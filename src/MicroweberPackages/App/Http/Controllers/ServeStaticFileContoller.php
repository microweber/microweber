<?php

namespace MicroweberPackages\App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;


class ServeStaticFileContoller extends Controller
{
    public $skip_ext = ['php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'php8', 'phps', 'pht', 'phar', 'inc'];
    public $inline_disposition = ['pdf', 'docx', 'doc', 'xls'];

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function serveFromUserfiles(Request $request)
    {
        // Use the bound route parameter only. $request->path prefers a "path" value from the query
        // string or body over the route parameter, which allowed reading any file (CVE-2026-65694).
        $path = $this->resolveUserfilesPath($request->route('path'));

        return $this->sendResponse($path, $request);

    }

    /**
     * Resolves a path relative to the userfiles folder. Returns null unless it resolves to a file
     * inside the userfiles folder and contains no hidden files/folders ("..", ".env", ".htaccess").
     */
    private function resolveUserfilesPath($relative_path)
    {
        if (!is_string($relative_path) or $relative_path === '') {
            return null;
        }

        // Null bytes, and ':' for Windows drive letters and NTFS streams (file.php::$DATA)
        if (str_contains($relative_path, "\0") or str_contains($relative_path, ':')) {
            return null;
        }

        foreach (preg_split('#[\\\\/]+#', $relative_path) as $segment) {
            if ($segment !== '' and $segment[0] === '.') {
                return null;
            }
        }

        $base = realpath(userfiles_path());
        $path = realpath(userfiles_path() . $relative_path);

        if ($base === false or $path === false) {
            return null;
        }

        $base = rtrim($base, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if (!str_starts_with($path, $base)) {
            return null;
        }

        return $path;
    }

    private function sendResponse($path, $request)
    {


        abort_if(is_null($path), 404);
        abort_if(!is_file($path), 404);


        //$mime = File::mimeType($path);
        $ext = File::extension($path);
        $size = File::size($path);
        $mtime = filemtime($path);

        abort_if(in_array(strtolower($ext), $this->skip_ext), 403);


        $mimetype = \GuzzleHttp\Psr7\MimeType::fromExtension($ext);

        $headers = [
            'Content-Type' => $mimetype,
            'Content-Length' => $size,
        ];

        $server = $request->server;

        if ($server and $server->has('HTTP_IF_MODIFIED_SINCE') &&
            strtotime($server->get('HTTP_IF_MODIFIED_SINCE')) >= $mtime) {
            return response(null, 304);
        }

        $headerEtag = md5($path . $mtime . $size);

        if ($server and $server->has('HTTP_IF_NONE_MATCH') &&
            $server->get('HTTP_IF_NONE_MATCH') === $headerEtag) {
            return response(null, 304);
        }
/*
        $target = normalize_path(public_path().DS.userfiles_folder_name().DS.$request->path, false);
        $target_dir = dirname($target);
        mkdir_recursive($target_dir);

      //  dd($target);
        $copy = File::copy($path,$target );*/

        return response(
            file_get_contents($path),
            200,
            $headers
        )->setLastModified(DateTime::createFromFormat('U', $mtime))->setEtag($headerEtag);


    }
}