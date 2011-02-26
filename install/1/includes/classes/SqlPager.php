<?php
/**
 * This class make paging very simple - just provide it with the query to page, and it will do the rest. 
 *		It uses the LIMIT keyword to do this. Currently, it support only MySQL.
 * Example:
 *		$pager = new SqlPager("SELECT name FROM ${config['db_prefix']}Users WHERE status=1");
 *		print $pager->getLink("first") . ' | ' . $pager->getLink("previous");
 *		$pager->printPager();
 *		print $pager->getLink("next") . ' | ' . $pager->getLink("last");
 *		$this_page = $pager->getPage();
 */
class SqlPager {
	// Public Variables
	var $query;				/// The SQL Query to be paged.
	var $items_per_page;	/// The Number of items that should be shown in 1 page
	var $page;				/// The current page number
	var $total_items;		/// Total number of records
	var $total_pages;		/// Total number of pages
	var $page_link   = "";	/// The file that must be used while making links to pages.
	
	/**
	 * This is the template used to create links when the getLink() is called.
	 * %%PAGE_LINK%% - current page url & sp_page=PageNumber . Example: demo.php?param=value&sp_page=3
	 */
	var $link_template = '<a href="%%PAGE_LINK%%" class="page-%%CLASS%%">%%TEXT%%</a>';
	
	/**
	 * This is the template used to create links when the getStatus() is called. Possible substitutions...
	 * %%PAGE%%
	 * %%FROM%%
	 * %%TO%%
	 * %%TOTAL_PAGES%%
	 * %%TOTAL_ITEMS%%
	 */
	var $status_template = 'Viewing %%FROM%% - %%TO%% of %%TOTAL_ITEMS%%';
	
	///The current mode of the system. Possible values are 'd' = Development Mode, 't' = Testing Mode, 'p' = Production Mode
	var $mode = 'd';
	var $opt = array(
		'links_count'	=> 10, // If 0, shows all links
	);

	///The text used in the pager is customizable. HTML is allowed.
	var $text = array(
		'previous' => '&lt;' , 'next' => '&gt;', 		//Next and Previous Texts
		'first' => '&lt;&lt;', 'last' => '&gt;&gt;', 	//First and Last Links
		'current_page_indicator' => array(
			'left'	=> '[',
			'right'	=> ']'
		),
		'link_seperator' => " "							//The text between the paging links(Page numbers). 
	);

	///Private Variables
	var $_sql_resource;
	var $_pager_query;

	/**
	 * Constructor
	 * Argument : $query - The query that should be used for paging
	 * 			  $items_per_page - The default number of items per page - defaults to 10 [OPTIONAL]
	 */
	function SqlPager($query, $items_per_page = 10) {
		$query = preg_replace('/ LIMIT .+/i','',$query);//Remove the 'limitation' if there is one
		$this->query = $query;

		//Before anything, get all the necessary data.
		//Get the value of $items_per_page from the query or from the parameter
		$this->items_per_page = ($this->_getParam('sp_items_per_page')) ? 
					$this->_getParam('sp_items_per_page') : $items_per_page;

		//Get the current page number
		$this->page = ($this->_getParam('sp_page')) ? $this->_getParam('sp_page') : 1;
		
		$this->page_link = basename($_SERVER['PHP_SELF']);

		$offset = ($this->page - 1) * $this->items_per_page;
		$this->_pager_query = $query . " LIMIT $offset," . $this->items_per_page;
		
		global $sql;
		$total_items_sql = $sql->getSql($this->query);
		$this->total_items = $sql->fetchNumRows($total_items_sql);
		$this->total_pages = ceil($this->total_items / $this->items_per_page);
	}
	
	/**
	 * Returns the SQL resource of the pager.
	 * Return : The SQL resource of the pager.
	 */
	function getSql() {
		//:TODO: Use DB abstraction
		global $sql;
		$this->_sql_resource = $sql->getSql($this->_pager_query);

		return $this->_sql_resource;
	}
	
	/**
	 * Returns all the items for one page in an array.
	 * Returns : All the items for one page in a list.
	 */
	function getPage() {
		global $sql;
		$resource = $this->getSql();
		$result = array();
		while($row = $sql->fetchAssoc($resource)) {
			$result[] = $row;
		}
		return $result;
	}

	//////////////////////////////////// Functions that print ///////////////////////////////////////
	/**
	 * Prints the pager. Shows links to pages as numbers - except for the current page. The number of links to be shown
	 *		is decided by the $opt['links_count'] member variable. If that is 0, all links are shown. If its 5, then 
	 *		5 links are shown.
	 */
	function printPager($return_data = false) {
		if($this->total_pages == 1) return; //Just 1 page - don't need pager.
		
		$from = 1;
		$to = $this->total_pages;

		// Decides many page numbers should be shown
		if($this->opt['links_count']) {
			$difference = intval($this->opt['links_count'] / 2);
			$from = $this->page - $difference + 1;
			$to = $this->page + $difference;

			//Make sure the numbers are in range.
			if($from < 1) {
				//The numbers that cannot be put in the right side can be put in the left side
				$to = $to + (-$from);//$from is negetive
				$from = 1;
			}
			if($to > $this->total_pages) {
				$from = $from - ($to - $this->total_pages);
				$to = $this->total_pages;
				if($from < 1) $from = 1;
			}
		}

		$to_print = '';
		for($i = $from; $i <= $to; $i++) {
			if($i == $this->page) { //Current Page
				$to_print .= $this->text['current_page_indicator']['left'] . $i . $this->text['current_page_indicator']['right'];
			} else {
				$to_print .= '<a class="sp-page-number" href="' . $this->getLinkToPage($i) . '">'.$i."</a>";
			}
			$to_print .= $this->text['link_seperator'];
		}
		
		if($return_data) return $to_print;
		else print $to_print;
	}
	
	/**
	 * Returns the link to the page of the given page number
	 * Argument: $page - The page number of the page of which's link you want.
	 * Return: The url of the page - index.php?sp_page=2
	 */
	function getLinkToPage($page) {
		if($page > $this->total_pages) return '';
		
		return $this->_getLinkParameters($this->page_link,array('sp_page'=>$page));
	}
	
	/**
	 * Return the Next/Previous and First/Last Link based on the argument. The link will be formated 
	 *		using the $link_template member variable of this class.
	 * Arguments  : $dir(String) - the direction of the link you want. Possible values are...
     *					- 'next' get the next link 
     *					- 'previous' get the previous link.
	 *					- 'first' link to the first 
	 *					- 'last' links to last page.	
	 * Returns: The HTML of the link - based on $link_template member variable
	 */
	function getLink($dir) {
		$dir = strtolower($dir);

		//We just make the template here. Depending on the value of '$dir' the values to be inserted in 
		//		the place of the replacement texts(like %%PAGE%%) will change.
		$replace_these = array("%%PAGE%%","%%CLASS%%","%%TEXT%%", "%%PAGE_LINK%%"); //Stuff to replace

		if($dir === "previous" or $dir === "back" or $dir === 'prev') { //Get the back link
			$back = $this->page-1;
			$back = ($back > 0) ? $back : 1; //Never let the value of $back be lesser than 1(first page)
			
			$with_these = array($back,"previous",$this->text['previous']);

		} elseif($dir === "next" or $dir === "forward") { //Get the forward link
			$next = $this->page+1;
			$next = ($next > $this->total_pages) ? $this->total_pages : $next; //Never let the value of $next go beyond the total number of pages
			
			$with_these = array($next,"next",$this->text['next']);
 		
 		} elseif($dir === "first" or $dir === "start" or $dir === 'beginning') { //Get the first link
			$with_these = array(1,"first",$this->text['first']);

		} elseif($dir === "last" or $dir === "end") { //Get the last link.
			$with_these = array($this->total_pages,"last",$this->text['last']);
		}
		
		$page_link = $this->_getLinkParameters($this->page_link, array('sp_page'=> $with_these[0]));
		array_push($with_these, $page_link); //Add the page link at the beginning of the array.
		$link = str_replace($replace_these, $with_these, $this->link_template); //Replace the texts
		
		return $link;
	}
	
	/**
	 * Create and return a link with the parameters given as the function argument.
	 */
	function makeLink($params) {
		return $this->_getLinkParameters($this->page_link, $params);
	}

	//////////////////////////////////////// HTML/JavaScript Blocks ///////////////////////////////////
	/**
	 * Returns the status of the current page. Use the $status_template member variable as a template.
	 */
	function getStatus() {
		$from = ($this->page * $this->items_per_page) - $this->items_per_page + 1;
		$to = $from + $this->items_per_page - 1;
		$to = ($to > $this->total_items) ? $this->total_items : $to;
		
		$replace_these = array('%%FROM%%', '%%TO%%', '%%TOTAL_ITEMS%%', '%%PAGE%%', '%%TOTAL_PAGES%%');
		$with_these = array($from, $to, $this->total_items, $this->page, $this->total_pages);
		$status = str_replace($replace_these, $with_these, $this->status_template);
		
		return $status;
	}

	/**
	 * Shows a Dropdown menu with values 5,10,20, 25, 50, 100. On selecting one option, that much items will be
	 *	shown in one page.
	 */
	function printItemsPerPageDropDown() {
		$page_link = $this->_getLinkParameters($this->page_link, array('sp_items_per_page'=>'__ITEMS_PER_PAGE__','sp_page'=>1));//Go to 1st page when items_per_page is changed
		?>
<script language="javascript" type="text/javascript">
function itemsPerPageChooser(number) {
	var url = "<?=$page_link?>";
	url = url.replace(/__ITEMS_PER_PAGE__/g,number);
	url = url.replace(/\&amp;/g,"&");

	document.location.href = url;
	return false;
}
</script>

<form name="item_per_page_dropdown" onSubmit="return itemsPerPageChooser(document.getElementById('sp_items_per_page').value)" action="">
<?php $this->_printHiddenFields(array('sp_items_per_page', 'sp_page', 'action')); ?>
Show <select name="sp_items_per_page" id="sp_items_per_page" onChange="itemsPerPageChooser(this.value)"> 
		<?php
		$values = array(5, 10, 20, 25, 50, 100);
		foreach($values as $i) {
			$sel="";
			if($i == $this->items_per_page) $sel = "selected='selected'"; //Make sure the current page is selected.
			print "	<option value='$i'$sel>$i</option>";
		}
		?></select> items per page.<input type="submit" name="action" value="Go" />
</form>
		<?php
	}
	
	/**
	 * Input box based navigation. Shows a Input box where the users can type in a page number to go 
	 * 	to that page number. Very useful for sites with a large number of pages.
	 */
	function printGoToInput() {
		$size = 4;
		if($this->total_pages < 10) $size = 1;
		else if($this->total_pages < 100) $size = 2;
		else if($this->total_pages < 1000) $size = 3;
		
		$this->_printGoToPageJsFunction();
		?>
<form onSubmit="return goToPage(this.sp_page.value)" action="">
<?php $this->_printHiddenFields(array('sp_page', 'action')); ?>
<?= t('Page')?> <input type="text" name="sp_page" size="<?=$size?>" value="<?=$this->page?>" />
<input type="submit" name="action" value="<?= t('Go')?>" /> <?=t('of %d',$this->total_pages)?>
</form>
<?php
	}
	
	/**
	 * This function displays a dropdown box with all pages. Selecting a different page jumps to that page.
	 */
	function printGoToDropDown() {
		$this->_printGoToPageJsFunction();
		?>
<form onSubmit="return goToPage(this.sp_page.value)" action="">
		<?php $this->_printHiddenFields(array('sp_page', 'action')); ?>
<?= t('Page')?> <select name="sp_page" onChange="goToPage(this.value);">
<?php
		for($i=1; $i<=$this->total_pages; $i++) {
			print "<option value='$i'";
			if($i==$this->page) print " selected='selected'";
			print ">$i</option>";
		}
?>
</select><input type="submit" name="action" value="<?= t('Go')?>" />
</form>
		<?php
	}
	
	/**
	 * This creates hidden field for all query fields except for the ones specified in the argument.
	 */
	function _printHiddenFields($except = array()) {
		$all_request = $_GET+$_POST; // $_REQUEST has session, cookie info- we don't want that.
		foreach($all_request as $key=>$value) {
			if(!in_array($key, $except)) {
				print "<input type='hidden' name='$key' value='$value' />\n";
			}
		}
	}
	
	/**
	 * Creates a goToPage javascript function. This is used by 2 different methods - 
	 *		the printGoToInput() and printGoToDropDown(). I am moving it to its own method
	 *		to make sure it appears only once.
	 */
	function _printGoToPageJsFunction() {
		static $shown = 0;
		if($shown) return; //Makes sure this script is inserted only once
		
		$shown = 1;
		$page_link = $this->_getLinkParameters($this->page_link, array('sp_page'=>'__PAGE__'));
		?>
<script language="javascript" type="text/javascript">
function goToPage(page) {
	var url = "<?=$page_link?>";
	url = url.replace(/__PAGE__/g,page);
	url = url.replace(/\&amp;/g,"&");
	
	if(page > <?=$this->total_pages?> || page < 1) alert(<?=t('Invalid page number. Please use a number between 1 and %d',$this->total_pages)?>");
	else document.location.href = url;
	return false;
}
</script>
		<?php
	}

	//////////////////////////////////////// Private Functions ///////////////////////////////////////
	
	/**
	 * Returns the User input from $_REQUEST after escaping it using mysql_real_escape_string()
	 * Argument : $string - Parameter name
	 * Return : Parameter value, escaped.
	 */
	function _getParam($string) {
		if(!isset($_REQUEST[$string])) return '';
		if(is_array($_REQUEST[$string])) return $_REQUEST[$string];
		return addslashes($_REQUEST[$string]);
	}
	
	/**
	 * Adds the given parameters to the given URL are retuns the result. The returned URL will be XHTML complient
	 * Argument : $url - The URL of the page.
	 *			  $params(Array) - An associative array holding the parameters that should be added to the URL. 
	 * Example : _getLinkParameters('index.php?user=1',array('sp_page'=>7,'sp_items_per_page'=>5))
	 */
	function _getLinkParameters($url , $params = array()) {
		$use_existing_arguments = true;
		if($use_existing_arguments) $params = $params + $_GET;
		
		if(!$params) return $url;
		$link = $url;
		if(strpos($link,'?') === false) $link .= '?'; //If there is no '?' add one at the end
		elseif(!preg_match('/(\?|\&(amp;)?)$/',$link)) $link .= '&amp;'; //If there is no '&' at the END, add one.
		
		$params_arr = array();
		foreach($params as $key=>$value) {
			if(gettype($value) == 'array') { //Handle array data properly
				foreach($value as $val) {
					$params_arr[] = $key . '[]=' . urlencode($val);
				}
			} else {
				$params_arr[] = $key . '=' . urlencode($value);
			}
		}
		$link .= implode('&amp;',$params_arr);
		return $link;
	}

	/**
	 * Shows an error message based on mode.
	 */
	function _error($message) {
		if($this->mode == 'd') die($message);
		elseif($this->mode == 't') print($message);
	}
}
