<?php

namespace MicroweberPackages\LiveEdit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * task-2026-09-15-qskit — shared CRUD for a module's DB-backed item list
 * (Accordion/FAQ/Tabs/Testimonials/Teamcard …), driving the Live-Edit
 * quick-settings inline item-list control.
 *
 * A concrete controller supplies the Eloquent model + the editable text fields;
 * this base handles list / create / update / delete / reorder for items scoped
 * to (rel_type='module', rel_id=<module id>). Fields are set by direct property
 * assignment (works regardless of the model's $fillable) — only the declared
 * fields are ever written, and rel_type is always forced to 'module'.
 *
 * Routes MUST be registered behind the `admin` middleware group — these mutate
 * the DB and must never be reachable unauthenticated.
 */
abstract class ModuleItemsController extends Controller
{
    /** @return class-string */
    abstract protected function modelClass(): string;

    /** Editable text fields; the first is used as the row's collapsed label. */
    abstract protected function fields(): array;

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
