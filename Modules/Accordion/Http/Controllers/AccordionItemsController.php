<?php

namespace Modules\Accordion\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accordion\Models\Accordion;

/**
 * task-2026-09-15-qskit — CRUD for accordion items from the Live-Edit
 * quick-settings inline item-list. Same data the AccordionTableList Livewire
 * editor manages (title / content, rel_id + rel_type='module', position), but
 * reachable over a thin JSON API so the floating panel can edit inline.
 *
 * All routes are behind the `admin` middleware group (session + admin auth) —
 * these mutate the DB, so they must never be reachable unauthenticated.
 * rel_type is always forced to 'module'; only the item's own title/content are
 * mass-assigned.
 */
class AccordionItemsController extends Controller
{
    private function relId(Request $request): string
    {
        return (string) $request->input('rel_id', $request->query('rel_id', ''));
    }

    public function index(Request $request)
    {
        $relId = $this->relId($request);
        if ($relId === '') {
            return response()->json(['items' => []]);
        }

        $items = Accordion::where('rel_id', $relId)
            ->where('rel_type', 'module')
            ->orderBy('position', 'asc')
            ->get(['id', 'title', 'content'])
            ->map(fn ($a) => ['id' => $a->id, 'title' => (string) $a->title, 'content' => (string) $a->content])
            ->values();

        return response()->json(['items' => $items]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rel_id' => 'required|string',
            'title' => 'nullable|string',
            'content' => 'nullable|string',
        ]);

        $maxPosition = (int) Accordion::where('rel_id', $data['rel_id'])
            ->where('rel_type', 'module')
            ->max('position');

        $item = new Accordion();
        $item->rel_id = $data['rel_id'];
        $item->rel_type = 'module';
        $item->title = $data['title'] ?? '';
        $item->content = $data['content'] ?? '';
        $item->position = $maxPosition + 1;
        $item->save();

        return response()->json(['id' => $item->id, 'title' => (string) $item->title, 'content' => (string) $item->content]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'content' => 'nullable|string',
        ]);

        $item = Accordion::where('rel_type', 'module')->findOrFail($id);
        if ($request->has('title')) {
            $item->title = $data['title'] ?? '';
        }
        if ($request->has('content')) {
            $item->content = $data['content'] ?? '';
        }
        $item->save();

        return response()->json(['id' => $item->id, 'title' => (string) $item->title, 'content' => (string) $item->content]);
    }

    public function destroy($id)
    {
        $item = Accordion::where('rel_type', 'module')->findOrFail($id);
        $item->delete();

        return response()->json(['ok' => true]);
    }

    public function reorder(Request $request)
    {
        $data = $request->validate(['ids' => 'required|array']);
        foreach (array_values($data['ids']) as $position => $id) {
            Accordion::where('rel_type', 'module')->where('id', $id)->update(['position' => $position]);
        }

        return response()->json(['ok' => true]);
    }
}
