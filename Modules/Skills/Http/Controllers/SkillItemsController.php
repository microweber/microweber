<?php

namespace Modules\Skills\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * task-2026-09-15-qskit — Skills inline item-list CRUD for the Live-Edit quick
 * panel. Unlike the DB-backed list modules, Skills stores its items as a JSON
 * array in the module option `skills` ({skill, percent, style}) — so this CRUDs
 * that option directly (via get_option/save_option scoped to the module id)
 * rather than an Eloquent model. Item ids are the array index, so every write
 * carries rel_id to scope it to the module.
 *
 * Admin-guarded (session + admin auth) — mutates stored options.
 */
class SkillItemsController extends Controller
{
    private const STYLES = ['primary', 'warning', 'danger', 'success', 'info'];

    private function relId(Request $request): string
    {
        return (string) $request->input('rel_id', $request->query('rel_id', ''));
    }

    private function load(string $relId): array
    {
        $decoded = @json_decode(get_option('skills', $relId), true);
        return is_array($decoded) ? array_values($decoded) : [];
    }

    private function persist(string $relId, array $items): void
    {
        save_option('skills', json_encode(array_values($items)), $relId);
    }

    private function present(array $items): array
    {
        $out = [];
        foreach ($items as $i => $it) {
            $out[] = [
                'id' => $i,
                'skill' => (string) ($it['skill'] ?? ''),
                'percent' => (string) ($it['percent'] ?? ''),
                'style' => (string) ($it['style'] ?? 'primary'),
            ];
        }
        return $out;
    }

    private function style($value): string
    {
        $value = (string) $value;
        return in_array($value, self::STYLES, true) ? $value : 'primary';
    }

    public function index(Request $request)
    {
        $relId = $this->relId($request);
        if ($relId === '') {
            return response()->json(['items' => []]);
        }
        return response()->json(['items' => $this->present($this->load($relId))]);
    }

    public function store(Request $request)
    {
        $relId = $this->relId($request);
        if ($relId === '') {
            abort(422, 'rel_id required');
        }
        $items = $this->load($relId);
        $items[] = [
            'skill' => (string) $request->input('skill', ''),
            'percent' => (string) $request->input('percent', ''),
            'style' => $this->style($request->input('style', 'primary')),
        ];
        $this->persist($relId, $items);

        return response()->json(['ok' => true]);
    }

    public function update(Request $request, $id)
    {
        $relId = $this->relId($request);
        if ($relId === '') {
            abort(422, 'rel_id required');
        }
        $items = $this->load($relId);
        $i = (int) $id;
        if (!isset($items[$i])) {
            abort(404);
        }
        if ($request->has('skill')) {
            $items[$i]['skill'] = (string) $request->input('skill', '');
        }
        if ($request->has('percent')) {
            $items[$i]['percent'] = (string) $request->input('percent', '');
        }
        if ($request->has('style')) {
            $items[$i]['style'] = $this->style($request->input('style', 'primary'));
        }
        $this->persist($relId, $items);

        return response()->json(['ok' => true]);
    }

    public function destroy(Request $request, $id)
    {
        $relId = $this->relId($request);
        if ($relId === '') {
            abort(422, 'rel_id required');
        }
        $items = $this->load($relId);
        $i = (int) $id;
        if (isset($items[$i])) {
            array_splice($items, $i, 1);
        }
        $this->persist($relId, $items);

        return response()->json(['ok' => true]);
    }

    public function reorder(Request $request)
    {
        $relId = $this->relId($request);
        if ($relId === '') {
            abort(422, 'rel_id required');
        }
        $items = $this->load($relId);
        $new = [];
        foreach ((array) $request->input('ids', []) as $idx) {
            if (isset($items[(int) $idx])) {
                $new[] = $items[(int) $idx];
            }
        }
        $this->persist($relId, $new);

        return response()->json(['ok' => true]);
    }
}
