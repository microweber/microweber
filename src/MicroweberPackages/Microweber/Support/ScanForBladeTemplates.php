<?php

namespace MicroweberPackages\Microweber\Support;

class ScanForBladeTemplates
{


    public function scan($templatesNamespace,$moduleType=false,$activeSiteTemplate=false, $activeSiteTemplateLowerName=false)
    {

        $viewsHints = app('view')->getFinder()->getHints();
        $templatesForModule = [];
        if ($templatesNamespace and !empty($templatesNamespace)) {
            //explode by ::
            $templatesNamespaceParts = explode('::', $templatesNamespace);
            $templatesNamespace = $templatesNamespaceParts[0];
            $templatesNamespaceSubfolder = $templatesNamespaceParts[1] ?? '';

            if ($templatesNamespaceSubfolder) {
                // replace . with DS
                $templatesNamespaceSubfolder = str_replace('.', DIRECTORY_SEPARATOR, $templatesNamespaceSubfolder);
            }

            if (isset($viewsHints[$templatesNamespace]) and is_array($viewsHints[$templatesNamespace]) and !empty($viewsHints[$templatesNamespace])) {
                $hints = $viewsHints[$templatesNamespace];
                foreach ($hints as $hint) {
                    $folder = $hint;
                    if ($templatesNamespaceSubfolder) {
                        $folder = $hint . '/' . $templatesNamespaceSubfolder;
                    }

                    $scanTemplatesResult = $this->scanFolder($folder, $templatesNamespace,$moduleType, $activeSiteTemplateLowerName);

                    if ($scanTemplatesResult) {
                        $templatesForModule = array_merge($templatesForModule, $scanTemplatesResult);
                    }




                }
            }
        }
        return $templatesForModule;
    }

    public function scanFolder($folder, $templatesNamespace,$moduleType=false, $activeSiteTemplateLowerName=false)
    {

        //legacy code from the old function, must be refactored
        $glob_patern = '*.blade.php';
        $folder = normalize_path($folder, false);
        $files = glob($folder . '/' . $glob_patern);

        if(!is_dir($folder)){
            return [];
        }


        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folder, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getFilename(), '.blade.php') !== false) {
                $files[] = $file->getPathname();
            }
        }

        if(!$files){
            return [];
        }

        //nat sort 01, 02, 03
        natsort($files);


        $files = array_unique($files);

        //   $files = array_merge($files, glob($folder . '/*/' . $glob_patern));
        $configs = array();
        foreach ($files as $filename) {
            if (is_array($filename)) {
                continue;
            }
            if (is_file($filename)) {

                $fin = file_get_contents($filename);
                $fin = preg_replace('/\r\n?/', "\n", $fin);

                $here_dir = dirname($filename) . DS;
                $to_return_temp = array();
                if (preg_match('/type:.+/', $fin, $regs)) {
                    $result = $regs[0];
                    $result = str_ireplace('type:', '', $result);
                    $to_return_temp['type'] = trim($result);

                }

                $to_return_temp['directory'] = $here_dir;


                //  if (strtolower($to_return_temp['type']) == 'layout') {
                $to_return_temp['directory'] = $here_dir;

                if (preg_match('/is_shop:.+/', $fin, $regs)) {
                    $result = $regs[0];
                    $result = str_ireplace('is_shop:', '', $result);
                    $to_return_temp['is_shop'] = trim($result);
                }

                if (preg_match('/hidden:.+/', $fin, $regs)) {
                    $result = $regs[0];
                    $result = str_ireplace('hidden:', '', $result);
                    $to_return_temp['hidden'] = trim($result);
                }

                if (preg_match('/name:.+/', $fin, $regs)) {
                    $result = $regs[0];
                    $result = str_ireplace('name:', '', $result);
                    $to_return_temp['name'] = trim($result);
                }

                $to_return_temp['category'] = 'All';
                if (preg_match('/category:.+/', $fin, $regs)) {
                    $result = $regs[0];
                    $result = str_ireplace('category:', '', $result);
                    $result = trim($result);
                    $to_return_temp['category'] = $result;
                }

                if (preg_match('/is_default:.+/', $fin, $regs)) {
                    $result = $regs[0];
                    $result = str_ireplace('is_default:', '', $result);
                    $to_return_temp['is_default'] = trim($result);
                }
                if (preg_match('/categories:.+/', $fin, $regs)) {
                    $result = $regs[0];
                    $result = str_ireplace('categories:', '', $result);
                    $to_return_temp['categories'] = trim($result);
                    $to_return_temp['category'] = explode(',', $to_return_temp['categories'])[0];
                }

                if (preg_match('/position:.+/', $fin, $regs)) {
                    $result = $regs[0];
                    $result = str_ireplace('position:', '', $result);
                    $to_return_temp['position'] = intval($result);
                } else {
                    $to_return_temp['position'] = 99999;
                }

                if (preg_match('/version:.+/', $fin, $regs)) {
                    $result = $regs[0];
                    $result = str_ireplace('version:', '', $result);
                    $to_return_temp['version'] = trim($result);
                }
                if (preg_match('/visible:.+/', $fin, $regs)) {
                    $result = $regs[0];
                    $result = str_ireplace('visible:', '', $result);
                    $to_return_temp['visible'] = trim($result);
                }

                if (preg_match('/icon:.+/', $fin, $regs)) {
                    $result = $regs[0];
                    $result = str_ireplace('icon:', '', $result);
                    $to_return_temp['icon'] = trim($result);

                    $possible = $here_dir . $to_return_temp['icon'];
                    if (is_file($possible)) {
                        // $to_return_temp['icon'] = $this->app->url_manager->link_to_file($possible);
                    } else {
                        unset($to_return_temp['icon']);
                    }
                }

                if (preg_match('/image:.+/', $fin, $regs)) {
                    $result = $regs[0];
                    $result = str_ireplace('image:', '', $result);
                    $to_return_temp['image'] = trim($result);
                    $possible = $here_dir . $to_return_temp['image'];

                    if (is_file($possible)) {
                        //  $to_return_temp['image'] = $this->app->url_manager->link_to_file($possible);
                    } else {
                        unset($to_return_temp['image']);
                    }
                }

                if (preg_match('/description:.+/', $fin, $regs)) {
                    $result = $regs[0];
                    $result = str_ireplace('description:', '', $result);
                    $to_return_temp['description'] = trim($result);
                }

                if (preg_match('/content_type:.+/', $fin, $regs)) {
                    $result = $regs[0];
                    $result = str_ireplace('content_type:', '', $result);
                    $to_return_temp['content_type'] = trim($result);
                }

                if (preg_match('/tag:.+/', $fin, $regs)) {
                    $result = $regs[0];
                    $result = str_ireplace('tag:', '', $result);
                    $to_return_temp['tag'] = trim($result);
                }
                $layout_file = $filename;

                if (isset($template_dirs) and !empty($template_dirs)) {
                    foreach ($template_dirs as $template_dir) {
                        $layout_file = str_replace($template_dir, '', $layout_file);
                    }
                }

//                    if (isset($options['content_type']) && $options['content_type'] == 'post') {
//                        if (isset($to_return_temp['content_type']) && $to_return_temp['content_type'] !== 'post') {
//                            continue;
//                        }
//                    } else {
//                        if (isset($to_return_temp['content_type']) && $to_return_temp['content_type'] == 'post') {
//                            continue;
//                        }
//                    }

                $layout_file = str_replace(DS, '/', $layout_file);
                $layout_file_preview = str_replace('/', '__', $layout_file);

                $skipLayoutFiles = [
                    '404.php',
                    'forgot_password.php',
                    'login.php',
                    'register.php',
                    'reset_password.php',
                    'layouts/sign-up.php',
                ];


                if (in_array($layout_file, $skipLayoutFiles)) {
                    continue;
                }
//
//                        if(!isset($the_active_site_template)){
//                            $the_active_site_template = $this->app->option_manager->get('current_template', 'template');
//                        }
                //  $layout_file_basename = basename($layout_file);

                $folder_normalized = normalize_path($folder, true);
                $filename_normalized = normalize_path($filename, false);

                $layout_file_basename = str_replace($folder_normalized, '', $filename_normalized);
                //   $layout_file_basename = basename( $layout_file_basename);
                $layout_file_basename = str_replace('\\', '.', $layout_file_basename);
                $layout_file_basename = str_replace('//', '.', $layout_file_basename);

                $view_name = str_replace('.blade.php', '', $layout_file_basename);

                $to_return_temp['layout_file'] = $view_name;

                if (!isset($to_return_temp['name'])) {
                    $to_return_temp['name'] = ucfirst($view_name);
                }

                $to_return_temp['filename'] = $filename;
                //$to_return_temp['layout_file'] = $layout_file;
                $screen = str_ireplace('.blade.php', '.png', $filename);
                $screen_jpg = str_ireplace('.blade.php', '.png', $filename);

                $screen2 = str_ireplace('.php', '.png', $filename);
                $screen_jpg2 = str_ireplace('.php', '.png', $filename);
                $skin_settings_json = str_ireplace('.blade.php', '.json', $filename);
                $skin_settings_json = str_ireplace('.php', '.json', $skin_settings_json);

                $screenshotType = 'modules';
                if (isset($to_return_temp['type']) && $to_return_temp['type'] == 'layout') {
                   $screenshotType = 'layouts';
                }

                if($moduleType){
                    $screenshotType = $moduleType;
                }



                $img_name = $to_return_temp['layout_file'].'.png';
                $img_name = str_replace('/', '.', $img_name);
                $img_name = str_replace('\\', '.', $img_name);
                $img_path_modules =  $img_path = 'modules/'. $screenshotType.'/templates/'.$img_name;

                if($activeSiteTemplateLowerName){
                    $img_path = 'templates/'.$activeSiteTemplateLowerName.'/img/screenshots/modules/'. $screenshotType.'/templates/'. $img_name;
                }


                $img_path_for_update_screenshot =$to_return_temp['directory'] . $img_name;
                if($activeSiteTemplateLowerName){
                    $checkIfActiveSiteTemplate = app()->templates->find($activeSiteTemplateLowerName);

                    $checkIfActiveSiteTemplatePath = $checkIfActiveSiteTemplate->get('path');

                    $img_path_for_update_screenshot =$checkIfActiveSiteTemplatePath. '/resources/assets/img/screenshots/' . $img_path_modules;

                    //  app()->laravel_templates->setActiveTemplate($activeSiteTemplateLowerName);
                }

                $img_path_for_update_screenshot = str_replace(DS, '/', $img_path_for_update_screenshot);
                $img_path_for_update_screenshot = str_replace('//', '/', $img_path_for_update_screenshot);
                $img_path_for_update_screenshot = str_replace('resources/views/', 'resources/assets/img/screenshots/', $img_path_for_update_screenshot);

                $path =$img_path;
                 $screenshotPublic = asset($path);
                 $screen2 = public_path($path);

                $to_return_temp['screenshot_public_url'] = $screenshotPublic;
                $to_return_temp['screenshot_path_lookup'] = $screen2;
                $to_return_temp['screenshot_path_lookup_public'] = $screen2;
                $to_return_temp['screenshot_path_for_update_screenshot'] =  $img_path_for_update_screenshot;


                if (is_file($skin_settings_json)) {

                    $to_return_temp['skin_settings_json_file'] = $skin_settings_json;
                }

                if (is_file($screen2)) {
                    $to_return_temp['screenshot_file'] = $screen2;
                }

              /*  elseif (is_file($screen_jpg2)) {
                    $to_return_temp['screenshot_file'] = $screen_jpg2;
                } elseif (is_file($screen_jpg)) {
                    $to_return_temp['screenshot_file'] = $screen_jpg;
                } elseif (is_file($screen)) {
                    $to_return_temp['screenshot_file'] = $screen;
                }*/

                if(isset($to_return_temp['screenshot_file'])){
                    $to_return_temp['screenshot_file'] = normalize_path($to_return_temp['screenshot_file'], false);
                }

//                        if (isset($to_return_temp['screenshot_file'])) {
//                            $to_return_temp['screenshot'] = $this->app->url_manager->link_to_file($to_return_temp['screenshot_file']);
//                        }

                $configs[] = $to_return_temp;
            }
        }


        //    }

        return $configs;
    }
}
