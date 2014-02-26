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
                if (is_file($file)) { //if (strpos($file, '.sql', 1) or strpos($file, '.zip', 1)) {
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

                    $bak['size'] = filesize($file);

                    $backups[] = $bak;
                }

            }

            // }

        }

        return $backups;

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
        } else if (isset($head[0]) and stristr($head[0], ';')) {
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
                            $new_k = str_replace(' ', '_', $new_k);
                            $new_k = str_replace('__', '_', $new_k);
                            $new_k = preg_replace("/[^a-zA-Z0-9_]+/", "", $new_k);
                            $new_k = rtrim($new_k, '_');
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
        return $this->batch_save($content_items);


    }

    public function import_xml($filename)
    {
        only_admin_access();
        if (!is_file($filename)) {
            return array('error' => "You have not provided a existing backup to restore.");
        }
        $content_items = array();

        $content_feed = file_get_contents($filename);

        $here = MW_APP_PATH . 'Utils' . DIRECTORY_SEPARATOR;
        $parser = $here . 'SimplePie.php';
        require_once($parser);
        $parser2 = MW_APP_PATH . 'libs/QueryPath/QueryPath.php';
        require_once($parser2);
        $parser2 = MW_APP_PATH . 'libs/QueryPath/qp.php';

        require_once($parser2);


        $feed = new \SimplePie();
        $feed->set_input_encoding('utf-8');
        $feed->set_raw_data($content_feed);

        $feed->init();
        $feed->handle_content_type();

        $items = $feed->get_items();
        if (!empty($items)) {
            foreach ($items as $item) {
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

                    $post_type = $item->get_item_tags('http://wordpress.org/export/1.2/', 'post_type');
                    if (isset($post_type[0]) and isset($post_type[0]['data'])) {
                        $post_type = $post_type[0]['data'];
                        $content['content_type'] = $post_type;
                        $content['subtype'] = $post_type;


                    }

                    $cats = $item->get_categories();
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
        } else {
            libxml_use_internal_errors(true);
            $cont = array();
            $items = qp($content_feed, 'channel>item');
            foreach ($items as $item) {
                $content_item = array();
                //  print $item->text();
                $el = qp($item, 'channel>item>title');
                $content_item['title'] = $el->text();

                $el = qp($item, 'channel>item>encoded');
                $content_item['content'] = $el->text();

                $el = qp($item, 'channel>item>description');
                $content_item['description'] = $el->text();

                $el = qp($item, 'channel>item>post_type');
                $content_item['post_type'] = $el->text();

                $cats = qp($item, 'channel>item>category');
                foreach ($cats as $cat) {
                    $content_item['categories'][] = $cat->text();
                    //print ;
                }


                //print $title->text();
                $content_items[] = $content_item;

            }


        }


        //

        return $this->batch_save($content_items);
    }

    function batch_save($content_items)
    {
        $chunk_size = 25;

        if (count($content_items) < $chunk_size) {
            return $this->batch_process($content_items);
        }


        $chunks_folder = $this->get_import_location() . '_process_import' . DS;
        $index_file = $chunks_folder . 'index.php';

        if (!is_dir($chunks_folder)) {
            mkdir_recursive($chunks_folder);
            @touch($index_file);
        }

        if (!is_writable($chunks_folder)) {
            return array('error' => "Import folder is not writable!");

        }


        $chunks = (array_chunk($content_items, $chunk_size, true));

        if (!empty($chunks)) {
            foreach ($chunks as $chunk) {
                $chunk_data = serialize($chunk);
                $file_name = 'import_chunk_' . md5($chunk_data);
                $file_location = $chunks_folder . $file_name;
                if (!is_file($file_location)) {
                    file_put_contents($file_location, $chunk_data);
                }

            }
        }

        $i = 0;
        $dir = $chunks_folder;
        if ($handle = opendir($dir)) {
            while (($file = readdir($handle)) !== false) {
                if (!in_array($file, array('.', '..')) && !is_dir($dir . $file))
                    $i++;
            }
        }
        file_put_contents($index_file, $i);


        return array('success' => count($content_items) . " items are scheduled for import");
        return;

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

    function batch_process($content_items = false)
    {

        $chunks_folder = $this->get_import_location() . '_process_import' . DS;
        $index_file = $chunks_folder . 'index.php';


        $total = 0;
        $remaining = 0;
        $batch_file = false;
        if (!is_array($content_items) or empty($content_items)) {
            if (is_file($index_file)) {
                $total = file_get_contents($index_file);
            }
            $i = 0;
            $dir = $chunks_folder;
            if ($handle = opendir($dir)) {
                while (($file = readdir($handle)) !== false) {
                    if (!in_array($file, array('.', '..')) && !is_dir($dir . $file) and strstr($file, 'import_chunk_')) {
                        if (!is_array($content_items)) {
                            $batch_file = $chunks_folder . $file;
                            $batch_file_content = file_get_contents($batch_file);
                            $content_items = @unserialize($batch_file_content);
                        }
                        $i++;
                    }
                }
                $remaining = $i;
            }


        } else {
            $total = count($content_items);
        }

        if ($content_items != false and is_array($content_items)) {
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

                if ($batch_file != false and is_file($batch_file)) {
                    unlink($batch_file);
                }

                $remaining = $remaining - 1;
                if ($remaining <= 0) {
                    file_put_contents($index_file, '0');
                }
                return array('success' => count($restored_items) . " items restored"
                , 'total' => ($total)
                , 'remaining' => ($remaining)
                );


            }

        }
        return false;
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

        $map_keys['encoded'] = 'content';
        $map_keys['fulltext'] = 'content';


        $map_keys['post_type'] = 'content_type';


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
            if (isset($item['id'])) {
                unset($item['id']);
            }
            $new_item = array();
            foreach ($map_keys as $map_key => $map_val) {
                if ((isset($item[$map_key]) and $item[$map_key] != false) and (!isset($item[$map_val]) or $item[$map_val] == false)) {
                    $new_val = $item[$map_key];
                    if ($map_key == 'categorysubcategory') {
                        $new_val = explode('/', $new_val);
                    }
                    $item[$map_val] = $new_val;
                    $new_item[$map_val] = $new_val;
                }

            }
            //$res[] = $new_item;
            $res[] = $item;

        }
        return $res;
    }


}

