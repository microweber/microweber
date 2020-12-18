<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Microweber\Utils\Adapters\Packages\Helpers;

use Composer\Config;
use Composer\Cache;
use Composer\EventDispatcher\EventDispatcher;
use Composer\Package\PackageInterface;
use Composer\Util\IniHelper;
use Composer\Util\Platform;
use Composer\Util\ProcessExecutor;
use Composer\Util\RemoteFilesystem;
use Composer\IO\IOInterface;
use Microweber\Utils\Adapters\Packages\PackageManagerUnzipOnChunksException;
use Microweber\Utils\Unzip;
use Symfony\Component\Process\ExecutableFinder;
use ZipArchive;
use Microweber\Utils\Adapters\Packages\Helpers\ArchiveDownloader as ArchiveDownloader;


class ZipDownloader extends ArchiveDownloader
{
    protected static $hasSystemUnzip;
    private static $hasZipArchive;
    private static $isWindows;

    protected $process;

    public function __construct(IOInterface $io, Config $config, EventDispatcher $eventDispatcher = null, Cache $cache = null, ProcessExecutor $process = null, RemoteFilesystem $rfs = null)
    {
        $this->process = $process ?: new ProcessExecutor($io);
        parent::__construct($io, $config, $eventDispatcher, $cache, $rfs);
    }

    /**
     * {@inheritDoc}
     */
    public function download(PackageInterface $package, $path, $output = true)
    {
        return parent::download($package, $path, $output);
    }


    /**
     * extract $file to $path with ZipArchive
     *
     * @param  string $file File to extract
     * @param  string $path Path where to extract file
     * @param  bool $isLastChance If true it is called as a fallback and should throw an exception
     * @return bool   Success status
     */
    protected function extractWithZipArchive($file, $path, $isLastChance)
    {
         $path = normalize_path($path, true);

        $temporaryDir = $this->config->get('vendor-dir') . '/composer-unzip/';
        $temporaryDir = normalize_path($temporaryDir, true);
        if (!is_dir($temporaryDir)) {
            mkdir_recursive($temporaryDir);
        }
        $cache_key = 'zip-' . md5($file . $path) . '.json';

        $chunks = false;
        $cache_file = $temporaryDir . $cache_key;

        if (is_file($cache_file)) {
            $cache_file_content = @json_decode(@file_get_contents($cache_file), true);

            if ($cache_file_content == 'done') {
                return;
            }

            if ($cache_file_content) {
                $chunks = $cache_file_content;
            }
        }

        if (!$chunks) {

            if (!is_dir($path)) {
                mkdir_recursive($path);
            }

            set_time_limit(1200);
            ini_set('memory_limit', '1024M');


            $filez = array();

            $zip = new \ZipArchive();
            $zip->open($file, \ZipArchive::CHECKCONS);
            $file_count = $zip->numFiles;

            for ($i = 0; $i < $file_count; $i++) {
                $file_name = $zip->getNameIndex($i);
                $filez[$i] = $file_name;
            }

            $zip->close();
            unset($zip);

            $chunks = array();

            if ($filez) {
                $chunks = array_chunk($filez, 50);
            }

            file_put_contents($cache_file, json_encode($chunks));


        }

        if ($chunks) {
            $str = substr($file, 2);

            $f = getcwd() . DS . $str;
            $f = normalize_path($f, false);
            //  $p = __DIR__.DS.$path;

            //  $cache_save = array('file' => $file, 'path' => $path, 'chunks_file' => $cache_file);
            $cache_save = array('file' => $f, 'path' => $path, 'chunks_file' => $cache_file);
            cache_save($cache_save, $cache_key, 'composer-unzip');

            throw new PackageManagerUnzipOnChunksException($cache_key, 100);

        }


        /* if ($chunks) {
             $chunks_count = count($chunks);


             foreach ($chunks as $chunks_key => $chunks_part) {
                 $try_again = false;
                 $this->io->writeError('    Unzip chunk ' . $chunks_key . ' of ' . $chunks_count);


                 set_time_limit(1200);
                 //ini_set('memory_limit', '1024M');
                 ini_set('memory_limit', '-1');


                 $zip = new ZipArchive();
                 $zip->open($file);

                 $extractResult = $zip->extractTo($path, $chunks_part);
                 $zip->close();
                 unset($zip);
                 unset($chunks[$chunks_key]);

                 if ($chunks) {
                     $json = json_encode($chunks, JSON_UNESCAPED_SLASHES);
                     $try_again = true;


                 } else {
                     $try_again = false;

                     $json = 'done';
                 }
                 file_put_contents($cache_file, $json);

                 break;

             }



             if ($try_again) {
                 throw new PackageManagerUnzipException('try again', 100);
             }



         }*/

    }

    private function __extractChunked($file, $path)
    {

        if (!is_dir($path)) {
            mkdir_recursive($path);
        }

        set_time_limit(1200);
        ini_set('memory_limit', '1024M');


        $filez = array();

        $zip = new \ZipArchive();
        $zip->open($file, \ZipArchive::CHECKCONS);
        $file_count = $zip->numFiles;

        for ($i = 0; $i < $file_count; $i++) {
            $file_name = $zip->getNameIndex($i);
            $filez[$i] = $file_name;
        }

        $zip->close();
        unset($zip);

        $chunks = array();

        if ($filez) {
            $chunks = array_chunk($filez, 500);
        }


        if ($chunks) {
            $chunks_count = count($chunks);


            foreach ($chunks as $chunks_key => $chunks_part) {
                $try_again = false;
                $this->io->writeError('    Unzip part ' . $chunks_key . ' of ' . $chunks_count);


                set_time_limit(1200);
                ini_set('memory_limit', '-1');


                $zip = new ZipArchive();
                $zip->open($file);

                $extractResult = $zip->extractTo($path, $chunks_part);
                $zip->close();
                unset($zip);
                unset($chunks[$chunks_key]);


            }
        }


    }


    /**
     * extract $file to $path
     *
     * @param string $file File to extract
     * @param string $path Path where to extract file
     */
    public    function extract($file, $path)
    {

        set_time_limit(1200);
        ini_set('memory_limit', '1024M');


        $path = normalize_path($path, true);
    //    $this->__extractChunked($file, $path);

        $zip = new \ZipArchive();
        $zip->open($file);
        $extractResult = $zip->extractTo($path);
        $zip->close();




        //        print $path;
//        dd($extractResult);


        //   $this->extractWithZipArchive($file, $path, false);


    }

    /**
     * Give a meaningful error message to the user.
     *
     * @param  int $retval
     * @param  string $file
     * @return string
     */
    protected    function getErrorMessage($retval, $file)
    {
        switch ($retval) {
            case ZipArchive::ER_EXISTS:
                return sprintf("File '%s' already exists.", $file);
            case ZipArchive::ER_INCONS:
                return sprintf("Zip archive '%s' is inconsistent.", $file);
            case ZipArchive::ER_INVAL:
                return sprintf("Invalid argument (%s)", $file);
            case ZipArchive::ER_MEMORY:
                return sprintf("Malloc failure (%s)", $file);
            case ZipArchive::ER_NOENT:
                return sprintf("No such zip file: '%s'", $file);
            case ZipArchive::ER_NOZIP:
                return sprintf("'%s' is not a zip archive.", $file);
            case ZipArchive::ER_OPEN:
                return sprintf("Can't open zip file: %s", $file);
            case ZipArchive::ER_READ:
                return sprintf("Zip read error (%s)", $file);
            case ZipArchive::ER_SEEK:
                return sprintf("Zip seek error (%s)", $file);
            default:
                return sprintf("'%s' is not a valid zip archive, got error code: %s", $file, $retval);
        }
    }
}
