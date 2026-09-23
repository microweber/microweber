<?php

namespace Modules\Pictures\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Media\Models\Media;

/**
 * task-2026-09-15-qskit — Pictures gallery CRUD for the Live-Edit quick panel's
 * inline image list. Pictures render from Media rows scoped to
 * (rel_type='module', rel_id=<module id>), ordered by position (same query the
 * module uses); new rows are media_type='picture'. Filenames come from the
 * shared media picker (mw.filePickerDialog) on the client.
 *
 * Admin-guarded (session + admin auth). Every write carries rel_id so deletes /
 * reorders are scoped to this module's own media, never global Media by id.
 */
class PictureItemsController extends Controller
{
    private function relId(Request $request): string
    {
        return (string) $request->input('rel_id', $request->query('rel_id', ''));
    }

    /**
     * task-2026-09-23 — a Pictures module can render EITHER its own media
     * (rel_type='module', rel_id=<module id>) OR the current content's media
     * (rel="content" → PicturesModule reads rel_type=morph(Content), rel_id=
     * content id — this is the product/page gallery). The quick-settings panel
     * must write to whichever store the module reads from, else edits save to the
     * wrong relation and never appear (the "product gallery picture didn't show"
     * bug). Default stays 'module' for back-compat.
     */
    private function relType(Request $request): string
    {
        $t = strtolower((string) $request->input('rel_type', $request->query('rel_type', 'module')));
        if (in_array($t, ['content', 'page', 'pages', 'post', 'posts', 'product', 'products'], true)) {
            return morph_name(\Modules\Content\Models\Content::class);
        }

        return 'module';
    }

    private function scoped(string $relId, string $relType)
    {
        return Media::query()->where('rel_type', $relType)->where('rel_id', $relId);
    }

    public function index(Request $request)
    {
        $relId = $this->relId($request);
        if ($relId === '') {
            return response()->json(['items' => []]);
        }
        $items = $this->scoped($relId, $this->relType($request))->orderBy('position', 'asc')->get(['id', 'filename'])
            ->map(fn ($m) => ['id' => $m->id, 'url' => (string) $m->filename])
            ->values();

        return response()->json(['items' => $items]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rel_id' => 'required|string',
            'filename' => 'required|string',
            'rel_type' => 'nullable|string',
        ]);

        $relType = $this->relType($request);
        $maxPosition = (int) $this->scoped($data['rel_id'], $relType)->max('position');

        $media = new Media();
        $media->rel_id = $data['rel_id'];
        $media->rel_type = $relType;
        $media->media_type = 'picture';
        $media->filename = $data['filename'];
        $media->position = $maxPosition + 1;
        $media->save();

        return response()->json(['id' => $media->id, 'url' => (string) $media->filename]);
    }

    public function destroy(Request $request, $id)
    {
        $relId = $this->relId($request);
        if ($relId === '') {
            abort(422, 'rel_id required');
        }
        $this->scoped($relId, $this->relType($request))->where('id', $id)->delete();

        return response()->json(['ok' => true]);
    }

    public function reorder(Request $request)
    {
        $relId = $this->relId($request);
        if ($relId === '') {
            abort(422, 'rel_id required');
        }
        $relType = $this->relType($request);
        foreach (array_values((array) $request->input('ids', [])) as $position => $id) {
            $this->scoped($relId, $relType)->where('id', $id)->update(['position' => $position]);
        }

        return response()->json(['ok' => true]);
    }
}
