<?php

namespace MicroweberPackages\Frontend\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;


class ServeStaticFileContoller extends Controller
{
    public $skip_ext = ['php', 'phtml', 'php7', '.htaccess', '.env'];
    public $inline_disposition = ['pdf', 'docx', 'doc', 'xls'];

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function serveFromUserfiles(Request $request)
    {
        $path = $request->path;


        $path = normalize_path(userfiles_path() . $path, false);

        return $this->sendResponse($path, $request);

    }

    private function sendResponse($path, $request)
    {
        $hasFile = false;

        if ($path and is_file($path)) {
            $hasFile = true;
        }

        if (!$hasFile) {
            return response('ServeStaticFileContoller: File not found', 404)
                ->withoutCookie(config('session.cookie'));
        }


        //$mime = File::mimeType($path);
        $ext = File::extension($path);
        $size = File::size($path);
        $mtime = filemtime($path);

        abort_if(in_array(strtolower($ext), $this->skip_ext), 403);

        $filesUtils = new \MicroweberPackages\Utils\System\Files();
        if ($filesUtils->is_allowed_file($path)) {
            abort(403, 'File not allowed');
        }


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
