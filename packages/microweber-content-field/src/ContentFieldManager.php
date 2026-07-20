<?php

namespace MicroweberPackages\ContentField;

use Illuminate\Support\Facades\DB;

/**
 * Standalone service for reading, writing, and drafting content fields.
 *
 * Registered as app('content_field_manager') by the package service provider.
 */
class ContentFieldManager
{
    // -----------------------------------------------------------------
    //  READ
    // -----------------------------------------------------------------

    /**
     * Retrieve a single content field row.
     *
     * @param  array<string,mixed>|string  $data  Query parameters (field, rel_type, rel_id, is_draft, all, full …)
     * @return mixed  The field value, the full row array, or false
     */
    public function getField(array|string $data): mixed
    {
        if (is_string($data)) {
            parse_str(str_replace('&amp;', '&', $data), $data);
        }

        /** @var array<string,mixed> $data */

        $table = 'content_fields';
        if (! empty($data['is_draft'])) {
            $table = 'content_fields_drafts';
        }

        // Normalise legacy 'data-id' key
        if (! isset($data['rel_id']) && isset($data['data-id'])) {
            $data['rel_id'] = $data['data-id'];
        }

        // Simple single-field lookup (non-draft, non-"all" mode)
        if (empty($data['is_draft']) && empty($data['all']) && isset($data['rel_type'], $data['field'])) {
            $field   = is_string($data['field']) ? $data['field'] : '';
            $relType = is_string($data['rel_type']) ? $data['rel_type'] : '';

            return $this->getFieldData(
                $field,
                $relType,
                $data['rel_id'] ?? false,
                ! empty($data['full']),
            );
        }

        // Fallback: raw query builder for draft / "all" / complex queries
        $query = DB::table($table);

        if (isset($data['field'])) {
            $query->where('field', $data['field']);
        }
        if (isset($data['rel_type'])) {
            $query->where('rel_type', $data['rel_type']);
        }
        if (isset($data['rel_id'])) {
            $query->where('rel_id', $data['rel_id']);
        }
        if (isset($data['url'])) {
            $query->where('url', $data['url']);
        }
        if (isset($data['created_at'])) {
            $createdAt = is_string($data['created_at']) ? $data['created_at'] : '';
            // support "[lt]date" syntax
            if (str_starts_with($createdAt, '[lt]')) {
                $query->where('created_at', '<', substr($createdAt, 4));
            } else {
                $query->where('created_at', $createdAt);
            }
        }

        if (! empty($data['order_by'])) {
            $orderBy = is_string($data['order_by']) ? $data['order_by'] : '';
            $parts   = explode(' ', trim($orderBy), 2);
            $query->orderBy($parts[0], $parts[1] ?? 'asc');
        }

        if (isset($data['limit'])) {
            $limit = is_numeric($data['limit']) ? (int) $data['limit'] : 1;
            $query->limit($limit);
        }
        if (isset($data['current_page'], $data['limit'])) {
            $cp = is_numeric($data['current_page']) ? (int) $data['current_page'] : 1;
            $lm = is_numeric($data['limit']) ? (int) $data['limit'] : 1;
            $query->offset(($cp - 1) * $lm);
        }

        if (empty($data['all'])) {
            $row = $query->first();
            if (! $row) {
                return false;
            }
            /** @var array<string,mixed> $rowArr */
            $rowArr = (array) $row;
            if (empty($data['full']) && isset($rowArr['value'])) {
                return $rowArr['value'];
            }
            return $rowArr;
        }

        /** @var list<array<string,mixed>> $rows */
        $rows = $query->get()->map(fn (object $r): array => (array) $r)->toArray();

        if (isset($data['fields'])) {
            $col = is_string($data['fields']) ? $data['fields'] : '';
            return array_map(fn (array $r): mixed => $r[$col] ?? $r, $rows);
        }

        return $rows !== [] ? $rows : false;
    }

    /**
     * Low-level read of a content field by field name, rel_type, and optional rel_id.
     *
     * @return array<string,mixed>|string|false
     */
    public function getFieldData(string $field, string $relType, mixed $relId = false, bool $full = false): array|string|false
    {
        $query = DB::table('content_fields')
            ->where('field', $field)
            ->where('rel_type', $relType);

        if ($relId) {
            $query->where('rel_id', $relId);
        }

        $row = $query->first();

        if (! $row) {
            return false;
        }

        /** @var array<string,mixed> $rowArr */
        $rowArr = (array) $row;

        if ($full) {
            return $rowArr;
        }

        if (isset($rowArr['value'])) {
            return is_string($rowArr['value']) ? $rowArr['value'] : '';
        }

        return false;
    }

    // -----------------------------------------------------------------
    //  WRITE
    // -----------------------------------------------------------------

    /**
     * Save (insert or update) a content field row.
     *
     * @param  array<string,mixed>  $data  Must include rel_type, rel_id, field, value.
     *                                     Optionally is_draft, url, checksum.
     * @return int|false  The row ID on success, false on validation failure.
     */
    public function saveField(array $data): int|false
    {
        if (empty($data['rel_type']) || ! isset($data['rel_id'])) {
            return false;
        }

        $table   = 'content_fields';
        $isDraft = ! empty($data['is_draft']);

        if ($isDraft) {
            $table = 'content_fields_drafts';
        }

        // Clean old drafts when saving a new draft with a URL
        if ($isDraft && ! empty($data['url'])) {
            $this->cleanOldDrafts($data);
        }

        // Find existing row
        $fieldName = is_string($data['field'] ?? '') ? ($data['field'] ?? '') : '';
        $relType   = is_string($data['rel_type']) ? $data['rel_type'] : '';
        $relId     = $data['rel_id'];

        if ($isDraft && ! empty($data['url'])) {
            // For drafts, don't look for an existing row — always insert
            $existing = null;
        } else {
            $existing = DB::table($table)
                ->where('field', $fieldName)
                ->where('rel_type', $relType)
                ->where('rel_id', $relId)
                ->first();
        }

        $now = now();

        /** @var array<string,mixed> $row */
        $row = [
            'field'      => $fieldName,
            'rel_type'   => $relType,
            'rel_id'     => $relId,
            'value'      => $data['value'] ?? '',
            'updated_at' => $now,
        ];

        if ($isDraft) {
            $row['url']        = $data['url'] ?? '';
            $row['session_id'] = $data['session_id'] ?? '';
            $row['is_temp']    = $data['is_temp'] ?? 0;
        }

        if (isset($data['created_by'])) {
            $row['created_by'] = $data['created_by'];
        }
        if (isset($data['edited_by'])) {
            $row['edited_by'] = $data['edited_by'];
        }

        if ($existing !== null) {
            $existingId = ((array) $existing)['id'] ?? null;
            DB::table($table)->where('id', $existingId)->update($row);

            return is_numeric($existingId) ? (int) $existingId : 0;
        }

        $row['created_at'] = $now;

        return (int) DB::table($table)->insertGetId($row);
    }

    /**
     * Delete content field rows matching the given filter.
     *
     * @param  array<string,mixed>  $filter  Keys: field, rel_type, rel_id (all optional).
     * @param  bool  $drafts  When true, deletes from the drafts table.
     * @return int  Number of deleted rows.
     */
    public function deleteField(array $filter, bool $drafts = false): int
    {
        $table = $drafts ? 'content_fields_drafts' : 'content_fields';
        $query = DB::table($table);

        if (isset($filter['field'])) {
            $query->where('field', $filter['field']);
        }
        if (isset($filter['rel_type'])) {
            $query->where('rel_type', $filter['rel_type']);
        }
        if (isset($filter['rel_id'])) {
            $query->where('rel_id', $filter['rel_id']);
        }
        if (isset($filter['id'])) {
            $query->where('id', $filter['id']);
        }

        return $query->delete();
    }

    /**
     * Delete all content fields for a given rel_type and rel_id.
     */
    public function deleteByRelation(string $relType, mixed $relId): int
    {
        return $this->deleteField(['rel_type' => $relType, 'rel_id' => $relId]);
    }

    /**
     * Remove duplicate content field rows, keeping only one per field+rel_type+rel_id.
     */
    public function deduplicateGlobalFields(string $field, string $relType, mixed $relId): void
    {
        $existing = DB::table('content_fields')
            ->where('field', $field)
            ->where('rel_type', $relType)
            ->get();

        if ($existing->count() <= 1) {
            return;
        }

        $i = 1;
        foreach ($existing as $row) {
            if ($row->rel_id != $relId) { // @phpstan-ignore notEqual.alwaysTrue
                DB::table('content_fields')->where('id', $row->id)->delete();
            }
            if ($i > 1) {
                DB::table('content_fields')->where('id', $row->id)->delete();
            }
            $i++;
        }
    }

    // -----------------------------------------------------------------
    //  DRAFTS
    // -----------------------------------------------------------------

    /**
     * Clean old draft entries to prevent unbounded growth.
     *
     * @param  array<string,mixed>  $data  Must contain field, rel_type, rel_id, url.
     */
    public function cleanOldDrafts(array $data): void
    {
        /** @var int $ts */
        $ts = strtotime('-5 min');
        $lastSavedDate = date('Y-m-d H:i:s', $ts);

        $old = DB::table('content_fields_drafts')
            ->where('rel_type', $data['rel_type'])
            ->where('rel_id', $data['rel_id'])
            ->where('field', $data['field'] ?? '')
            ->where('url', $data['url'])
            ->where('created_at', '<', $lastSavedDate)
            ->orderBy('id', 'desc')
            ->skip(20)
            ->take(200)
            ->get();

        foreach ($old as $item) {
            DB::table('content_fields_drafts')->where('id', $item->id)->delete();
        }
    }

    // -----------------------------------------------------------------
    //  HELPERS
    // -----------------------------------------------------------------

    /**
     * Check whether a field exists for the given relation.
     */
    public function fieldExists(string $field, string $relType, mixed $relId = null): bool
    {
        $query = DB::table('content_fields')
            ->where('field', $field)
            ->where('rel_type', $relType);

        if ($relId !== null) {
            $query->where('rel_id', $relId);
        }

        return $query->exists();
    }
}
