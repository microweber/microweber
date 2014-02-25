<?php
/**
 * Class used to import and restore the database or the userfiles directory
 *
 * You can use it to create import of the site. The import will contain na sql export of the database
 * and also a zip file with userfiles directory.
 *
 *
 * @package utils
 */


namespace Microweber\Utils;


use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;


api_expose('Utils\Import\delete');
api_expose('Utils\Import\create');
api_expose('Utils\Import\download');
api_expose('Utils\Import\create_full');
api_expose('Utils\Import\move_uploaded_file_to_import');
api_expose('Utils\Import\restore');


class Import
{

    public $imports_folder = false;
    public $import_file = false;
    public $app;
    /**
     * The import class is used for making or restoring exported files from other CMS
     *
     * @category  mics
     * @package   utils
     */


    private $file_q_sep = '; /* MW_QUERY_SEPERATOR */';
    private $prefix_placeholder = '/* MW_PREFIX_PLACEHOLDER */';

    function __construct($app = null)
    {


        if (!defined('USER_IP')) {
            if (isset($_SERVER["REMOTE_ADDR"])) {
                define("USER_IP", $_SERVER["REMOTE_ADDR"]);
            } else {
                define("USER_IP", '127.0.0.1');

            }
        }
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw('application');
        }
    }

    public function get()
    {
        if (!is_admin()) {
            error("must be admin");
        }

        $here = $this->get_import_location();

        $files = glob("$here{*.sql,*.zip,*}", GLOB_BRACE);

        usort($files, function ($a, $b) {
            return filemtime($a) < filemtime($b);
        });

        $backups = array();
        if (!empty($files)) {
            foreach ($files as $file) {

                //if (strpos($file, '.sql', 1) or strpos($file, '.zip', 1)) {
                $mtime = filemtime($file);
                // Get time and date from filename
                $date = date("F d Y", $mtime);
                $time = date("H:i:s", $mtime);
                // Remove the sql extension part in the filename
                //	$filenameboth = str_replace('.sql', '', $file);
                $bak = array();
                $bak['filename'] = basename($file);
                $bak['date'] = $date;
                $bak['time'] = str_replace('_', ':', $time);
                ;
                $bak['size'] = filesize($file);

                $backups[] = $bak;
            }

            // }

        }

        return $backups;

    }

    function get_import_location()
    {

        if (defined('MW_CRON_EXEC')) {

        } else if (!is_admin()) {
            error("must be admin");
        }

        $loc = $this->imports_folder;

        if ($loc != false) {
            return $loc;
        }
        $here = MW_USERFILES . "import" . DS;

        if (!is_dir($here)) {
            mkdir_recursive($here);
            $hta = $here . '.htaccess';
            if (!is_file($hta)) {
                touch($hta);
                file_put_contents($hta, 'Deny from all');
            }
        }

        $here = MW_USERFILES . "import" . DS . MW_TABLE_PREFIX . DS;

        $here2 = mw('option')->get('import_location', 'admin/import');
        if ($here2 != false and is_string($here2) and trim($here2) != 'default' and trim($here2) != '') {
            $here2 = normalize_path($here2, true);

            if (!is_dir($here2)) {
                mkdir_recursive($here2);
            }

            if (is_dir($here2)) {
                $here = $here2;
            }
        }


        if (!is_dir($here)) {
            mkdir_recursive($here);
        }


        $loc = $here;


        $this->imports_folder = $loc;
        return $here;
    }

    function move_uploaded_file_to_import($params)
    {
        only_admin_access();

        if (!isset($params['src'])) {

            return array('error' => "You have not provided src to the file.");

        }

        $check = url2dir(trim($params['src']));
        $here = $this->get_import_location();
        if (is_file($check)) {
            $fn = basename($check);
            if (copy($check, $here . $fn)) {
                @unlink($check);
                return array('success' => "$fn was moved!");

            } else {
                return array('error' => "Error moving uploaded file!");

            }

        } else {
            return array('error' => "Uploaded file is not found!");

        }

    }

    function delete($params)
    {
        if (!is_admin()) {
            error("must be admin");
        }


        // Get the provided arg
        $id = $params['id'];

        // Check if the file has needed args
        if ($id == NULL) {

            return array('error' => "You have not provided filename to be deleted.");

        }

        $here = $this->get_bakup_location();
        $filename = $here . $id;


        $id = str_replace('..', '', $id);
        $filename = str_replace('..', '', $filename);

        if (is_file($filename)) {

            unlink($filename);
            return array('success' => "$id was deleted!");
        } else {

            $filename = $here . $id . '.sql';
            if (is_file($filename)) {
                unlink($filename);
                return array('success' => "$id was deleted!");
            }
        }

    }

    function get_bakup_location()
    {
        return $this->get_import_location();
    }

    function download($params)
    {
        if (!is_admin()) {
            error("must be admin");
        }

        ini_set('memory_limit', '512M');
        set_time_limit(0);

        if (isset($params['id'])) {
            $id = $params['id'];
        } else if (isset($_GET['filename'])) {
            $id = $params['filename'];
        } else if (isset($_GET['file'])) {
            $id = $params['file'];
        }
        $id = str_replace('..', '', $id);


        // Check if the file has needed args
        if ($id == NULL) {
            return array('error' => "You have not provided filename to download.");

            die();
        }

        $here = $this->get_bakup_location();
        // Generate filename and set error variables

        $filename = $here . $id;
        $filename = str_replace('..', '', $filename);
        if (!is_file($filename)) {
            return array('error' => "You have not provided a existising filename to download.");

            die();
        }
        // Check if the file exist.
        if (file_exists($filename)) {
            // Add headers
            $name = basename($filename);
            $type = 'sql';
            header('Cache-Control: public');
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . $name);
            header('Content-Length: ' . filesize($filename));
            // Read file
            $this->readfile_chunked($filename);
        } else {
            die('File does not exist');
        }
    }

    function readfile_chunked($filename, $retbytes = TRUE)
    {


        $filename = str_replace('..', '', $filename);

        $chunk_size = 1024 * 1024;
        $buffer = "";
        $cnt = 0;
        // $handle = fopen($filename, "rb");
        $handle = fopen($filename, "rb");
        if ($handle === false) {
            return false;
        }


        while (!feof($handle)) {
            $buffer = fread($handle, $chunk_size);
            echo $buffer;
            ob_flush();
            flush();
            if ($retbytes) {
                $cnt += strlen($buffer);
            }
        }
        $status = fclose($handle);
        if ($retbytes && $status) {
            return $cnt; // return num. bytes delivered like readfile() does.
        }
        return $status;
    }

    function restore($params)
    {
        only_admin_access();

        $id = null;
        if (isset($params['id'])) {
            $id = $params['id'];
        } else if (isset($_GET['filename'])) {
            $id = $params['filename'];
        } else if (isset($_GET['file'])) {
            $id = $params['file'];
        }

        if ($id == NULL) {

            return array('error' => "You have not provided a file to restore.");
            die();
        }
        $id = str_replace('..', '', $id);

        $here = $this->get_bakup_location();
        $filename = $here . $id;

        if (!is_file($filename)) {
            return array('error' => "You have not provided a existing backup to restore.");

        } else {
            return $this->import_file($filename);
        }


        return $params;
    }

    public function import_file($filename)
    {
        only_admin_access();

        if (!is_file($filename)) {
            return array('error' => "You have not provided a existing backup to restore.");
        }
        $ext = get_file_extension($filename);
        $import_method = strtolower('import_' . $ext);
        if (method_exists($this, $import_method)) {
            return $this->$import_method($filename);
        } else {
            return array('error' => "Cannot find method for importing $ext files.");

        }
    }

    public function import_csv($filename)
    {
        only_admin_access();
        if (!is_file($filename)) {
            return array('error' => "You have not provided a existing backup to restore.");
        }


        $csv = new \Keboola\Csv\CsvFile($filename);

        $head = $csv->getHeader();
        if (!isset($head[2])) {
            $csv = new \Keboola\Csv\CsvFile($filename, ';');
            $head = $csv->getHeader();
        } else if (isset($head[0]) and stristr($head[0],';')) {
            $csv = new \Keboola\Csv\CsvFile($filename, ';');
            $head = $csv->getHeader();
        }

        if (empty($head) or empty($csv)) {
            return array('error' => "CSV file cannot be parsed properly.");
        }
        $rows = array();
        $i = 0;
        foreach ($csv as $row) {
            if ($i > 0) {
                $r = array();
                if (is_array($row)) {
                    foreach ($row as $k => $v) {
                        if (isset($head[$k])) {
                            $row[$head[$k]] = $v;
                            $new_k = strtolower($head[$k]);
                            $new_k = str_replace(' ','_',$new_k);
                            $new_k = str_replace('__','_',$new_k);
                            $new_k = preg_replace("/[^a-zA-Z0-9_]+/", "", $new_k);
                            $new_k = rtrim($new_k,'_');
                            $r[$new_k] = $v;
                        }
                    }
                }
                $rows[] = $r;
            }
            $i++;
        }
        $content_items = $rows;
         $content_items = $this->map_array($rows);
        d($content_items);
        return $this->batch_save($content_items);

      //  d($content_items);
       // d($rows);
//        $head = array_pop($csv);
//        //  d($head);
//        foreach ($csv as $k => $row) {
//            d($row);
//            // d($k);
//            print '------------------------------------';
//        }

    }

    public function import_xml($filename)
    {
        only_admin_access();
        if (!is_file($filename)) {
            return array('error' => "You have not provided a existing backup to restore.");
        }


        $content = file_get_contents($filename);

        $here = __DIR__ . DIRECTORY_SEPARATOR;
        $parser = $here . 'SimplePie.php';
        if (!class_exists('\SimplePie')) {
            require_once($parser);
        }

        $feed = new \SimplePie();
        $feed->set_input_encoding('utf-8');
        $feed->set_raw_data($content);

        $feed->init();


        $feed->handle_content_type();

        $content_items = array();
        foreach ($feed->get_items() as $item) {
            $link = $item->get_permalink();
            if ($link != false) {
                $content = array();
                $content['data_import_link'] = $link;
                $content['created_on'] = $item->get_date();
                $upd = $item->get_updated_date();
                if ($upd != false) {
                    $content['updated_on'] = $item->get_updated_date();
                }
                $content['title'] = $item->get_title();
                $content['description'] = $item->get_description();
                $content['content'] = $item->get_content();

                $cats = $item->get_categories();
                //$item_tags = $item->get_item_tags();

                $media_group = $item->get_source();


                //  $cat = $item->get_category();
                if (!empty($cats)) {
                    foreach ($cats as $category) {
                        if (!isset($category->label)) {
                            // no category
                            if (isset($category->term)) {
                                if (stristr($category->term, 'kind#')) {
                                    if (!stristr($category->term, 'kind#post') and !stristr($category->term, 'kind#page')) {
                                        $content = false;
                                    }
                                }
                            }
                        }

                        if (is_array($content) and $category->get_label() != false) {
                            $content['categories'][] = $category->get_label();
                        }
                    }

                }
                if (is_array($content) and !empty($content)) {
                    $content_items[] = $content;
                }
            }
        }
        //d($content_items);
        return $this->batch_save($content_items);
    }

    function batch_save($content_items)
    {
        if (!empty($content_items)) {
            $parent = get_content('one=true&subtype=dynamic&is_deleted=n&is_active=y');
            if ($parent == false) {
                return array('error' => "No parent page found");
            }


            $content_items = $this->map_array($content_items);


            $parent_id = $parent['id'];
            $restored_items = array();
            foreach ($content_items as $content) {
                if (isset($content['title'])) {
                    $is_saved = get_content('one=true&title=' . $content['title']);


                    if (isset($content['description']) and (!isset($content['content']) or $content['content'] == false)) {
                        $content['content'] = $content['description'];
                    }


                    $content['parent'] = $parent_id;
                    $content['content_type'] = 'post';
                    $content['subtype'] = 'post';
                    $content['is_active'] = 'y';
                    //  $content['debug'] = 'y';
                    $content['download_remote_images'] = true;

                    if ($is_saved != false) {
                        $content['id'] = $is_saved['id'];
                        $content['content_type'] = $is_saved['content_type'];
                        $content['subtype'] = $is_saved['subtype'];
                    }

                    $import = save_content($content);
                    $restored_items[] = $import;
                }
            }
            cache_clear('categories');
            cache_clear('content');
            return array('success' => count($restored_items) . " items restored");


        }

    }

    function map_array($content_items)
    {
        $res = array();
        $map_keys = array();

        //title keys
        $map_keys['name'] = 'title';
        $map_keys['product_name'] = 'title';
        $map_keys['productname'] = 'title';


        //description keys
        $map_keys['introtext'] = 'description';
        $map_keys['short_description'] = 'description';

        //url keys
        $map_keys['url_rewritten'] = 'url';
        $map_keys['alias'] = 'url';


        //image keys
        $map_keys['image_urls_xyz'] = 'insert_content_image';
        $map_keys['picture_url'] = 'insert_content_image';


        //categories keys
        $map_keys['categories_xyz'] = 'categories';
        $map_keys['categorysubcategory'] = 'categories';




        //custom fields
        $map_keys['wholesale_price'] = 'custom_field_price';
        $map_keys['price'] = 'custom_field_price';

        //data fields
        $map_keys['manufacturer'] = 'data_manufacturer';
        $map_keys['supplier'] = 'data_supplier';
        $map_keys['ean13'] = 'data_ean13';
        $map_keys['weight'] = 'data_weight';
        $map_keys['quantity'] = 'data_qty';
        $map_keys['qty'] = 'data_qty';
        $map_keys['reference'] = 'data_reference';




        //meta fields
        $map_keys['meta_title'] = 'content_meta_title';
        $map_keys['meta_keywords'] = 'content_meta_keywords';
        $map_keys['meta_keyword'] = 'content_meta_keywords';
        $map_keys['meta_description'] = 'content_meta_description';

        //date fields
        $map_keys['product_creation_date'] = 'created_on';
        $map_keys['product_available_date'] = 'updated_on';
        $map_keys['created'] = 'created_on';
        $map_keys['modified'] = 'updated_on';


        foreach ($content_items as $item) {
            if(isset($item['id'])){
                unset($item['id']);
            }
            $new_item = array();
            foreach($map_keys as $map_key => $map_val){
                if((isset($item[$map_key]) and $item[$map_key] != false) and (!isset($item[$map_val]) or $item[$map_val] == false)){
                   $new_val = $item[$map_key];
                    if($map_key == 'categorysubcategory'){
                    $new_val = explode('/',$new_val);
                    }
                    $item[$map_val]=$new_val;
                    $new_item[$map_val]=$new_val;
                }

            }
            //$res[] = $new_item;
             $res[] = $item;

        }
        return $res;
    }


}

