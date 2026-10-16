<?php

namespace MicroweberPackages\App\Utils\ParserHelpers;

/**
 * Assigns stable, unique module IDs following the legacy rules.
 *
 * ID generation rules:
 *
 * 1. If a module has an explicit `id` attribute, use it as-is (never touch it).
 *
 * 2. Otherwise, build the base ID from the module type:
 *    base = moduleCssClass(type)         e.g. "module-btn"
 *
 * 3. If the module is inside an edit field with rel=content/page/post/product:
 *    → append "-{contentId}"             e.g. "module-btn-3"
 *    If rel=inherit:
 *    → append "-{inheritedParentId}"     e.g. "module-btn-3"
 *    If rel=global or rel=module or no edit field:
 *    → no content id appended            e.g. "module-btn"
 *
 * 4. If the module is inside a parent module's edit field with rel=module:
 *    → prefix the parent field:          e.g. "{parentField}-module-btn"
 *
 * 5. Duplicate modules of the same type in the same scope get "--N":
 *    First  → module-btn-3
 *    Second → module-btn-3--1
 *    Third  → module-btn-3--2
 *
 * 6. Nested edit fields create separate scopes.
 *    Inner <div class="edit" rel="content" field="x" rel-id="1"> resets
 *    the counter for that scope.
 *
 * 7. IDs are cleaned: /, \, _, space, ;, ., # → - and lowercased.
 */
class ModuleIdAllocator
{
    /**
     * Tracks how many modules of each type have been seen per scope.
     * Key = scope identifier, Value = [moduleName => count]
     * @var array<string, array<string, int>>
     */
    private array $counters = [];

    /**
     * Tracks all allocated IDs per scope to detect collisions.
     * @var array<string, array<string, true>>
     */
    private array $allocatedIds = [];

    /**
     * Global set of all allocated IDs (cross-scope).
     * @var array<string, true>
     */
    private array $globalIds = [];

    /**
     * IDs that are known to come from the database (pre-existing).
     * @var array<string, true>
     */
    private array $databaseIds = [];

    /**
     * Allocate an ID for a module tag.
     *
     * @param string      $moduleName  The module type (e.g. "btn", "layouts")
     * @param string|null $existingId  Explicit id attribute from the tag, or null
     * @param string      $editRel     The rel attribute of the enclosing .edit field (e.g. "content", "global")
     * @param string|null $editField   The field attribute of the enclosing .edit field
     * @param int|null    $contentId   The content ID to use for content-scoped modules
     * @param string      $scope       A scope identifier (e.g. the content_id or "global")
     * @param string|null $parentModuleField  If rel=module, the parent module's field name
     * @return string                  The allocated module ID
     */
    public function allocate(
        string  $moduleName,
        ?string $existingId = null,
        string  $editRel = '',
        ?string $editField = null,
        ?int    $contentId = null,
        string  $scope = 'global',
        ?string $parentModuleField = null
    ): string {
        // Rule 1: explicit ID is never touched
        if ($existingId !== null && $existingId !== '') {
            $this->registerAllocatedId($scope, $existingId);
            return $existingId;
        }

        // Rule 2: build base ID
        $baseId = $this->moduleCssClass($moduleName);

        // Rule 4: parent module field prefix
        if ($parentModuleField !== null && $editRel === 'module') {
            $baseId = $parentModuleField . '-' . $baseId;
        }

        // Clean the base ID
        $baseId = $this->cleanModId($baseId);

        // Rule 3: append content ID based on rel
        $appendContentId = $this->shouldAppendContentId($editRel);
        if ($appendContentId && $contentId !== null) {
            $baseId = $baseId . '-' . $contentId;
        }

        // Rule 5: handle duplicates within the scope
        if (!isset($this->counters[$scope])) {
            $this->counters[$scope] = [];
        }
        if (!isset($this->counters[$scope][$moduleName])) {
            $this->counters[$scope][$moduleName] = 0;
        }

        $count = $this->counters[$scope][$moduleName];
        $modId = $count === 0 ? $baseId : $baseId . '--' . $count;

        // Handle collisions with database IDs or previously allocated IDs
        while ($this->isCollision($scope, $modId)) {
            $count++;
            $modId = $baseId . '--' . $count;
        }

        $this->counters[$scope][$moduleName] = $count + 1;
        $this->registerAllocatedId($scope, $modId);

        return $modId;
    }

    /**
     * Register an ID that exists in the database.
     */
    public function registerDatabaseId(string $id): void
    {
        $this->databaseIds[$id] = true;
    }

    /**
     * Check if an ID is from the database.
     */
    public function isDatabaseId(string $id): bool
    {
        return isset($this->databaseIds[$id]);
    }

    /**
     * Reset all state.
     */
    public function reset(): void
    {
        $this->counters = [];
        $this->allocatedIds = [];
        $this->globalIds = [];
        $this->databaseIds = [];
    }

    /**
     * Determine if content ID should be appended based on the edit field rel.
     */
    private function shouldAppendContentId(string $rel): bool
    {
        return in_array($rel, ['content', 'page', 'post', 'product', 'inherit'], true);
    }

    /**
     * Generate the CSS class name for a module (used as ID base).
     */
    public function moduleCssClass(string $moduleName): string
    {
        $class = str_replace('/', '-', $moduleName);
        $class = str_replace('\\', '-', $class);
        $class = str_replace(' ', '-', $class);
        $class = str_replace('%20', '-', $class);
        $class = str_replace('_', '-', $class);
        return 'module-' . strtolower($class);
    }

    /**
     * Clean a module ID string.
     */
    public function cleanModId(string $modId): string
    {
        $modId = str_replace(' ', '-', $modId);
        $modId = str_replace('/', '-', $modId);
        $modId = str_replace('\\', '-', $modId);
        $modId = str_replace('_', '-', $modId);
        $modId = str_replace(';', '-', $modId);
        $modId = str_replace('.', '-', $modId);
        $modId = str_replace('#', '-', $modId);
        $modId = strtolower($modId);
        return trim($modId);
    }

    private function isCollision(string $scope, string $modId): bool
    {
        return isset($this->allocatedIds[$scope][$modId])
            || isset($this->databaseIds[$modId]);
    }

    private function registerAllocatedId(string $scope, string $modId): void
    {
        if (!isset($this->allocatedIds[$scope])) {
            $this->allocatedIds[$scope] = [];
        }
        $this->allocatedIds[$scope][$modId] = true;
        $this->globalIds[$modId] = true;
    }
}
