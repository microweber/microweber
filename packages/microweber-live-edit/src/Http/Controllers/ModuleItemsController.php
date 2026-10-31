<?php

namespace MicroweberPackages\LiveEdit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

abstract class ModuleItemsController extends Controller
{
    /** @return class-string */
    abstract protected function modelClass(): string;

    /** Editable text fields; the first is used as the row's collapsed label. */
    abstract protected function fields(): array;

    /**
     * Top-level key in the module's default_content.json holding the default
     * items (e.g. 'testimonials', 'teamcard', 'slides'). Return null for
     * modules that ship no defaults — then the quick-edit panel is legitimately
     * empty until the operator adds items.
     */
    protected function defaultContentKey(): ?string
    {
        return null;
    }

    /**
     * The owning module's default_content.json path. Derived from the model's
     * `Modules\<Name>\...` namespace by default; override when a module keeps
     * its defaults elsewhere (e.g. Slider's resources/default-content/).
     */
    protected function defaultContentFile(): ?string
    {
        $parts = explode('\\', $this->modelClass());
        if (($parts[0] ?? '') !== 'Modules' || !isset($parts[1])) {
            return null;
        }
        return base_path('Modules/' . $parts[1] . '/default_content.json');
    }

    /**
     * The option key a module sets once it has materialised its defaults (its
     * `get<X>CreatedDefault` guard). Shared with the module's own renderer so
     * the quick-edit never re-seeds defaults the operator deliberately cleared.
     */
    protected function defaultSeedGuardKey(): ?string
    {
        return null;
    }

    /**
     * Materialise the module's default_content.json into editable rows for this
     * rel_id — so the quick-edit panel shows the SAME defaults the module
     * renders instead of an empty list (the "edit testimonials shows nothing"
     * report). Only runs when the module has no rows yet AND its one-time
     * default guard is unset, so it never resurrects intentionally-cleared
     * content and never double-seeds alongside the module renderer (whichever
     * runs first wins; the other sees rows and skips).
     */
    protected function seedDefaultsIfEmpty(string $relId)
    {
        $key = $this->defaultContentKey();
        $file = $this->defaultContentFile();
        if ($key === null || $file === null || ! is_file($file)) {
            return null;
        }

        $guard = $this->defaultSeedGuardKey();
        if ($guard !== null && function_exists('get_option') && get_option($guard, $relId)) {
            return null;
        }

        $raw = @file_get_contents($file);
        $data = is_string($raw) ? json_decode($raw, true) : null;
        $items = is_array($data) ? ($data[$key] ?? null) : null;
        if (! is_array($items) || $items === []) {
            return null;
        }

        $model = $this->modelClass();
        $fillable = array_flip((new $model())->getFillable());
        $pos = 0;
        foreach ($items as $itemRaw) {
            if (! is_array($itemRaw)) {
                continue;
            }
            $row = new $model();
            $row->fill(array_intersect_key($itemRaw, $fillable));
            $row->rel_type = 'module';
            $row->rel_id = $relId;
            $row->position = $pos++;
            $row->save();
        }

        if ($guard !== null && function_exists('save_option')) {
            save_option($guard, '1', $relId);
        }

        return $model::where('rel_type', 'module')->where('rel_id', $relId)
            ->orderBy('position', 'asc')
            ->get(array_merge(['id'], $this->fields()));
    }

    private function present($item): array
    {
        $out = ['id' => $item->id];
        foreach ($this->fields() as $f) {
            $out[$f] = (string) $item->$f;
        }
        return $out;
    }

    public function index(Request $request)
    {
        $relId = (string) $request->input('rel_id', $request->query('rel_id', ''));
        if ($relId === '') {
            return response()->json(['items' => []]);
        }
        $model = $this->modelClass();
        $rows = $model::where('rel_type', 'module')->where('rel_id', $relId)
            ->orderBy('position', 'asc')
            ->get(array_merge(['id'], $this->fields()));

        if ($rows->isEmpty()) {
            $seeded = $this->seedDefaultsIfEmpty($relId);
            if ($seeded !== null) {
                $rows = $seeded;
            }
        }

        return response()->json(['items' => $rows->map(fn ($r) => $this->present($r))->values()]);
    }

    public function store(Request $request)
    {
        $relId = (string) $request->input('rel_id', '');
        if ($relId === '') {
            abort(422, 'rel_id required');
        }
        $model = $this->modelClass();
        $item = new $model();
        $item->rel_id = $relId;
        $item->rel_type = 'module';
        foreach ($this->fields() as $f) {
            $item->$f = (string) $request->input($f, '');
        }
        $item->position = (int) $model::where('rel_type', 'module')->where('rel_id', $relId)->max('position') + 1;
        $item->save();

        return response()->json($this->present($item));
    }

    public function update(Request $request, $id)
    {
        $model = $this->modelClass();
        $item = $model::where('rel_type', 'module')->findOrFail($id);
        foreach ($this->fields() as $f) {
            if ($request->has($f)) {
                $item->$f = (string) $request->input($f, '');
            }
        }
        $item->save();

        return response()->json($this->present($item));
    }

    public function destroy($id)
    {
        $model = $this->modelClass();
        $model::where('rel_type', 'module')->findOrFail($id)->delete();

        return response()->json(['ok' => true]);
    }

    public function reorder(Request $request)
    {
        $ids = (array) $request->input('ids', []);
        $model = $this->modelClass();
        foreach (array_values($ids) as $position => $id) {
            $model::where('rel_type', 'module')->where('id', $id)->update(['position' => $position]);
        }

        return response()->json(['ok' => true]);
    }
}
