<?php

namespace MicroweberPackages\Microweber\Support;

class ScanForBladeTemplates
{

    public int $depth = 0;


    public function setScanDepth($depth)
    {
        $this->depth = $depth;
    }

    public function scan($templatesNamespace)
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

                    $scanTemplatesResult = $this->scanFolder($folder);

                    if ($scanTemplatesResult) {
                        $templatesForModule = array_merge($templatesForModule, $scanTemplatesResult);
                    }

                }
            }
        }
        return $templatesForModule;
    }

    public function scanFolder($folder)
    {

        //legacy code from the old function, must be refactored
        $glob_patern = '*.blade.php';
        $folder = normalize_path($folder, false);
        $files = glob($folder . '/' . $glob_patern);

        if($this->depth > 0){
            $this->depth--;
            $subfolders = glob($folder . '/*', GLOB_ONLYDIR);
            foreach($subfolders as $subfolder){
                $subfolderFiles = array_merge($files, glob($subfolder . '/' . $glob_patern));
                $files = array_merge($files, $subfolderFiles);
            }
        }

        //   $files = array_merge($files, glob($folder . '/*/' . $glob_patern));
        $configs = array();
        foreach ($files as $filename) {
            if(is_array($filename)) {
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

                if(!isset($to_return_temp['name'])){
                    $to_return_temp['name'] = ucfirst($view_name);
                }

                $to_return_temp['filename'] = $filename;
                //$to_return_temp['layout_file'] = $layout_file;
                $screen = str_ireplace('.php', '.png', $filename);
                $screen_jpg = str_ireplace('.php', '.jpg', $filename);
                $skin_settings_json = str_ireplace('.blade.php', '.json', $filename);
                $skin_settings_json = str_ireplace('.php', '.json', $skin_settings_json);

                if (is_file($skin_settings_json)) {

                    $to_return_temp['skin_settings_json_file'] = $skin_settings_json;
                }


//                        if (is_file($screen_jpg)) {
//                            $to_return_temp['screenshot_file'] = $screen_jpg;
//                        } elseif (is_file($screen)) {
//                            $to_return_temp['screenshot_file'] = $screen;
//                        }
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
