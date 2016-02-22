<?php
namespace Microweber\Utils;

//FROM 
class Sitemap {
	var $pages = array();
	var $file;
	function Sitemap($file) {
		$this->file = $file;
	}
	function create()
	{
		$str = $this->xmlHeader();
		$str .= $this->getPages();
		$str .= $this->xmlFooter();
		$this->write2file($this->file, $str);
		return $this->file;
		//die('Done! <a href="'.$this->file.'">SiteMap</a>');
	}
	function xmlHeader()
	{
		$str = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		return $str;
	}
	function xmlFooter()
	{
		$str = '
		</urlset>
		';
		return $str;
	}
	function getPages()
	{
		$str = '';
		for ($i = 0; $i < count($this->pages['url']); $i ++){
			
			$lmod = date('Y-m-d').'T'.date('H:i:s');
			
			if(isset($this->pages['lastmod'][$i]) and $this->pages['lastmod'][$i] != false){
				
				$lmod = date('Y-m-d',strtotime($this->pages['lastmod'][$i])).'T'.date('H:i:s',strtotime($this->pages['lastmod'][$i]));
			}
			
			
			$str .= '
			<url>
				<loc>'.$this->pages['url'][$i].'</loc>
				
				<lastmod>'.$lmod.'+00:00</lastmod>
				<changefreq>'.$this->pages['frecvent'][$i].'</changefreq>
				<priority>'.$this->pages['priority'][$i].'</priority>
			</url>
			';
		}
		return $str;
	}
	function addPage($url, $frecvent = 'daily', $priority = 1.0, $lastmod = false)
	{
		$this->pages['url'][] = $url;
		$this->pages['frecvent'][] = $frecvent;
		$this->pages['lastmod'][] = $lastmod;

		$this->pages['priority'][] = $priority;
	}
	function write2file($fname, $string)
	{
		
		//var_dump($fname, $string);
		//@unlink($fname);
		 @file_put_contents($fname, $string);
	}
}