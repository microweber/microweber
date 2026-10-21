<?php

namespace MicroweberPackages\Template\ContentJson;

use Modules\Content\Models\Content;
use Modules\Menu\Models\Menu;

/**
 * Exports the current site (pages, posts, products and menus) to the flexible
 * content.json format consumed by {@see ContentJsonInstaller}. The inverse of the
 * installer: bodies are exported as stored "content" HTML (the portable form),
 * and parent/menu targets are exported as page URLs so the payload can be imported
 * into any template on any site.
 *
 * Full-site export by default; pass a template to setTemplate() to export only the
 * content whose active_site_template matches (e.g. export a Big-template site and
 * import it under another template).
 */
class ContentJsonExporter
{
    protected ?string $template = null;      // null = full site; else filter by active_site_template
    protected bool $includeProducts = true;
    protected bool $includePosts = true;
    protected bool $includeMenus = true;

    public function setTemplate(?string $template): self { $this->template = $template; return $this; }
    public function setIncludeProducts(bool $v): self { $this->includeProducts = $v; return $this; }
    public function setIncludePosts(bool $v): self { $this->includePosts = $v; return $this; }
    public function setIncludeMenus(bool $v): self { $this->includeMenus = $v; return $this; }

    /** @return array The content.json payload. */
    public function export(): array
    {
        $out = [];
        if ($this->template) {
            $out['template'] = $this->template;
        }

        $idToUrl = $this->buildIdToUrlMap();

        $out['pages'] = $this->exportType('page', $idToUrl);
        if ($this->includePosts) {
            $out['posts'] = $this->exportType('post', $idToUrl);
        }
        if ($this->includeProducts) {
            $out['products'] = $this->exportProducts($idToUrl);
        }
        if ($this->includeMenus) {
            $out['menus'] = $this->exportMenus($idToUrl);
        }

        // Drop empty collections for a tidy payload.
        foreach (['pages', 'posts', 'products', 'menus'] as $k) {
            if (empty($out[$k])) {
                unset($out[$k]);
            }
        }

        return $out;
    }

    public function toJson(int $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE): string
    {
        return (string) json_encode($this->export(), $flags);
    }

    /** Write the export to a file; returns bytes written or false. */
    public function writeTo(string $path)
    {
        return file_put_contents($path, $this->toJson());
    }

    protected function baseQuery()
    {
        $q = Content::where('is_deleted', 0);
        if ($this->template) {
            $q->where('active_site_template', $this->template);
        }
        return $q;
    }

    /** Map every (in-scope) content id to its url so parents/menu targets export as urls. */
    protected function buildIdToUrlMap(): array
    {
        $map = [];
        foreach ((clone $this->baseQuery())->get(['id', 'url']) as $row) {
            if ($row->url) {
                $map[(int) $row->id] = $row->url;
            }
        }
        return $map;
    }

    protected function exportType(string $contentType, array $idToUrl): array
    {
        $rows = (clone $this->baseQuery())->where('content_type', $contentType)->orderBy('id')->get();
        $items = [];
        foreach ($rows as $row) {
            $items[] = $this->baseItem($row, $idToUrl);
        }
        return $items;
    }

    protected function exportProducts(array $idToUrl): array
    {
        $productClass = '\\Modules\\Product\\Models\\Product';
        $rows = class_exists($productClass)
            ? $this->scopeProductQuery($productClass::query())->where('is_deleted', 0)->orderBy('id')->get()
            : (clone $this->baseQuery())->where('content_type', 'product')->orderBy('id')->get();

        $items = [];
        foreach ($rows as $row) {
            $item = $this->baseItem($row, $idToUrl);
            // Product price/qty/sku are appended attributes on the Product model.
            if (isset($row->price) && $row->price !== null && $row->price !== '') {
                $item['price'] = is_numeric($row->price) ? $row->price + 0 : $row->price;
            }
            foreach (['sku', 'qty'] as $extra) {
                if (isset($row->{$extra}) && $row->{$extra} !== '' && $row->{$extra} !== null) {
                    $item[$extra] = $row->{$extra};
                }
            }
            $items[] = $item;
        }
        return $items;
    }

    protected function scopeProductQuery($query)
    {
        if ($this->template) {
            $query->where('active_site_template', $this->template);
        }
        return $query;
    }

    /** The shared page/post/product fields (body exported as portable html). */
    protected function baseItem($row, array $idToUrl): array
    {
        $item = [
            'url' => $row->url,
            'title' => $row->title,
            'content_type' => $row->content_type,
        ];
        if ($row->subtype) {
            $item['subtype'] = $row->subtype;
        }
        if ((int) $row->is_home === 1) {
            $item['is_home'] = true;
        }
        if ($row->layout_file) {
            $item['layout_file'] = $row->layout_file;
        }
        if ($row->active_site_template) {
            $item['active_site_template'] = $row->active_site_template;
        }
        $parentId = (int) $row->parent;
        if ($parentId && isset($idToUrl[$parentId])) {
            $item['parent'] = $idToUrl[$parentId];
        }
        if ($row->description) {
            $item['description'] = $row->description;
        }
        $item['html'] = (string) ($row->content ?? '');
        return $item;
    }

    /** Export menus as { menu_title: [ {page|url, title}, … ] }. */
    protected function exportMenus(array $idToUrl): array
    {
        $out = [];
        $menus = Menu::where('item_type', 'menu')->orderBy('position')->get();
        foreach ($menus as $menu) {
            $items = Menu::where('item_type', 'menu_item')
                ->where('parent_id', $menu->id)
                ->orderBy('position')
                ->get();
            $entries = [];
            foreach ($items as $mi) {
                $entry = [];
                $cid = (int) $mi->content_id;
                if ($cid && isset($idToUrl[$cid])) {
                    $entry['page'] = $idToUrl[$cid];
                } elseif ($cid) {
                    // Content exists but is out of the template scope — resolve its url directly.
                    $url = Content::where('id', $cid)->value('url');
                    if ($url) {
                        $entry['page'] = $url;
                    }
                }
                if (!isset($entry['page']) && $mi->url) {
                    $entry['url'] = $mi->url;
                }
                if ($mi->title) {
                    $entry['title'] = $mi->title;
                }
                if ($entry) {
                    $entries[] = $entry;
                }
            }
            if ($entries) {
                $out[$menu->title] = $entries;
            }
        }
        return $out;
    }
}
