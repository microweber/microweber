<?php

namespace Modules\MediaLibrary\Tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Media uploads must land in the per-site media tree on the configured disk.
 *
 * The media path helpers are keyed off MW_USERFILES_FOLDER_NAME, which the
 * multisite package sets to "userfiles/<domain>" per site, so media is isolated
 * per site AND addresses the same per-site subfolder on S3. Two Filament upload
 * surfaces used to bypass that with a hardcoded "userfiles/media" + "public"
 * disk, dropping every site's uploads into one shared local folder (cross-tenant
 * leak) and skipping S3. These assertions pin the behaviour and guard the fix.
 */
class MediaMultisiteUploadIsolationTest extends TestCase
{
    /** Source of a module file with all PHP comments stripped (so absence
     *  assertions can't match the explanatory prose in the code's own comments). */
    private function executableSource(string $relPath): string
    {
        $src = (string) file_get_contents(base_path($relPath));
        $out = '';
        foreach (token_get_all($src) as $token) {
            if (is_array($token)) {
                if (in_array($token[0], [T_COMMENT, T_DOC_COMMENT], true)) {
                    continue;
                }
                $out .= $token[1];
            } else {
                $out .= $token;
            }
        }

        return $out;
    }

    #[Test]
    public function media_path_helpers_are_per_site_and_disk_aware(): void
    {
        // The relative media path is keyed off the per-site userfiles folder, so
        // under multisite it carries the "<domain>" segment for free (local + S3).
        $rel = media_base_path_relative();
        $this->assertStringStartsWith(MW_USERFILES_FOLDER_NAME, $rel,
            'media_base_path_relative() must sit under the per-site userfiles folder.');
        $this->assertStringContainsString(MW_MEDIA_FOLDER_NAME, $rel);

        // The upload disk is the single userfiles disk (S3 when configured), not a
        // hardcoded local disk.
        $this->assertSame(userfiles_disk(), media_upload_disk(),
            'media_upload_disk() must defer to userfiles_disk() so S3 is honoured.');
    }

    #[Test]
    public function media_library_browser_upload_uses_the_per_site_helpers(): void
    {
        $src = $this->executableSource('Modules/MediaLibrary/Filament/Admin/Pages/MediaLibrary.php');

        $this->assertStringContainsString('media_base_path_relative', $src,
            'The media browser upload must resolve its directory via media_base_path_relative().');
        $this->assertStringContainsString('media_upload_disk', $src,
            'The media browser upload must store on media_upload_disk() (S3-aware).');
        $this->assertStringNotContainsString("store('userfiles/media', 'public')", $src,
            'The hardcoded shared "userfiles/media" + "public" upload must be gone (cross-site leak).');
    }

    #[Test]
    public function media_resource_file_upload_uses_the_per_site_helpers(): void
    {
        $src = $this->executableSource('Modules/Media/Filament/Resources/MediaResource.php');

        $this->assertStringContainsString('media_base_path_relative', $src,
            'The MediaResource FileUpload must target the per-site media tree.');
        $this->assertStringContainsString('media_upload_disk', $src,
            'The MediaResource FileUpload must use media_upload_disk() (S3-aware).');
        $this->assertStringNotContainsString("->directory('uploaded')", $src,
            'The hardcoded shared "uploaded" directory must be gone.');
    }
}
