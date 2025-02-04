<?php
namespace MicroweberPackages\FileManager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use MicroweberPackages\App\Http\Controllers\Controller;
use MicroweberPackages\Helper\HTMLClean;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class FileManagerApiController extends Controller {

    public function list(Request $request) {

        $path = '';///media_uploads_path();
        $pathRestirct = '';//media_uploads_path();

        if (!empty($request->get('path'))) {
            $path = $request->get('path');
        }

        $keyword = false;
        if (!empty($request->get('keyword'))) {
            $keyword = $request->get('keyword');
        }

        // parameter for filetypes , ex ?filetypes=images
        $filetypes = false;
        $areAllowedExtensions = false;
        if (!empty($request->get('filetypes'))) {
            $filetypes = $request->get('filetypes');
            $files_utils = new \MicroweberPackages\Utils\System\Files();
            $areAllowedExtensions = $files_utils->get_allowed_files_extensions_for_upload($filetypes);
         }

        $limit = intval($request->get('limit', false));
        $order = $request->get('order', 'asc');
        $orderBy = $request->get('orderBy', 'filemtime');
        $path = urldecode($path);

        $path = sanitize_path($path);
        $path = str_replace($pathRestirct, '', $path);

        $thumbnailSize = 150;
        if (!empty($request->get('thumbnailSize'))) {
            $thumbnailSize = (int) $request->get('thumbnailSize');
        }

        $fileFilter = [];
        $fileFilter['directory'] = $pathRestirct . $path;
        $fileFilter['restrict_path'] = $pathRestirct;
        $fileFilter['hide_files'] = ['index.html','index.php'];

        if (!empty($keyword)) {
            $fileFilter['search'] = $keyword;
        }

        if (!empty($areAllowedExtensions)) {
            $fileFilter['extensions'] = $areAllowedExtensions;
        }

        $fileFilter['sort_order'] = $order;
        $fileFilter['sort_by'] = $orderBy;


        $data = [];
//        $getData = app()->make(\MicroweberPackages\Utils\System\Files::class)->get($fileFilter);

        $getData = [];
        $storageFiles = Storage::files($fileFilter['directory']);
        if (!empty($storageFiles)) {
            $getData['files'] = $storageFiles;
        }
        $storageDirectories = Storage::directories($fileFilter['directory']);
        if (!empty($storageDirectories)) {
            $getData['dirs'] = $storageDirectories;
        }

        $paginationOutput = [];
        if ($limit > 0) {
            if (isset($getData['files']) && !empty($getData['files'])) {
                $paginatedData = $this->paginateArray($getData['files'], $limit);
                $paginationOutput = [
                    'limit'=>$limit,
                    'total' => $paginatedData->total(),
                    'count' => $paginatedData->count(),
                    'perPage' => $paginatedData->perPage(),
                    'currentPage' => $paginatedData->currentPage(),
                    'totalPages' => $paginatedData->lastPage()
                ];

                $getData['files'] = $paginatedData->items();
            }
        }

        // Append dirs
        $appendDirs = true;
        if (!empty($request->get('page')) && $request->get('page') > 1) {
            $appendDirs = false;
        }

        if ($appendDirs) {
            if (isset($getData['dirs']) && is_array($getData['dirs'])) {
                foreach ($getData['dirs'] as $dir) {

//                    Storage::deleteDirectory($dir);

                    $relativeDir = str_ireplace(media_base_path(), '', $dir);

//                    if(!is_dir($dir)){
//                        continue;
//                    }
                    $lastModified =  date('Y-m-d H:i:s');//Storage::lastModified($dir);
                    $data[] = [
                        'type' => 'folder',
                        'mimeType' => Storage::mimeType($dir),
                        'name' => basename(Storage::path($dir)),
                        'path' => $relativeDir,
                        'created' => $lastModified,
                        'modified' => $lastModified
                    ];
                }
            }
        }

        // Append files
        if (isset($getData['files']) && is_array($getData['files'])) {
            foreach ($getData['files'] as $file) {

                $thumbnail = false;
                if(is_file(!$file)){
                    continue;
                }

//                $ext = strtolower(get_file_extension($file));
//                if ($ext == 'jpg' or $ext == 'png' or $ext == 'gif' or $ext == 'jpeg' or $ext == 'bmp' or $ext == 'webp' or $ext == 'svg') {
//                    $thumbnail = thumbnail(mw()->url_manager->link_to_file($file), $thumbnailSize, $thumbnailSize, false);
//                }

                $thumbnail = Storage::url($file);

                $relative_path = str_ireplace(media_base_path(), '', $file);

                $created = date('Y-m-d H:i:s', Storage::lastModified($file));
                $lastModified = date('Y-m-d H:i:s', Storage::lastModified($file));

                $data[] = [
                    'type'=>'file',
                    'mimeType'=> Storage::mimeType($file),
                    'name'=> basename($file),
                    'path'=> $relative_path,
                    'created'=> $created,
                    'modified'=> $lastModified,
                    'thumbnail'=> $thumbnail,
                    'url'=> Storage::url($file),
                    'size'=> Storage::size($file)
                ];
            }
        }

        if (empty($paginationOutput)) {
            $paginationOutput = [
                'total'=>count($data),
            ];
        }

        return [
            'data'=>$data,
            'query'=>[
                'order'=>$order,
                'orderBy'=>$orderBy,
                'keyword'=>$keyword,
                'path'=>$path
            ],
            'permissions'=>[
                'edit'=>true,
                'create'=>true,
                'delete'=>true,
            ],
            'pagination'=>$paginationOutput
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public function paginateArray($items, $perPage = 50, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

  /*  public function rename(Request $request)
    {
        $path = $request->get('path', false);
        $newPath = $request->get('newPath', false);

        $path = trim($path);
        $newPath = trim($newPath);

        if (empty($path)) {
            return array('error' => 'Please set file path');
        }

        if (empty($newPath)) {
            return array('error' => 'Please set new file path');
        }

    }*/

    public function delete(Request $request)
    {
        $deletePaths = $request->post('paths', false);

        if (empty($deletePaths)) {
            return array('error' => 'Please set file paths for delete.');
        }

        $resp = [];

        if (!empty($deletePaths) && is_array($deletePaths)) {

            $pathRestirct = media_base_path();

            foreach ($deletePaths as $deletePath) {

                $deletePath = trim($deletePath);
                $fnRemove = app()->url_manager->to_path($deletePath);

                if (isset($fnRemove) and trim($fnRemove) != '' and trim($fnRemove) != 'false') {

                    $path = urldecode($fnRemove);
                    $path = normalize_path($path, 0);
                    $path = sanitize_path($path);
                    $path = str_replace($pathRestirct, '', $path);

                    $targetPath = media_base_path() . DS . $path;
                    $targetPath = normalize_path($targetPath, false);

                    if (stristr($targetPath, media_uploads_path())) {
                        if (is_dir($targetPath)) {
                            mw('MicroweberPackages\Utils\System\Files')->rmdir($targetPath, false);
                            $resp = array('success' => 'Directory ' . basename($targetPath) . ' is deleted');
                        } elseif (is_file($targetPath)) {
                            unlink($targetPath);
                            $resp = array('success' => 'File ' . basename($targetPath) . ' is deleted');
                        } else {
                            $resp = array('error' => 'Not valid file or folder ' . basename($targetPath) . ' ');
                        }
                    } else {
                        $resp = array('error' => 'Not allowed to delete on ' . basename($targetPath) . ' ');
                    }
                }
            }
        }

        return $resp;
    }

    public function createFolder(Request $request)
    {
        $folderName = $request->post('name', false);
        $folderPath = $request->post('path', false);

        $clean = new HTMLClean();
        $folderName = $clean->clean($folderName);
        $folderPath = $clean->clean($folderPath);

       // $targetPath = media_uploads_path();
        $targetPath = '';


        if (trim($folderPath) != '') {

            $folderPath = urldecode($folderPath);
            $folderPath = $this->pathAutoCleanString($folderPath);

            if (Str::length($folderPath) > 500) {
                return array('error' => 'Folder path is too long.');
            }

            $fnPath = $targetPath . DS . $folderPath . DS;
            $fnPath = sanitize_path($fnPath);
            $fnPath = normalize_path($fnPath, false);

            $targetPath = $fnPath;
        }
        if (empty($folderName)) {
            $resp = array('error' => 'You must send folder name parameter.');
        } else {
            $fnNewFolderPath = $folderName;
            $fnNewFolderPath = urldecode($fnNewFolderPath);

            $fnNewFolderPath = $this->pathAutoCleanString($fnNewFolderPath);

            if (Str::length($fnNewFolderPath) > 500) {
                return array('error' => 'Folder path is too long.');
            }

            $fnNewFolderPath = sanitize_path($fnNewFolderPath);
            $fnNewFolderPath_new = $targetPath . DS . $fnNewFolderPath;
            $fnPath = normalize_path($fnNewFolderPath_new, false);

            if (!Storage::directoryExists($fnPath)) {
                Storage::createDirectory($fnPath);
                $resp = array('success' => 'Folder ' . $fnPath . ' is created');
            } else {
                $resp = array('error' => 'Folder ' . $fnNewFolderPath . ' already exists');
            }
        }

        return $resp;
    }

    private function pathAutoCleanString($string)
    {
        // THIS FUNCTION IS BROKEN WHEN YOU HAVE MORE THAN 3 LEVELS OF FOLDERS
        // TODO
        return $string;

        $url = $string;
        $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url); // substitutes anything but letters, numbers and '_' with separator
        $url = trim($url, "-");

        if (function_exists('iconv')) {
            $url = iconv("utf-8", "us-ascii//TRANSLIT", $url); // TRANSLIT does the whole job
        }

        $url = strtolower($url);
        $url = preg_replace('~[^-a-z0-9_]+~', '', $url); // keep only letters, numbers, '_' and separator

        return $url;
    }
}
