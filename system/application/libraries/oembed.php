<?php

if (! function_exists ( 'shortcode_parse_atts' )) {
	/**
	 * Retrieve all attributes from the shortcodes tag.
	 *
	 * The attributes list has the attribute name as the key and the value of the
	 * attribute as the value in the key/value pair. This allows for easier
	 * retrieval of the attributes, since all attributes have to be known.
	 *
	 * @since 2.5
	 *
	 * @param string $text
	 * @return array List of attributes and their value.
	 */
	function shortcode_parse_atts($text) {
		$atts = array ();
		$pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
		$text = preg_replace ( "/[\x{00a0}\x{200b}]+/u", " ", $text );
		if (preg_match_all ( $pattern, $text, $match, PREG_SET_ORDER )) {
			foreach ( $match as $m ) {
				if (! empty ( $m [1] ))
					$atts [strtolower ( $m [1] )] = stripcslashes ( $m [2] );
				elseif (! empty ( $m [3] ))
					$atts [strtolower ( $m [3] )] = stripcslashes ( $m [4] );
				elseif (! empty ( $m [5] ))
					$atts [strtolower ( $m [5] )] = stripcslashes ( $m [6] );
				elseif (isset ( $m [7] ) and strlen ( $m [7] ))
					$atts [] = stripcslashes ( $m [7] );
				elseif (isset ( $m [8] ))
					$atts [] = stripcslashes ( $m [8] );
			}
		} else {
			$atts = ltrim ( $text );
		}
		return $atts;
	}
}

if (! function_exists ( 'wp_parse_str' )) :
	/**
	 * Parses a string into variables to be stored in an array.
	 *
	 * Uses {@link http://www.php.net/parse_str parse_str()} and stripslashes if
	 * {@link http://www.php.net/magic_quotes magic_quotes_gpc} is on.
	 *
	 * @since 2.2.1
	 * @uses apply_filters() for the 'wp_parse_str' filter.
	 *
	 * @param string $string The string to be parsed.
	 * @param array $array Variables will be stored in this array.
	 */
	function wp_parse_str($string, &$array) {
		parse_str ( $string, $array );
		if (get_magic_quotes_gpc ())
			$array = stripslashes_deep ( $array );
		$array = apply_filters ( 'wp_parse_str', $array );
	}


endif;
if (! function_exists ( 'add_query_arg' )) :
	/**
	 * Retrieve a modified URL query string.
	 *
	 * You can rebuild the URL and append a new query variable to the URL query by
	 * using this function. You can also retrieve the full URL with query data.
	 *
	 * Adding a single key & value or an associative array. Setting a key value to
	 * emptystring removes the key. Omitting oldquery_or_uri uses the $_SERVER
	 * value.
	 *
	 * @since 1.5.0
	 *
	 * @param mixed $param1 Either newkey or an associative_array
	 * @param mixed $param2 Either newvalue or oldquery or uri
	 * @param mixed $param3 Optional. Old query or uri
	 * @return string New URL query string.
	 */
	function add_query_arg() {
		$ret = '';
		if (is_array ( func_get_arg ( 0 ) )) {
			if (@func_num_args () < 2 || false === @func_get_arg ( 1 ))
				$uri = $_SERVER ['REQUEST_URI'];
			else
				$uri = @func_get_arg ( 1 );
		} else {
			if (@func_num_args () < 3 || false === @func_get_arg ( 2 ))
				$uri = $_SERVER ['REQUEST_URI'];
			else
				$uri = @func_get_arg ( 2 );
		}
		
		if ($frag = strstr ( $uri, '#' ))
			$uri = substr ( $uri, 0, - strlen ( $frag ) );
		else
			$frag = '';
		
		if (preg_match ( '|^https?://|i', $uri, $matches )) {
			$protocol = $matches [0];
			$uri = substr ( $uri, strlen ( $protocol ) );
		} else {
			$protocol = '';
		}
		
		if (strpos ( $uri, '?' ) !== false) {
			$parts = explode ( '?', $uri, 2 );
			if (1 == count ( $parts )) {
				$base = '?';
				$query = $parts [0];
			} else {
				$base = $parts [0] . '?';
				$query = $parts [1];
			}
		} elseif (! empty ( $protocol ) || strpos ( $uri, '=' ) === false) {
			$base = $uri . '?';
			$query = '';
		} else {
			$base = '';
			$query = $uri;
		}
		
		wp_parse_str ( $query, $qs );
		$qs = urlencode_deep ( $qs ); // this re-URL-encodes things that were already in the query string
		if (is_array ( func_get_arg ( 0 ) )) {
			$kayvees = func_get_arg ( 0 );
			$qs = array_merge ( $qs, $kayvees );
		} else {
			$qs [func_get_arg ( 0 )] = func_get_arg ( 1 );
		}
		
		foreach ( ( array ) $qs as $k => $v ) {
			if ($v === false)
				unset ( $qs [$k] );
		}
		
		$ret = build_query ( $qs );
		$ret = trim ( $ret, '?' );
		$ret = preg_replace ( '#=(&|$)#', '$1', $ret );
		$ret = $protocol . $base . $ret . $frag;
		$ret = rtrim ( $ret, '?' );
		return $ret;
	}

endif;
if (! function_exists ( 'wp_parse_args' )) :
	/**
	 * Merge user defined arguments into defaults array.
	 *
	 * This function is used throughout WordPress to allow for both string or array
	 * to be merged into another array.
	 *
	 * @since 2.2.0
	 *
	 * @param string|array $args Value to merge with $defaults
	 * @param array $defaults Array that serves as the defaults.
	 * @return array Merged user defined values with defaults.
	 */
	function wp_parse_args($args, $defaults = '') {
		if (is_object ( $args ))
			$r = get_object_vars ( $args );
		elseif (is_array ( $args ))
			$r = & $args;
		else
			wp_parse_str ( $args, $r );
		
		if (is_array ( $defaults ))
			return array_merge ( $defaults, $r );
		return $r;
	}


endif;

class oembed {
	var $providers = array ();
	
	/**
	 * PHP4 constructor
	 */
	function oembed() {
		return $this->__construct ();
	}
	
	/**
	 * PHP5 constructor
	 *
	 * @uses apply_filters() Filters a list of pre-defined oEmbed providers.
	 */
	function __construct() {
		// List out some popular sites that support oEmbed.
		// The WP_Embed class disables discovery for non-unfiltered_html users, so only providers in this array will be used for them.
		// Add to this list using the wp_oembed_add_provider() function (see it's PHPDoc for details).
		$this->providers = $this->apply_filters ( 'oembed_providers', array ('#http://(www\.)?youtube.com/watch.*#i' => array ('http://www.youtube.com/oembed', true ), 'http://youtu.be/*' => array ('http://www.youtube.com/oembed', false ), 'http://blip.tv/file/*' => array ('http://blip.tv/oembed/', false ), '#http://(www\.)?vimeo\.com/.*#i' => array ('http://www.vimeo.com/api/oembed.{format}', true ), '#http://(www\.)?dailymotion\.com/.*#i' => array ('http://www.dailymotion.com/api/oembed', true ), '#http://(www\.)?flickr\.com/.*#i' => array ('http://www.flickr.com/services/oembed/', true ), '#http://(.+)?smugmug\.com/.*#i' => array ('http://api.smugmug.com/services/oembed/', true ), '#http://(www\.)?hulu\.com/watch/.*#i' => array ('http://www.hulu.com/api/oembed.{format}', true ), '#http://(www\.)?viddler\.com/.*#i' => array ('http://lab.viddler.com/services/oembed/', true ), 'http://qik.com/*' => array ('http://qik.com/api/oembed.{format}', false ), 'http://revision3.com/*' => array ('http://revision3.com/api/oembed/', false ), 'http://i*.photobucket.com/albums/*' => array ('http://photobucket.com/oembed', false ), 'http://gi*.photobucket.com/groups/*' => array ('http://photobucket.com/oembed', false ), '#http://(www\.)?scribd\.com/.*#i' => array ('http://www.scribd.com/services/oembed', true ), 'http://wordpress.tv/*' => array ('http://wordpress.tv/oembed/', false ), '#http://(answers|surveys)\.polldaddy.com/.*#i' => array ('http://polldaddy.com/oembed/', true ), '#http://(www\.)?funnyordie\.com/videos/.*#i' => array ('http://www.funnyordie.com/oembed', true ) ) );
		
		// Fix any embeds that contain new lines in the middle of the HTML which breaks wpautop().
		add_filter ( 'oembed_dataparse', array (&$this, '_strip_newlines' ), 10, 3 );
	}
	
	/**
	 * The do-it-all function that takes a URL and attempts to return the HTML.
	 *
	 * @see WP_oEmbed::discover()
	 * @see WP_oEmbed::fetch()
	 * @see WP_oEmbed::data2html()
	 *
	 * @param string $url The URL to the content that should be attempted to be embedded.
	 * @param array $args Optional arguments. Usually passed from a shortcode.
	 * @return bool|string False on failure, otherwise the UNSANITIZED (and potentially unsafe) HTML that should be used to embed.
	 */
	function get_html($url, $args = '') {
		$provider = false;
		
		if (! isset ( $args ['discover'] ))
			$args ['discover'] = true;
		
		foreach ( $this->providers as $matchmask => $data ) {
			list ( $providerurl, $regex ) = $data;
			
			// Turn the asterisk-type provider URLs into regex
			if (! $regex)
				$matchmask = '#' . str_replace ( '___wildcard___', '(.+)', preg_quote ( str_replace ( '*', '___wildcard___', $matchmask ), '#' ) ) . '#i';
			
			if (preg_match ( $matchmask, $url )) {
				$provider = str_replace ( '{format}', 'json', $providerurl ); // JSON is easier to deal with than XML
				break;
			}
		}
		
		if (! $provider && $args ['discover'])
			$provider = $this->discover ( $url );
		
		if (! $provider || false === $data = $this->fetch ( $provider, $url, $args ))
			return false;
		
		return apply_filters ( 'oembed_result', $this->data2html ( $data, $url ), $url, $args );
	}
function apply_filters($tag, $value) {
	global $wp_filter, $merged_filters, $wp_current_filter;

	$args = array();
	$wp_current_filter[] = $tag;

	// Do 'all' actions first
	if ( isset($wp_filter['all']) ) {
		$args = func_get_args();
		//_wp_call_all_hook($args);
	}

	if ( !isset($wp_filter[$tag]) ) {
		array_pop($wp_current_filter);
		return $value;
	}

	// Sort
	if ( !isset( $merged_filters[ $tag ] ) ) {
		ksort($wp_filter[$tag]);
		$merged_filters[ $tag ] = true;
	}

	reset( $wp_filter[ $tag ] );

	if ( empty($args) )
		$args = func_get_args();

	do {
		foreach( (array) current($wp_filter[$tag]) as $the_ )
			if ( !is_null($the_['function']) ){
				$args[1] = $value;
				$value = call_user_func_array($the_['function'], array_slice($args, 1, (int) $the_['accepted_args']));
			}

	} while ( next($wp_filter[$tag]) !== false );

	array_pop( $wp_current_filter );

	return $value;
}
	/**
	 * Attempts to find oEmbed provider discovery <link> tags at the given URL.
	 *
	 * @param string $url The URL that should be inspected for discovery <link> tags.
	 * @return bool|string False on failure, otherwise the oEmbed provider URL.
	 */
	function discover($url) {
		$providers = array ();
		
		// Fetch URL content
		if ($html = (file_get_contents ( $url ))) {
			
			// <link> types that contain oEmbed provider URLs
			$linktypes = $this->apply_filters ( 'oembed_linktypes', array ('application/json+oembed' => 'json', 'text/xml+oembed' => 'xml', 'application/xml+oembed' => 'xml' ) ); // Incorrect, but used by at least Vimeo
			

			// Strip <body>
			$html = substr ( $html, 0, stripos ( $html, '</head>' ) );
			
			// Do a quick check
			$tagfound = false;
			foreach ( $linktypes as $linktype => $format ) {
				if (stripos ( $html, $linktype )) {
					$tagfound = true;
					break;
				}
			}
			
			if ($tagfound && preg_match_all ( '/<link([^<>]+)>/i', $html, $links )) {
				foreach ( $links [1] as $link ) {
					$atts = shortcode_parse_atts ( $link );
					
					if (! empty ( $atts ['type'] ) && ! empty ( $linktypes [$atts ['type']] ) && ! empty ( $atts ['href'] )) {
						$providers [$linktypes [$atts ['type']]] = $atts ['href'];
						
						// Stop here if it's JSON (that's all we need)
						if ('json' == $linktypes [$atts ['type']])
							break;
					}
				}
			}
		}
		
		// JSON is preferred to XML
		if (! empty ( $providers ['json'] ))
			return $providers ['json'];
		elseif (! empty ( $providers ['xml'] ))
			return $providers ['xml'];
		else
			return false;
	}
	
	/**
	 * Connects to a oEmbed provider and returns the result.
	 *
	 * @param string $provider The URL to the oEmbed provider.
	 * @param string $url The URL to the content that is desired to be embedded.
	 * @param array $args Optional arguments. Usually passed from a shortcode.
	 * @return bool|object False on failure, otherwise the result in the form of an object.
	 */
	function fetch($provider, $url, $args = '') {
		$args = wp_parse_args ( $args, wp_embed_defaults () );
		
		$provider = add_query_arg ( 'maxwidth', $args ['width'], $provider );
		$provider = add_query_arg ( 'maxheight', $args ['height'], $provider );
		$provider = add_query_arg ( 'url', urlencode ( $url ), $provider );
		
		foreach ( array ('json', 'xml' ) as $format ) {
			$result = $this->_fetch_with_format ( $provider, $format );
			if (is_wp_error ( $result ) && 'not-implemented' == $result->get_error_code ())
				continue;
			return ($result && ! is_wp_error ( $result )) ? $result : false;
		}
		return false;
	}
	
	/**
	 * Fetches result from an oEmbed provider for a specific format and complete provider URL
	 *
	 * @since 3.0.0
	 * @access private
	 * @param string $provider_url_with_args URL to the provider with full arguments list (url, maxheight, etc.)
	 * @param string $format Format to use
	 * @return bool|object False on failure, otherwise the result in the form of an object.
	 */
	function _fetch_with_format($provider_url_with_args, $format) {
		$provider_url_with_args = add_query_arg ( 'format', $format, $provider_url_with_args );
		$response = wp_remote_get ( $provider_url_with_args );
		if (501 == wp_remote_retrieve_response_code ( $response ))
			return new WP_Error ( 'not-implemented' );
		if (! $body = wp_remote_retrieve_body ( $response ))
			return false;
		$parse_method = "_parse_$format";
		return $this->$parse_method ( $body );
	}
	
	/**
	 * Parses a json response body.
	 *
	 * @since 3.0.0
	 * @access private
	 */
	function _parse_json($response_body) {
		return (($data = json_decode ( trim ( $response_body ) )) && is_object ( $data )) ? $data : false;
	}
	
	/**
	 * Parses an XML response body.
	 *
	 * @since 3.0.0
	 * @access private
	 */
	function _parse_xml($response_body) {
		if (function_exists ( 'simplexml_load_string' )) {
			$errors = libxml_use_internal_errors ( 'true' );
			$data = simplexml_load_string ( $response_body );
			libxml_use_internal_errors ( $errors );
			if (is_object ( $data ))
				return $data;
		}
		return false;
	}
	
	/**
	 * Converts a data object from {@link WP_oEmbed::fetch()} and returns the HTML.
	 *
	 * @param object $data A data object result from an oEmbed provider.
	 * @param string $url The URL to the content that is desired to be embedded.
	 * @return bool|string False on error, otherwise the HTML needed to embed.
	 */
	function data2html($data, $url) {
		if (! is_object ( $data ) || empty ( $data->type ))
			return false;
		
		switch ($data->type) {
			case 'photo' :
				if (empty ( $data->url ) || empty ( $data->width ) || empty ( $data->height ))
					return false;
				
				$title = (! empty ( $data->title )) ? $data->title : '';
				$return = '<a href="' . esc_url ( $url ) . '"><img src="' . esc_url ( $data->url ) . '" alt="' . esc_attr ( $title ) . '" width="' . esc_attr ( $data->width ) . '" height="' . esc_attr ( $data->height ) . '" /></a>';
				break;
			
			case 'video' :
			case 'rich' :
				$return = (! empty ( $data->html )) ? $data->html : false;
				break;
			
			case 'link' :
				$return = (! empty ( $data->title )) ? '<a href="' . esc_url ( $url ) . '">' . esc_html ( $data->title ) . '</a>' : false;
				break;
			
			default :
				$return = false;
		}
		
		// You can use this filter to add support for custom data types or to filter the result
		return apply_filters ( 'oembed_dataparse', $return, $data, $url );
	}
	
	/**
	 * Strip any new lines from the HTML.
	 *
	 * @access private
	 * @param string $html Existing HTML.
	 * @param object $data Data object from WP_oEmbed::data2html()
	 * @param string $url The original URL passed to oEmbed.
	 * @return string Possibly modified $html
	 */
	function _strip_newlines($html, $data, $url) {
		if (false !== strpos ( $html, "\n" ))
			$html = str_replace ( array ("\r\n", "\n" ), '', $html );
		
		return $html;
	}
}

?>