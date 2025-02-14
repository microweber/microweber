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

    public $onDisk = 'media';

    public function list(Request $request) {

        if (!empty($request->get('path'))) {
            $path = $request->get('path');
        }

        $keyword = false;
        if (!empty($request->get('keyword'))) {
            $keyword = $request->get('keyword');
        }

        $limit = intval($request->get('limit', false));
        $order = $request->get('order', 'asc');
        $orderBy = $request->get('orderBy', 'filemtime');
        $path = urldecode($path);
        $path = sanitize_path($path);

        $storageInstance = Storage::disk($this->onDisk);

        $data = [];
        $getData = [];

        $storageFilesInDirectory = $storageInstance->files($path);

        $fileDetails = collect($storageFilesInDirectory)->map(function ($file) use($storageInstance) {
            return [
                'name' => basename($file),
                'path' => $file,
                'size' => $storageInstance->size($file),
                'filemtime' => $storageInstance->lastModified($file),
                'url' => $storageInstance->url($file),
                'mimeType' => $storageInstance->mimeType($file),
            ];
        });
        $sortedFiles = $fileDetails->sortBy($order, SORT_REGULAR, $order === 'desc');
        $storageFiles = $sortedFiles->values()->all();

        if (!empty($storageFiles)) {
            $getData['files'] = $storageFiles;
        }
        $storageDirectories = $storageInstance->directories($path);
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
                        'mimeType' => $storageInstance->mimeType($dir),
                        'name' => basename($storageInstance->path($dir)),
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
                if(is_file(!$file['path'])){
                    continue;
                }

//                $ext = strtolower(get_file_extension($file));
//                if ($ext == 'jpg' or $ext == 'png' or $ext == 'gif' or $ext == 'jpeg' or $ext == 'bmp' or $ext == 'webp' or $ext == 'svg') {
//                    $thumbnail = thumbnail(mw()->url_manager->link_to_file($file), $thumbnailSize, $thumbnailSize, false);
//                }

                $thumbnail = $file['url'];

                $created = date('Y-m-d H:i:s', $file['filemtime']);
                $lastModified = date('Y-m-d H:i:s', $file['filemtime']);

                $data[] = [
                    'type'=>'file',
                    'mimeType'=> $file['mimeType'],
                    'name'=> $file['name'],
                    'path'=> $file['path'],
                    'created'=> $created,
                    'modified'=> $lastModified,
                    'thumbnail'=> $thumbnail,
                    'url'=> $file['url'],
                    'size'=> $file['size']
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

  public function rename(Request $request)
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

        $storageInstance = Storage::disk($this->onDisk);

        $fileType = $storageInstance->mimeType($path);
        if ($fileType) {
            if ($storageInstance->fileExists($newPath)) {
                return array('error' => 'File Exists');
            }
        } else {
            if ($storageInstance->directoryExists($newPath)) {
                return array('error' => 'Directory Exists');
            }
        }

        $storageInstance->move($path, $newPath);


        return array('success'=>'Renamed');
    }

    public function delete(Request $request)
    {
        $deletePaths = $request->post('paths', false);

        if (empty($deletePaths)) {
            return array('error' => 'Please set file paths for delete.');
        }

        $resp = [];

        $storageInstance = Storage::disk($this->onDisk);

        if (!empty($deletePaths) && is_array($deletePaths)) {

            $pathRestirct = '';//media_base_path();

            foreach ($deletePaths as $deletePath) {

                $deletePath = trim($deletePath);
                $fnRemove = app()->url_manager->to_path($deletePath);

                if (isset($fnRemove) and trim($fnRemove) != '' and trim($fnRemove) != 'false') {

                    $path = urldecode($fnRemove);
                    $path = normalize_path($path, 0);
                    $path = sanitize_path($path);
                    $path = str_replace($pathRestirct, '', $path);

//                    $targetPath = media_base_path() . DS . $path;
                    $targetPath = '' . DS . $path;
                    $targetPath = normalize_path($targetPath, false);

                    $isDir = $storageInstance->directoryExists($targetPath);
                    if ($isDir) {
                        $storageInstance->deleteDirectory($targetPath);
                        $resp = array('success' => 'Directory ' . basename($targetPath) . ' is deleted');
                    } else {
                        $storageInstance->delete($targetPath);
                        $resp = array('success' => 'File ' . basename($targetPath) . ' is deleted');
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

        $storageInstance = Storage::disk($this->onDisk);


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

            if (!$storageInstance->directoryExists($fnPath)) {
                $storageInstance->createDirectory($fnPath);
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
