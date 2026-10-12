<?php

namespace MicroweberPackages\Livewire\Http\Controllers;

use Illuminate\Support\Facades\App;

trait CanPretendToBeAFileButNotInCliTrait
{
    public function pretendResponseIsFile(string $file, string $mimeType = 'application/javascript'): \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
    {

        $expires = strtotime('+1 year');
        $lastModified = filemtime($file);
        $cacheControl = 'public, max-age=31536000';
        if (!App::runningUnitTests()) {
            if (!is_cli()) {
                if ($this->matchesCache($lastModified)) {
                    return response()->make('', 304, [
                        'Expires' => $this->httpDate($expires),
                        'Cache-Control' => $cacheControl,
                    ]);
                }
            }
        }
        return response()->file($file, [
            'Content-Type' => "$mimeType; charset=utf-8",
            'Expires' => $this->httpDate($expires),
            'Cache-Control' => $cacheControl,
            'Last-Modified' => $this->httpDate($lastModified),
        ]);
    }

    protected function matchesCache(int|false $lastModified): bool
    {
        $ifModifiedSince = $_SERVER['HTTP_IF_MODIFIED_SINCE'] ?? '';

        return @strtotime($ifModifiedSince) === $lastModified;
    }

    /**
     * @param int|false $timestamp filemtime() or strtotime() result
     */
    protected function httpDate(int|false $timestamp): string
    {
        return sprintf('%s GMT', gmdate('D, d M Y H:i:s', $timestamp));
    }
}
