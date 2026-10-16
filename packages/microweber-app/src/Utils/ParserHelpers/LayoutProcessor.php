<?php

namespace MicroweberPackages\App\Utils\ParserHelpers;

/**
 * Top-level orchestrator that runs all parser helpers over a full layout string.
 *
 * This is a thin coordinator that:
 *  1. Uses ContentProtector to shield script/textarea/code/comments
 *  2. Uses TagLexer to find all <module> tags
 *  3. Uses AttributeParser to parse each tag's attributes
 *  4. Uses EditFieldExtractor to identify .edit field scopes
 *  5. Uses ModuleIdAllocator to assign stable IDs
 *  6. Uses ModuleRenderer to render each module
 *  7. Restores protected regions
 *
 * This class does NOT replace ParserProcessor yet — it's the new
 * coordinator that ParserProcessor will delegate to incrementally.
 */
class LayoutProcessor
{
    private TagLexer $lexer;
    private AttributeParser $attrParser;
    private ContentProtector $protector;
    private ModuleIdAllocator $idAllocator;
    private ModuleRenderer $renderer;
    private EditFieldExtractor $editFieldExtractor;

    public function __construct(
        ?TagLexer $lexer = null,
        ?AttributeParser $attrParser = null,
        ?ContentProtector $protector = null,
        ?ModuleIdAllocator $idAllocator = null,
        ?ModuleRenderer $renderer = null,
        ?EditFieldExtractor $editFieldExtractor = null
    ) {
        $this->lexer = $lexer ?? new TagLexer();
        $this->attrParser = $attrParser ?? new AttributeParser();
        $this->protector = $protector ?? new ContentProtector();
        $this->idAllocator = $idAllocator ?? new ModuleIdAllocator();
        $this->renderer = $renderer ?? new ModuleRenderer();
        $this->editFieldExtractor = $editFieldExtractor ?? new EditFieldExtractor($this->attrParser);
    }

    /**
     * Get the helper instances for direct access when needed.
     */
    public function getLexer(): TagLexer
    {
        return $this->lexer;
    }

    public function getAttributeParser(): AttributeParser
    {
        return $this->attrParser;
    }

    public function getContentProtector(): ContentProtector
    {
        return $this->protector;
    }

    public function getModuleIdAllocator(): ModuleIdAllocator
    {
        return $this->idAllocator;
    }

    public function getModuleRenderer(): ModuleRenderer
    {
        return $this->renderer;
    }

    public function getEditFieldExtractor(): EditFieldExtractor
    {
        return $this->editFieldExtractor;
    }

    /**
     * Process a layout string: protect regions, find modules, allocate IDs.
     *
     * This is a simplified processing pipeline that demonstrates the
     * coordination between helpers. The full ParserProcessor integration
     * adds module loading, edit field content loading, and recursive processing.
     *
     * @param string   $layout          The HTML layout to process
     * @param int|null $contentId       Current content ID
     * @param callable|null $moduleLoader  Callback to load module content: fn(string $type, array $attrs) => string
     * @param callable|null $editFieldLoader Callback to load saved edit-field content from the
     *                       data store: fn(string $field, string $rel, ?string $relId, ?int $contentId) => ?string.
     *                       When provided, each .edit region's inner default is replaced with the
     *                       stored content (recursively), matching the legacy edit-field flow.
     * @return string
     */
    /** Max recursion depth when re-processing rendered module output. */
    private const MAX_DEPTH = 8;

    /**
     * Resolver for rel="inherit" fields: fn(int $contentId) => ?int masterId.
     * Set per process() run so both edit-field loading and module-id scoping use
     * the inherited (master) content for inherit regions.
     * @var callable|null
     */
    private $inheritedParentResolver = null;

    public function process(
        string $layout,
        ?int $contentId = null,
        ?callable $moduleLoader = null,
        ?callable $editFieldLoader = null,
        ?callable $inheritedParentResolver = null
    ): string {
        if ($layout === '') {
            return '';
        }

        // Reset shared state once for this top-level run, then restore protected
        // regions once at the very end. processInner() accumulates protection and
        // id state across all recursion levels so nothing collides or leaks.
        $this->protector->reset();
        $this->idAllocator->reset();
        $this->inheritedParentResolver = $inheritedParentResolver;

        $result = $this->processInner($layout, $contentId, $moduleLoader, $editFieldLoader, 0);

        return $this->protector->restore($result);
    }

    /**
     * Core pipeline for one layout string. Does NOT reset or restore — that is
     * owned by process(). Re-enters itself for rendered module output so nested
     * modules / edit fields (e.g. a layout module's inner regions) are resolved,
     * bounded by MAX_DEPTH.
     */
    private function processInner(
        string $layout,
        ?int $contentId,
        ?callable $moduleLoader,
        ?callable $editFieldLoader,
        int $depth
    ): string {
        if ($layout === '') {
            return '';
        }

        // Step 1: Protect regions (accumulates into the shared protector)
        $layout = $this->protector->protect($layout);

        // Step 2: Normalize tag aliases
        $layout = str_replace('<mw ', '<module ', $layout);
        $layout = str_replace('<editable ', '<div class="edit" ', $layout);
        $layout = str_replace('</editable>', '</div>', $layout);
        $layout = str_replace('<microweber module=', '<module data-type=', $layout);
        $layout = str_replace('</microweber>', '', $layout);
        $layout = str_replace('></module>', '/>', $layout);

        // Step 2b: Load saved edit-field content from the data store,
        // replacing each .edit region's inline default with its stored value.
        if ($editFieldLoader !== null) {
            $layout = $this->resolveEditFields($layout, $editFieldLoader, $contentId);
        }

        // Step 3: Find edit fields and determine scopes
        $editFields = $this->editFieldExtractor->findEditFields($layout);

        // Step 4: Find and process module tags
        // Process in forward order so ID allocation is deterministic
        $moduleTags = $this->lexer->findModuleTags($layout);

        foreach ($moduleTags as $tagInfo) {
            $tag = $tagInfo['tag'];
            $attrs = $this->attrParser->parse($tag);
            $moduleName = $this->attrParser->getModuleType($attrs);

            if ($moduleName === null || $moduleName === '') {
                // Empty module — replace with nothing (fixes placeholder leak)
                $layout = $this->strReplaceFirst($tag, '', $layout);
                continue;
            }

            // Determine the edit field scope for this module.
            // Use the lexer's original-layout offset (NOT strpos on the mutated
            // layout): $editFields offsets and $tagInfo offsets share the same
            // pre-mutation coordinate system, so scope stays correct even after
            // earlier modules have been replaced (which shifts later positions).
            $scope = $this->determineScope($tagInfo['offset'], $editFields, $contentId);

            // Allocate ID
            $existingId = $attrs['id'] ?? null;
            $moduleId = $this->idAllocator->allocate(
                $moduleName,
                $existingId,
                $scope['rel'],
                $scope['field'],
                $scope['contentId'],
                $scope['scopeKey']
            );

            $attrs['id'] = $moduleId;
            $attrs['data-type'] = $moduleName;

            // Load module content. The loader (the real module renderer) may
            // return a string OR a Stringable / HtmlString / Htmlable — coerce
            // to string instead of requiring a raw string, otherwise every
            // module renders empty (the cause of all-layouts-blank).
            $content = '';
            if ($moduleLoader !== null) {
                $loadResult = $moduleLoader($moduleName, $attrs);
                if ($loadResult !== null && $loadResult !== false) {
                    $content = (string) $loadResult;
                }
            }

            // Recurse into the rendered module output so nested modules / edit
            // fields it emits (e.g. a layout module's inner regions) are resolved
            // too. Bounded by MAX_DEPTH and gated on actually containing work.
            if ($content !== '' && $depth < self::MAX_DEPTH
                && ($this->lexer->hasModuleTags($content)
                    || ($editFieldLoader !== null && $this->editFieldExtractor->hasEditFields($content)))) {
                $content = $this->processInner($content, $contentId, $moduleLoader, $editFieldLoader, $depth + 1);
            }

            // Determine rendering options
            $noWrap = $this->renderer->isNoWrap($attrs);
            $asElement = $this->renderer->isAsElement($attrs);
            $userClass = $attrs['class'] ?? '';

            // Render
            $rendered = $this->renderer->render(
                $moduleName, $moduleId, $attrs, $content,
                'div', $noWrap, $userClass, $asElement
            );

            // Replace only the first occurrence to handle duplicate tags correctly
            $layout = $this->strReplaceFirst($tag, $rendered, $layout);
        }

        return $layout;
    }

    /**
     * Replace each .edit region's inline default content with the value loaded
     * from the data store. Runs iteratively so that fields introduced by
     * already-loaded content (nested edit fields) are resolved too, and keys an
     * (rel|field|rel_id) set to avoid re-loading / infinite recursion.
     *
     * The edit field's own open/close tags are preserved (only the inner content
     * is swapped), so later scope detection still sees the enclosing region.
     */
    private function resolveEditFields(string $layout, callable $loader, ?int $contentId): string
    {
        $resolved = [];
        $maxIter = 100; // hard backstop against pathological recursion

        for ($iter = 0; $iter < $maxIter; $iter++) {
            $fields = $this->editFieldExtractor->findEditFields($layout);
            $didWork = false;

            foreach ($fields as $ef) {
                $key = ($ef['rel'] ?? '') . '|' . ($ef['field'] ?? '') . '|' . ($ef['rel_id'] ?? '');
                if (isset($resolved[$key])) {
                    continue;
                }
                $resolved[$key] = true;

                $cid = $this->editFieldExtractor->resolveContentId(
                    $ef['rel'],
                    $ef['rel_id'],
                    $contentId,
                    $this->inheritedParentResolver
                );

                $loaded = $loader($ef['field'], $ef['rel'], $ef['rel_id'], $cid);
                // Only replace when the store returned real content. edit_field()
                // returns FALSE for an empty field — that (and null / non-string)
                // must keep the inline default, NOT blank the region out.
                if (!is_string($loaded) || $loaded === '') {
                    continue;
                }

                // Shield comments / script / textarea / etc. inside the loaded
                // content too — it arrives after the initial protect() pass, so
                // without this a <module> inside a loaded HTML/Blade comment would
                // be wrongly tokenized and rendered. The same protector instance
                // accumulates these and restore() unwinds them at the end.
                $loaded = $this->protector->protect($loaded);

                // Splice the loaded content between the open tag and its close.
                $openEnd = $ef['offset'] + strlen($ef['tag']);
                $closeLen = strlen('</' . $ef['tag_name'] . '>');
                $closeStart = ($ef['end'] ?? strlen($layout)) - $closeLen;
                if ($closeStart < $openEnd) {
                    continue; // malformed / unbalanced — leave as-is
                }

                $layout = substr($layout, 0, $openEnd) . $loaded . substr($layout, $closeStart);
                $didWork = true;
                break; // offsets shifted — rescan from the top
            }

            if (!$didWork) {
                break;
            }
        }

        return $layout;
    }

    /**
     * Determine the edit field scope for a module at a given offset.
     *
     * Finds the innermost enclosing .edit field.
     */
    private function determineScope(int $offset, array $editFields, ?int $contentId): array
    {
        $scope = [
            'rel' => '',
            'field' => null,
            'contentId' => $contentId,
            'scopeKey' => 'global',
        ];

        // Find the innermost ENCLOSING edit field: the module offset must fall
        // between the field's open tag and its matching close (open < pos < end),
        // not merely after the open tag. This keeps a module placed after a
        // closed sibling .edit out of that sibling's scope. Among enclosing
        // fields, the one with the largest open offset is the innermost.
        $closest = null;
        foreach ($editFields as $ef) {
            $start = $ef['offset'];
            $end = $ef['end'] ?? PHP_INT_MAX;
            if ($offset > $start && $offset < $end) {
                if ($closest === null || $start > $closest['offset']) {
                    $closest = $ef;
                }
            }
        }

        if ($closest !== null) {
            $scope['rel'] = $closest['rel'];
            $scope['field'] = $closest['field'];

            $resolvedId = $this->editFieldExtractor->resolveContentId(
                $closest['rel'],
                $closest['rel_id'],
                $contentId,
                $this->inheritedParentResolver
            );
            $scope['contentId'] = $resolvedId;
            $scope['scopeKey'] = $this->editFieldExtractor->getScopeKey($closest['rel'], $resolvedId);
        }

        return $scope;
    }

    /**
     * Replace only the first occurrence of a string.
     */
    private function strReplaceFirst(string $search, string $replace, string $subject): string
    {
        $pos = strpos($subject, $search);
        if ($pos !== false) {
            return substr_replace($subject, $replace, $pos, strlen($search));
        }
        return $subject;
    }
}
