<?php

/**
 * Php5 Loader Plugin for Codeigniter
 *
 * @author		Piro Fabio (1.2++) - Jonathon Hill (<= 1.1)
 * @version		Version 1.8 (works on Codeigniter <= 1.7.3)
 * @link		Userguide at: http://codeigniter.com/forums/viewthread/99960/P15/
 */

// Init
CI::$ci = & get_instance ();

class CI {
	public static $ci;
	
	// Library
	public static function library($library = '', $params = NULL, $object_name = NULL) {
		$suffix = '';
		$library_name = $library . $suffix;
		
		if (strpos ( $library_name, '/' ) !== FALSE) {
			$sub_directory = explode ( '/', $library_name );
			$library_name = end ( $sub_directory );
		}
		
		if (! isset ( self::$ci->$library_name )) {
			self::$ci->load->library ( $library_name, $params, $object_name );
		}
		
		return self::$ci->$library_name;
	}
	
	// Model
	public static function model($model, $name = '', $db_conn = FALSE) {
		$suffix = '_model';
		$model_name = $model . $suffix;
		
		if (strpos ( $model_name, '/' ) !== FALSE) {
			$sub_directory = explode ( '/', $model_name );
			$model_name = end ( $sub_directory );
		}
		
		if (! isset ( self::$ci->$model_name )) {
			self::$ci->load->model ( $model_name, $name, $db_conn );
		}
		
		return self::$ci->$model_name;
	}
	
	// View
	public static function view($view, $vars = array(), $return = FALSE, $template = TRUE) {
		//$suffix = '_view';
		$view_name = $view . $suffix;
		
		return self::$ci->load->view ( $view_name, $vars, $return );
	}
	
	// View
	public static function file($file, $return = FALSE) {
		
		return self::$ci->load->file ( $file, $return );
	}
	
	// Vars
	public static function vars($vars = array(), $val = '') {
		return self::$ci->load->vars ( $vars, $val );
	}
	
	// Helper
	public static function helper($helper = array()) {
		$suffix = '_helper';
		$helper_name = $helper . $suffix;
		
		if (! empty ( $helper ) && ! isset ( self::$ci->load->_ci_helpers [$helper_name] )) {
			self::$ci->load->helper ( $helper );
		}
		
		return new Modularity ( 'helper' );
	}
	
	// Plugin
	public static function plugin($plugin = array()) {
		$suffix = '_pi';
		$plugin_name = $plugin . $suffix;
		
		if (! empty ( $plugin ) && ! isset ( self::$ci->load->_ci_plugins [$plugin_name] )) {
			self::$ci->load->plugin ( $plugin_name );
		}
		
		return new Modularity ( 'plugin' );
	}
	
	// Config
	public static function config($file = '', $use_sections = FALSE, $fail_gracefully = FALSE) {
		if (! empty ( $file ) && ! in_array ( $file, self::$ci->config->is_loaded )) {
			self::$ci->load->config ( $file, $use_sections, $fail_gracefully );
		}
		
		return new Modularity ( 'config' );
	}
	
	// Lang
	public static function lang($file = array(), $lang = '') {
		if (! empty ( $file ) && ! in_array ( $file . '_lang.php', self::$ci->lang->is_loaded )) {
			self::$ci->load->language ( $file, $lang );
		}
		
		return new Modularity ( 'lang' );
	}
	
	// Db
	public static function db($db = '', $return = FALSE, $active_record = FALSE) {
		if (empty ( $db )) {
			if (! isset ( self::$ci->db )) {
				include (APPPATH . 'config/database' . EXT);
				self::$ci->load->database ( $active_group, $return, $active_record ); // default
			}
			
			return self::$ci->db;
		} else {
			return self::$ci->load->database ( $db, TRUE, $active_record );
		}
	}
}

// Modularity
class Modularity {
	public function __construct($type) {
		$this->type = $type;
	}
	
	public function __call($name, $args) {
		switch ($this->type) {
			case 'config' :
				return call_user_func_array ( array (CI::$ci->config, $name ), $args );
				break;
			
			case 'lang' :
				return call_user_func_array ( array (CI::$ci->lang, $name ), $args );
				break;
			
			case 'helper' :
			case 'plugin' :
				return call_user_func_array ( $name, $args );
				break;
		}
	}
}














