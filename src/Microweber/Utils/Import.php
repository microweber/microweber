<?php
/**
 * Class used to import and restore the database or the userfiles directory.
 */

namespace Microweber\Utils;

api_expose_admin('Microweber\Utils\Import\delete');
api_expose_admin('Microweber\Utils\Import\create');
api_expose_admin('Microweber\Utils\Import\download');
api_expose_admin('Microweber\Utils\Import\create_full');
api_expose_admin('Microweber\Utils\Import\move_uploaded_file_to_import');
api_expose_admin('Microweber\Utils\Import\restore');
api_expose_admin('Microweber\Utils\Import\export');
api_expose_admin('Microweber\Utils\Import\batch_process');

class Import {
    public $import_to_page_id = false;
    public $imports_folder = false;
    public $import_file = false;
    public $app;
    public $batch_size = 5;
    public $xml_paths = array('channel' => 'item',
                              'feed'    => 'entry',
                              'feed'    => 'post_item',
                              'records' => 'record',);
    /**
     * The import class is used for making or restoring exported files from other CMS.
     *
     * @category  mics
     */
    private $file_q_sep = '; /* MW_QUERY_SEPERATOR */';
    private $prefix_placeholder = '/* MW_PREFIX_PLACEHOLDER */';

    public function __construct($app = null) {
        spl_autoload_register(function ($class) {
            $prefix = 'QueryPath';

            if (!substr($class, 0, 8)===$prefix){
                return;
            }

            $class = substr($class, strlen($prefix));
            $location = __DIR__ . '/lib/QueryPath' . str_replace('\\', '/', $class) . '.php';

            if (is_file($location)){
                require_once $location;
            }
        });

        if (!defined('USER_IP')){
            if (isset($_SERVER['REMOTE_ADDR'])){
                define('USER_IP', $_SERVER['REMOTE_ADDR']);
            } else {
                define('USER_IP', '127.0.0.1');
            }
        }
        if (is_object($app)){
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }

    public function get() {
        if (!is_admin()){
            error('must be admin');
        }

        $here = $this->get_import_location();

        $files = glob("$here{*.sql,*.zip,*}", GLOB_BRACE);

        usort($files, function ($a, $b) {
            return filemtime($a) < filemtime($b);
        });

        $backups = array();
        if (!empty($files)){
            foreach ($files as $file) {
                if (is_file($file)){ //if (strpos($file, '.sql', 1) or strpos($file, '.zip', 1)) {
                    $mtime = filemtime($file);
                    // Get time and date from filename
                    $date = date('F d Y', $mtime);
                    $time = date('H:i:s', $mtime);
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

    public function move_uploaded_file_to_import($params) {
        only_admin_access();
        if (!isset($params['src'])){
            return array('error' => 'You have not provided src to the file.');
        }

        $check = url2dir(trim($params['src']));
        $here = $this->get_import_location();
        if (is_file($check)){
            $fn = basename($check);
            if (copy($check, $here . $fn)){
                @unlink($check);

                return array('success' => "$fn was moved!");
            } else {
                return array('error' => 'Error moving uploaded file!');
            }
        } else {
            return array('error' => 'Uploaded file is not found!');
        }
    }

    public function delete($params) {
        if (!is_admin()){
            error('must be admin');
        }

        // Get the provided arg
        $id = $params['id'];

        // Check if the file has needed args
        if ($id==null){
            return array('error' => 'You have not provided filename to be deleted.');
        }

        $here = $this->get_bakup_location();
        $filename = $here . $id;

        $id = str_replace('..', '', $id);
        $filename = str_replace('..', '', $filename);

        if (is_file($filename)){
            unlink($filename);

            return array('success' => "$id was deleted!");
        } else {
            $filename = $here . $id . '.sql';
            if (is_file($filename)){
                unlink($filename);

                return array('success' => "$id was deleted!");
            }
        }
    }

    public function download($params) {
        if (!is_admin()){
            mw_error('must be admin');
        }

        ini_set('memory_limit', '512M');
        set_time_limit(0);

        if (isset($params['id'])){
            $id = $params['id'];
        } elseif (isset($_GET['filename'])) {
            $id = $params['filename'];
        } elseif (isset($_GET['file'])) {
            $id = $params['file'];
        }
        $id = str_replace('..', '', $id);

        // Check if the file has needed args
        if ($id==null){
            return array('error' => 'You have not provided filename to download.');

            die();
        }

        $here = $this->get_bakup_location();
        // Generate filename and set error variables

        $filename = $here . $id;
        $filename = str_replace('..', '', $filename);
        if (!is_file($filename)){
            return array('error' => 'You have not provided a existing filename to download.');

            die();
        }
        // Check if the file exist.
        if (file_exists($filename)){
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

    public function get_bakup_location() {
        return $this->get_import_location();
    }

    public function readfile_chunked($filename, $retbytes = true) {
        $filename = str_replace('..', '', $filename);

        $chunk_size = 1024 * 1024;
        $buffer = '';
        $cnt = 0;
        // $handle = fopen($filename, "rb");
        $handle = fopen($filename, 'rb');
        if ($handle===false){
            return false;
        }

        while (!feof($handle)) {
            $buffer = fread($handle, $chunk_size);
            echo $buffer;
            ob_flush();
            flush();
            if ($retbytes){
                $cnt += strlen($buffer);
            }
        }
        $status = fclose($handle);
        if ($retbytes && $status){
            return $cnt; // return num. bytes delivered like readfile() does.
        }

        return $status;
    }

    public function restore($params) {
        only_admin_access();

        $id = null;
        if (isset($params['id'])){
            $id = $params['id'];
        } elseif (isset($_GET['filename'])) {
            $id = $params['filename'];
        } elseif (isset($_GET['file'])) {
            $id = $params['file'];
        }

        if ($id==null){
            return array('error' => 'You have not provided a file to restore.');
            die();
        }
        $id = str_replace('..', '', $id);

        $here = $this->get_bakup_location();
        $filename = $here . $id;

        if (isset($_POST['import_to_page_id'])){
            $this->import_to_page_id = intval($_POST['import_to_page_id']);
        }

        if (!is_file($filename)){
            return array('error' => 'You have not provided a existing backup to restore.');
        } else {
            return $this->import_file($filename);
        }

        return $params;
    }

    public function import_file($filename) {
        only_admin_access();
        if (!is_file($filename)){
            return array('error' => 'You have not provided a existing backup to restore.');
        }
        $ext = get_file_extension($filename);
        $import_method = strtolower('queue_import_' . $ext);
        if (method_exists($this, $import_method)){
            ini_set('memory_limit', '512M');
            set_time_limit(900);

            return $this->$import_method($filename);
        } else {
            return array('error' => "Cannot find method for importing $ext files.");
        }
    }

    public function save_content_item($content) {
        if (!isset($content['title']) or $content['title']==false){
            return;
        }

        $this->app->media_manager->download_remote_images = true;

        if (isset($content['content_type']) and $content['content_type']=='category'){
            unset($content['content_type']);

            if (isset($content['title']) and is_string($content['title'])){
                $category_set_item = $content['title'];
                $cat_item = $this->app->category_manager->get('no_cache=true&single=true&rel_type=content&title=' . $content['title']);

                if (!isset($cat_item['id'])){
                    $new = $content;
                    $new['rel_type'] = 'content';
                    $new['title'] = $category_set_item;
                    // parent_category_title

                    $new_cat = $this->app->category_manager->save($new);
                    $cat_item = $this->app->category_manager->get_by_id($new_cat);
                }
                if (isset($cat_item['id'])){
                    if (isset($content['parent'])){
                        unset($content['parent']);
                    }

                    $new = $content;
                    $new['id'] = $cat_item['id'];

                    //  dd($new);
                    return $this->app->category_manager->save($new);
                }
            }

            //return save_category($content);
        } else {

            // make categories
            if (isset($content['categories']) and is_string($content['categories'])){
                $category_sets = explode(',', $content['categories']);
                $cats_ids = array();
                foreach ($category_sets as $category_set) {
                    if (is_string($category_set)){
                        $category_set_items = explode('/', $category_set);

                        $nest_level = 0;
                        $prev_parent_cat = 0;
                        foreach ($category_set_items as $category_set_item) {
                            $category_set_item = trim($category_set_item);
                            if ($category_set_item==false){
                                //  dd($category_set_items,$category_set,$content);
                            }

                            if ($category_set_item!=false){
                                $cat_item = $this->app->category_manager->get('no_cache=true&single=true&rel_type=content&title=' . $category_set_item);
                                // dd($category_set_items,$category_set_item);
                                //if (!isset($cat_item['id'])) {

                                $new = array();
                                if (isset($cat_item['id'])){
                                    $new['id'] = $cat_item['id'];

                                }
                                $new['rel_type'] = 'content';
                                $new['title'] = $category_set_item;
                                if (isset($content['parent']) and $nest_level==0){
                                    $new['parent_page'] = $content['parent'];
                                }
                                if ($nest_level > 0 and $prev_parent_cat){
                                    $new['parent_id'] = $prev_parent_cat;
                                }
                                // d($category_set_items);
                                $new_cat = $this->app->category_manager->save($new);
                                $cat_item = $this->app->category_manager->get_by_id($new_cat);
                                //  }
                                if (isset($cat_item['id'])){
                                    $prev_parent_cat = $cat_item['id'];
                                    $cats_ids[] = $cat_item['id'];
                                }

                                ++ $nest_level;
                            }
                        }
                    }
                }
                if (!empty($cats_ids)){
                    $content['categories'] = $cats_ids;
                }
            }

            // dd($content);
            if (isset($content['images']) and is_string($content['images'])){
                $content['images'] = explode(',', $content['images']);
                $content['images'] = array_unique($content['images']);
            }

            $is_saved = get_content('one=true&title=' . $content['title']);

            if (isset($content['description']) and (!isset($content['content']) or $content['content']==false)){
                //$content['content'] = $content['description'];
            }

            if (isset($content['parent'])){
                $par = get_content_by_id($content['parent']);

                if ($par!=false){
                    if (isset($par['is_shop']) and $par['is_shop']==1){
                        $content['content_type'] = 'product';
                        $content['subtype'] = 'product';
                    }
                }
            }

            if (!isset($content['content_type'])){
                $content['content_type'] = 'post';
            }
            if (!isset($content['subtype'])){
                $content['subtype'] = 'post';
            }
            // $content['subtype'] = 'post';
            $content['is_active'] = 1;
            if (isset($content['debug'])){
                unset($content['debug']);
            }
            //  $content['debug'] = 'y';
            $content['download_remote_images'] = true;
            if ($is_saved!=false){
                $content['id'] = $is_saved['id'];
                if (!isset($content['content_type'])){
                    $content['content_type'] = $is_saved['content_type'];
                    $content['subtype'] = $is_saved['subtype'];
                }
            }

            return save_content($content);
        }
    }

    public function queue_import_json($filename) {
        only_admin_access();
        if (!is_file($filename)){
            return array('error' => 'You have not provided a existing backup to restore.');
        }
        $json = file_get_contents($filename);
        $rows = json_decode($json, true);
        $content_items = $rows;
        $content_items = $this->map_array($rows);

        return $this->batch_save($content_items);
    }

    public function batch_process($content_items = false) {
        $chunks_folder = $this->get_import_location() . '_process_import' . DS;
        $index_file = $chunks_folder . 'index.php';
        if (!is_dir($chunks_folder)){
            mkdir_recursive($chunks_folder);
        }

        $total = 0;
        $remaining = 0;
        $batch_file = false;
        $save_log_info = $this->log();

        if (is_array($content_items) and isset($content_items['process'])){
            $content_items = false;
        } elseif (is_array($content_items) and isset($content_items['cancel'])){
            rmdir_recursive($chunks_folder);
            return;
        }




        if (!is_array($content_items) or empty($content_items)){
            $content_items = array();
            if (is_file($index_file)){
                $total = file_get_contents($index_file);
            }
            if ($total==0){
                $total = 0;
                $dir = $chunks_folder;
                if ($handle = opendir($dir)){
                    while (($file = readdir($handle))!==false) {
                        if (!in_array($file, array('.', '..')) && !is_dir($dir . $file) and strstr($file, 'import_chunk_')){
                            ++ $total;
                        }
                    }
                }
                $save_log_info['total'] = $total;
                $this->log($save_log_info);
                file_put_contents($index_file, $total);
            }

            $i = 0;
            $dir = $chunks_folder;
            $rem_counter = 0;
            $process_xml_files = array();
            $chunk_size = $this->batch_size;

            if ($handle = opendir($dir)){
                while (($file = readdir($handle))!==false) {
                    if (!in_array($file, array('.', '..')) && !is_dir($dir . $file) and strstr($file, 'import_chunk_')){
                        //if (!is_array($content_items)) {
                        if ($i < $chunk_size){
                            $batch_file = $chunks_folder . $file;

                            $batch_file_content = file_get_contents($batch_file);
                            if (strstr($file, 'import_chunk_xml')){

                                // for ($x=0; $x<=10; $x++){
                                $content_from_xml = $this->parse_content_from_xml_string($batch_file_content);
                                if (is_array($content_from_xml)){
                                    foreach ($content_from_xml as $content_from_x) {
                                        $content_items[] = $content_from_x;
                                    }
                                    // $rem_counter--;
                                }
                                //}
                            } else {
                                $content_items_from_file = @unserialize($batch_file_content);
                                if (!empty($content_items_from_file)){
                                    foreach ($content_items_from_file as $content_from_x) {
                                        $content_items[] = $content_from_x;
                                    }
                                }
                            }

                            if ($batch_file!=false and is_file($batch_file)){
                                @unlink($batch_file);
                            }
                        }
                        ++ $i;
                    }
                }

                $remaining = $i;
                $save_log_info['remaining'] = $remaining;
                $this->log($save_log_info);
            }
        } else {
            $total = count($content_items);
            $save_log_info['total'] = $total;
            //  $this->log($save_log_info);
        }
        // dd($content_items);
        if ($content_items!=false and is_array($content_items)){
            if (!empty($content_items)){
                $parent = get_content('one=true&subtype=dynamic&is_deleted=0&is_active=1');
                if ($parent==false){
                    $parent = get_content('one=true&content_type=page&is_deleted=0&is_active=1');
                }
                if ($parent==false){
                    $parent = 0;
                }

                $content_items = $this->map_array($content_items);

                $parent_id = $parent['id'];
                $restored_items = array();
                foreach ($content_items as $content) {
                    if (isset($content['title']) and $content['title']!=false){
                        if (!isset($content['parent'])){
                            $content['parent'] = $parent_id;
                        }

                        $restored_items[] = $this->save_content_item($content);
                    }
                }
                cache_clear('categories');
                cache_clear('content');

                $remaining = $remaining - 1;
                if ($remaining <= 0){
                    file_put_contents($index_file, '0');
                }

                if ($total < $remaining){
                    $total = 0;
                    $dir = $chunks_folder;
                    if ($handle = opendir($dir)){
                        while (($file = readdir($handle))!==false) {
                            if (!in_array($file, array('.', '..')) && !is_dir($dir . $file) and strstr($file, 'import_chunk_')){
                                ++ $total;
                            }
                        }
                    }
                    file_put_contents($index_file, $total);
                }

                $save_log_info['remaining'] = $remaining;
                $save_log_info['total'] = $total;
                $this->log($save_log_info);
                $ret = array(
                    'success'   => count($restored_items) . ' items restored',
                    'total'     => ($total),
                    'remaining' => ($remaining),
                );

                if ($total > 0 and $remaining > 0){
                    $val2 = $total;
                    $val1 = $remaining;
                    $res = round(($val1 / $val2) * 100);
                    $remaining_perc = 100 - $res;
                    $ret['percent'] = $remaining_perc;
                }


                return $ret;
            }
        }

        return false;
    }

    public function parse_content_from_xml_string($xml_string) {
        libxml_use_internal_errors(true);

        $parser2 = __DIR__ . DIRECTORY_SEPARATOR . 'lib/QueryPath/QueryPath.php';
        require_once $parser2;

        $parser2 = __DIR__ . DIRECTORY_SEPARATOR . 'lib/QueryPath/qp.php';

        require_once $parser2;

        $content_items = array();

        $items = qp($xml_string, 'item');
        if (count($items)==0){
            $items = qp($xml_string, 'item');
        }

        foreach ($items as $item) {
            $content_item = array();

            //$arr = $item->eq(0)->contents();

            //  print $item->text();
            // $el = qp($item, 'channel>item>title');
            $el = $item->find('title');
            $content_item['title'] = $el->eq(0)->text();

            // $el = qp($item, 'channel>item>encoded');
            //$el = $item->find('encoded');
            $content_item['content'] = false;
            if ($content_item['content']==false){
                $el = $item->find('content');
                $content_item['content'] = $el->eq(0)->text();
                $content_item['content'] = $el->eq(0)->text();
            }

            $content_item['created_at'] = false;

            if ($content_item['created_at']==false){
                $el = $item->find('post_date_gmt');
                $content_item['created_at'] = $el->eq(0)->text();
            }

            if ($content_item['created_at']==false){
                $el = $item->find('post_date');
                $content_item['created_at'] = $el->eq(0)->text();
            }
            if ($content_item['created_at']==false){
                $el = $item->find('pubDate');
                $content_item['created_at'] = $el->eq(0)->text();
            }
            if ($content_item['created_at']!=false){
                $content_item['updated_at'] = $content_item['created_at'];
            }
            //$el = qp($item, 'channel>item>description');
            $el = $item->find('description');
            $content_item['description'] = $el->eq(0)->text();

            if ($content_item['content']==false){
                $content_item['content'] = $c = ($item->find('content')->eq(0)->innerHTML());
            }
            if ($content_item['content']==false){
                $el = $item->find('summary');

                $content_item['content'] = $el->eq(0)->text();
            }

            if ($content_item['content']==false){
                $el = $item->find('encoded');
                $content_item['content'] = $el->eq(0)->text();
            }
            //$c= ($item->find('content')->text());

            //$itm = $item->eq(0)->html();

            // print $item->tag() . PHP_EOL;

            //$el = qp($item, 'channel>item>post_type');
            $el = $item->find('post_type');

            $content_item['post_type'] = $el->eq(0)->text();
            $cats = $item->find('category');
            // $cats = qp($item, 'channel>item>category');
            foreach ($cats as $cat) {
                $content_item['categories'][] = $cat->text();
            }
            $content_items[] = $content_item;
        }

        return $content_items;
    }

    public function queue_import_xml($filename) {
        only_admin_access();

        if (!is_file($filename)){
            return array('error' => 'You have not provided a existing backup to restore.');
        }
        $chunk_size = $this->batch_size;

        libxml_use_internal_errors(true);
        $chunks_folder = $this->get_chunks_location();

        $content_items = array();
        $chunk_size = $this->batch_size;
        $i = 0;

        $xml_paths = $this->xml_paths;
        $content_batch = '';
        foreach ($xml_paths as $xml_key => $xml_path) {
            $XMLReader = new \XMLReader();
            $xml_file_path = $filename;
            $XMLReader->open($xml_file_path);

// Move to the first "[item name]" node in the file.
            while ($XMLReader->read() && $XMLReader->name!=$xml_path) {

                //$xml_str = $XMLReader->readOuterXML();
                // d($xml_str);
            }
// Now that we're at the right depth, hop to the next "[item name]" until the end of tree/file.
            while ($XMLReader->name===$xml_path) {
                $xml_str = $XMLReader->readOuterXML();
                if ($xml_str!=''){
                    //$content_batch = $content_batch . $xml_str . "\n";
                    $content_batch = $xml_str;

                    //if ($i % $chunk_size == 0) {
                    $file_name = 'import_chunk_xml_' . md5($content_batch);
                    $file_location = $chunks_folder . $file_name;
                    if (!is_file($file_location)){
                        $content_batch = str_replace('content:encoded', 'content', $content_batch);
                        $content_batch = str_replace('<' . $xml_path, '<item', $content_batch);
                        $content_batch = str_replace('</' . $xml_path, '</item', $content_batch);

                        $rss_stub = '<?xml version="1.0"?>' . "\n";
                        file_put_contents($file_location, $rss_stub . $content_batch);
                    }
                    $content_batch = '';
                    // }

                    ++ $i;
                    $XMLReader->next($xml_path);
                }
            }
            //$XMLReader->close();
        }
        $file_name = 'import_chunk_xml_' . md5($content_batch);
        $file_location = $chunks_folder . $file_name;
        if (!is_file($file_location)){
            file_put_contents($file_location, $content_batch);
        }

        return array('success' => ($i) . ' xml items will be imported');
    }

    public function queue_import_xlsx($filename) {
        return $this->queue_import_xls($filename);
    }

    public function queue_import_xls($filename) {
        only_admin_access();
        $target_url = 'http://api.microweber.com/service/xls2csv/index.php';
        $file_name_with_full_path = realpath($filename);
        $post = array('test' => '123456', 'file_contents' => '@' . $file_name_with_full_path);
        $result = $this->app->http->url($target_url)->post($post);

        $err = false;
        if ($result!=false){
            $result = json_decode($result, true);
            if (!isset($result['result'])){
                $err = true;
            }
        } else {
            $err = true;
        }
        if ($err==true){
            return array('error' => 'Could not contact the Microweber remote server to parse the Excel file. Please try uploading a CSV file.');
        } else {
            if (isset($result['result'])){
                $url = $result['result'];
                $target_dir = MW_CACHE_DIR . 'backup_restore' . DS . 'excel' . DS;
                if (!is_dir($target_dir)){
                    mkdir_recursive($target_dir);
                }
                $local_target_file = basename($url);
                $local_target_file = (str_ireplace('.xlsx', '.csv', $local_target_file));
                $local_target_file = (str_ireplace('.xls', '.csv', $local_target_file));
                $local_save_path = $target_dir . $local_target_file;
                $fp = fopen($local_save_path, 'w+'); //This is the file where we save the    information
                $ch = curl_init(str_replace(' ', '%20', $url)); //Here is the file we are downloading, replace spaces with %20
                curl_setopt($ch, CURLOPT_TIMEOUT, 50);
                curl_setopt($ch, CURLOPT_FILE, $fp); // write curl response to file
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_exec($ch); // get curl response
                curl_close($ch);
                fclose($fp);

                return $this->queue_import_csv($local_save_path);
            }
        }
    }

    public function queue_import_csv($filename) {
        only_admin_access();
        if (!is_file($filename)){
            return array('error' => 'You have not provided a existing backup to restore.');
        }

        $csv = new \Keboola\Csv\CsvFile($filename);

        $head = $csv->getHeader();
        if (!isset($head[2])){
            $csv = new \Keboola\Csv\CsvFile($filename, ';');
            $head = $csv->getHeader();
        } elseif (isset($head[0]) and stristr($head[0], ';')) {
            $csv = new \Keboola\Csv\CsvFile($filename, ';');
            $head = $csv->getHeader();
        }

        if (empty($head) or empty($csv)){
            return array('error' => 'CSV file cannot be parsed properly.');
        }
        $rows = array();
        $i = 0;
        foreach ($csv as $row) {
            if ($i > 0){
                $r = array();
                if (is_array($row)){
                    foreach ($row as $k => $v) {
                        if (isset($head[ $k ])){
                            $row[ $head[ $k ] ] = $v;
                            $new_k = strtolower($head[ $k ]);
                            $new_k = str_replace(' ', '_', $new_k);
                            $new_k = str_replace('__', '_', $new_k);
                            // $new_k = preg_replace("/[^a-zA-Z0-9_]+/", "", $new_k);
                            $new_k = rtrim($new_k, '_');
                            $r[ $new_k ] = $v;
                        }
                    }
                }
                $rows[] = $r;
            }
            ++ $i;
        }
        $content_items = $rows;
        $content_items = $this->map_array($rows);

        return $this->batch_save($content_items);
    }

    public function map_array($content_items) {
        if (empty($content_items)){
            return false;
        }

        $res = array();
        $map_keys = array();

        //title keys
        $map_keys['name'] = 'title';
        $map_keys['product_name'] = 'title';
        $map_keys['productname'] = 'title';
        $map_keys['content_title'] = 'title';

        //description keys
        $map_keys['introtext'] = 'description';
        $map_keys['short_description'] = 'description';
        $map_keys['summary'] = 'description';
        $map_keys['excerpt'] = 'description';

        $map_keys['encoded'] = 'content';
        $map_keys['fulltext'] = 'content';

        $map_keys['post_type'] = 'content_type';

        //url keys
        $map_keys['url_rewritten'] = 'url';
        $map_keys['content_url'] = 'url';

        $map_keys['alias'] = 'url';
        //  $map_keys['link'] = 'url';

        //parent
        $map_keys['content_parent'] = 'parent';

        //content type
        $map_keys['content_subtype'] = 'subtype';
        $map_keys['type'] = 'content_type';

        //image keys
        $map_keys['image_urls_xyz'] = 'insert_content_image';
        $map_keys['picture_url'] = 'insert_content_image';

        $map_keys['pictures'] = 'pictures';
        $map_keys['img'] = 'pictures';
        $map_keys['image'] = 'pictures';

        //categories keys
        $map_keys['categories'] = 'categories';
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
        $map_keys['product_creation_date'] = 'created_at';
        $map_keys['product_available_date'] = 'updated_at';
        $map_keys['created'] = 'created_at';
        $map_keys['modified'] = 'updated_at';
        $map_keys['published'] = 'created_at';
        $map_keys['updated'] = 'updated_at';
        $map_keys['pubDate'] = 'created_at';

        if (isset($item['is_home']) and $item['is_home']=='y'){
            $item['is_home'] = 1;
        }

        if (isset($item['is_active']) and $item['is_active']=='y'){
            $item['is_active'] = 1;
        }

        if (isset($item['is_shop']) and $item['is_shop']=='y'){
            $item['is_shop'] = 1;
        } elseif (isset($item['is_shop']) and $item['is_shop']=='n') {
            $item['is_shop'] = 0;
        }
        if (isset($item['is_deleted']) and $item['is_deleted']=='y'){
            $item['is_deleted'] = 1;
        } elseif (isset($item['is_deleted']) and $item['is_deleted']=='n') {
            $item['is_deleted'] = 0;
        }
        foreach ($content_items as $item) {
            if (isset($item['id'])){
                unset($item['id']);
            }
            $skip = false;
            $new_item = array();
            foreach ($map_keys as $map_key => $map_val) {
                if ((isset($item[ $map_key ]) and $item[ $map_key ]!=false) and (!isset($item[ $map_val ]) or $item[ $map_val ]==false)){
                    $new_val = $item[ $map_key ];

                    $item[ $map_val ] = $new_val;
                    $new_item[ $map_val ] = $new_val;
                    unset($item[ $map_key ]);
                }
            }

            if (isset($item['category']) and isset($item['category']['@attributes'])){
                $attrs = $item['category']['@attributes'];
                if (isset($attrs['term']) and stristr($attrs['term'], 'kind#')){
                    if (stristr($attrs['term'], 'kind#post')){
                        $skip = false;
                    } else {
                        $skip = 1;
                    }
                }
            } elseif (isset($item['category']) and is_array($item['category'])) {
                $cats = array();
                foreach ($item['category'] as $cat) {
                    if (is_array($cat) and isset($cat['@attributes'])){
                        $attrs = $cat['@attributes'];

                        if (isset($attrs['nicename']) and isset($attrs['domain']) and stristr($attrs['domain'], 'category')){
                            $cats[] = $attrs['nicename'];
                        }
                    }
                }
                if (!empty($cats)){
                    $item['category'] = $cats;
                }
            }

            if ($skip==false and isset($item['title'])){
                $res[] = $item;
            }
        }

        return $res;
    }

    public function batch_save($content_items) {
        $chunk_size = $this->batch_size;
        $content_items = $this->map_array($content_items);

        if (!empty($content_items)){
            $copy = array();
            foreach ($content_items as $content_item) {
                if (!isset($content_item['parent'])){
                    if ($this->import_to_page_id!=false){
                        $content_item['parent'] = $this->import_to_page_id;
                    }
                }
                $copy[] = $content_item;
            }
            $content_items = $copy;
        }

        $chunks_folder = $this->get_chunks_location();
        $index_file = $chunks_folder . 'index.php';

        if (!is_dir($chunks_folder)){
            mkdir_recursive($chunks_folder);
            @touch($index_file);
        }

        if (!is_writable($chunks_folder)){
            return array('error' => 'Import folder is not writable!');
        }

        $chunks = (array_chunk($content_items, $chunk_size, true));
        $i = 0;
        if (!empty($chunks)){
            foreach ($chunks as $chunk) {
                $chunk_data = serialize($chunk);
                $file_name = 'import_chunk_' . $i;
                $file_location = $chunks_folder . $file_name;

                if (!is_file($file_location)){
                    file_put_contents($file_location, $chunk_data);
                }
                $i ++;
            }
        }

        return array('success' => count($content_items) . ' items are scheduled for import');
    }

    public function get_chunks_location() {
        $chunks_folder = $this->get_import_location() . '_process_import' . DS;
        $index_file = $chunks_folder . 'index.php';

        if (!is_dir($chunks_folder)){
            mkdir_recursive($chunks_folder);
            @touch($index_file);
        }

        return $chunks_folder;
    }

    public function log($save = null) {
        $chunks_folder = $this->get_import_location() . 'log' . DS;
        $index_file = $chunks_folder . 'log.json';

        if (!is_dir($chunks_folder)){
            mkdir_recursive($chunks_folder);
        }
        if (!is_file($index_file)){
            @touch($index_file);
        }
        if ($save!==null){
            file_put_contents($index_file, json_encode($save));
            $save = null;
        }
        if ($save===null){
            $cont = json_decode(file_get_contents($index_file), true);

            return $cont;
        }

        //return $chunks_folder;
    }

    public function get_import_location() {
        if (defined('MW_CRON_EXEC')){
        } elseif (!is_admin()) {
            return false;
        }

        $loc = $this->imports_folder;

        if ($loc!=false){
            return $loc;
        }
        $folder_root = false;
        if (function_exists('userfiles_path')){
            $folder_root = userfiles_path();
        } elseif (mw_cache_path()) {
            $folder_root = normalize_path(mw_cache_path());
        }

        $here = $folder_root . 'import' . DS;

        if (!is_dir($here)){
            mkdir_recursive($here);
            $hta = $here . '.htaccess';
            if (!is_file($hta)){
                touch($hta);
                file_put_contents($hta, 'Deny from all');
            }
        }

        $here = $folder_root . 'import' . DS . get_table_prefix() . DS;
        $here2 = $this->app->option_manager->get('import_location', 'admin/import');
        if ($here2!=false and is_string($here2) and trim($here2)!='default' and trim($here2)!=''){
            $here2 = normalize_path($here2, true);
            if (!is_dir($here2)){
                mkdir_recursive($here2);
            }
            if (is_dir($here2)){
                $here = $here2;
            }
        }

        if (!is_dir($here)){
            mkdir_recursive($here);
        }

        $loc = $here;

        $this->imports_folder = $loc;

        return $here;
    }

    public function export() {
        only_admin_access();

        $cont = get_content('is_active=1&is_deleted=0&limit=250000&orderby=updated_at desc');
        echo count($cont);
        exit;
    }
}

class SimpleXmlStreamer extends \Microweber\Utils\lib\XmlStreamer {
    public $content_items = array();

    public function processNode($xmlString, $elementName, $nodeIndex) {
        $xml_items = \simplexml_load_string($xmlString);
        $skip = false;
        $content = array();
        $xmls = array();
        $xmls[] = $xml_items;
        foreach ($xmls as $xml) {
            $encoded = json_encode($xml);
            $a = (json_decode($encoded, true));
            if (is_array($a)){
                if (isset($a['item']) and is_array($a['item'])){
                    $a_item = $a['item'];
                    foreach ($a_item as $a_ite) {
                        $this->content_items[] = $a_ite;
                    }
                } else {
                    $this->content_items[] = $a;
                }
            }
        }

        return true;
    }

    /**
     * Called after a file chunk was processed (16KB by default, see constructor).
     */
    public function chunkCompleted() {
        $import = mw('Microweber\Utils\Import')->batch_save($this->content_items);
        $this->content_items = array();

        return true;
    }
}
