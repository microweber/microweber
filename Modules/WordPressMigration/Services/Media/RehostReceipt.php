<?php

namespace Modules\WordPressMigration\Services\Media;

/**
 * Structured outcome of a successful rehost.
 *
 * The rewriter only needs the URL, but callers that attach media to
 * a content row (featured image, gallery) also want the media id, so
 * they don't have to look it up by filename afterwards.
 */
final class RehostReceipt
{
    public function __construct(
        public readonly int $mediaId,
        public readonly string $url,
    ) {}
}
