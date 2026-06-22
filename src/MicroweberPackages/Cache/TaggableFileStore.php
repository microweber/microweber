<?php

namespace MicroweberPackages\Cache;

/**
 * Backwards-compatibility alias.
 *
 * The real implementation now lives in the standalone package
 * microweber-packages/taggable-file-cache. This class simply
 * extends it so that any code referencing the old FQCN keeps working.
 */
class TaggableFileStore extends \MicroweberPackages\TaggableFileCache\TaggableFileStore
{
}
