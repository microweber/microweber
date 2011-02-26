<?php
/**
 * A class that makes it easy to display calendars inside your app. This will show an entire month's calendar in a table
 *		and will call a user specified callback function for each day so that the user can print what is needed within
 *		the cell of that date.
 * Example:
 *	$cal = new Calender("daily");
 *	$cal->display();
 *	function daily($year, $month, $day) {
 *		print "Hello, $year-$month-$day!";
 *	}
 */
class Calendar {
	public $month;
	public $year;
	public $limit;
	public $link_template = "?year=%YEAR%&amp;month=%MONTH%"; /// The template that will be used to create the link to each month page
	
	private $_callback; /// The callback function that the user have to provide - this will be called for every day - from within the day's cell
	private $_callback_cell_class_provider;
	private $_month_days;
	
	/**
	 * Constructor
	 * Argument: $callback(function) - The user specified callback function that will be called for each day
	 *			$year - [OPTIONAL] The year that is to be shown - defaults to current year.
	 *			$month - [OPTIONAL] The month that is to be shown - defaults to current month.
	 */
	function __construct($callback = '', $year=0, $month=0) {
		if($callback) $this->setDailyFunction($callback);

		$this->month= (!empty($_REQUEST['month']))? intval($_REQUEST['month']): $month;	//Shows the calendar for this month
		$this->year	= (!empty($_REQUEST['year'])) ? intval($_REQUEST['year']) : $year;	// of this year.
		if(!$this->year)	$this->year = date('Y'); //This could be 0 if it the default argument the function - make it current year
		if(!$this->month)	$this->month = date('n');
		
		// Set the limit to which the calendar can go.
 		$this->limit = array(
 			//'from'	=> array('year'=>2000,		'month'=>1),
 			'to'	=> array('year'=>date('Y'),	'month'=>date('n'))	//This makes sure that future dates are not shown.
 		);
		
		//Validations
		if($this->month > 12)		$this->month=12;
		elseif($this->month < 1)	$this->month=1;
		
		$this->_month_days  = array(31,28,31,30,31,30,31,31,30,31,30,31);
		if($this->year%4 == 0) $this->_month_days[1] = 29;
	}
	
	/**
	 * The function to set the user-specified callback function.
	 * Argument: $callback(function) - The user specified callback function that will be called for each day
	 */
	function setDailyFunction($func) {
		$this->_callback = $func;
	}
	
	/**
	 * The user can use this function to set the class name of any valid date's cell(the td tag of that day). 
	 * 		The string returned by the user function will be added to the class of the cell.
	 */
	function setCellClassProvider($func) {
		$this->_callback_cell_class_provider = $func;
	}
	
	//////////////////////////////////////// Private Functions ///////////////////////////
	/**
	 * This function renders the stuff inside each day's cell. It also calls the user-specified callback function.
	 */
	private function _each_day() {
		static $flag = 0;
		static $column = 1;
		static $d = 1;
		
		$start_day = date('w',mktime(0,0,0,$this->month,1,$this->year)) + 1;//Get the first day of this month - 0 (for Sunday) through 6 (for Saturday)
		$curmonth = ($this->month>9) ? $this->month : '0'.$this->month;
	
		if($d > $this->_month_days[$this->month-1]) $flag=0;//If the days has overshot the number of days in this month, stop writing
		else if($column>=$start_day and !$flag) $flag=1;//If the first day of this month has come, start the date writing
		
		$class='';
		if($d == date('j') and $this->month == date('n') and $this->year == date('Y') and $flag) $class='today';
		if(!$flag) $class .= ' overflow-days';
		$weekdays = array('saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday');
		$class .= " weekday-" . $weekdays[($column%7)];
		
		//See if the user wants to tag any date with a class - 
		if(is_callable($this->_callback_cell_class_provider) and $flag) {
			$da = ($d<10) ? "0".$d : $d ;
			$func = $this->_callback_cell_class_provider;
			$class .= $func($this->year, $curmonth, $da);
		}
		print "<td class='$class days'>";
		
		if($flag) {
			$da = ($d<10) ? "0".$d : $d ;
			$full_day = "{$this->year}-{$curmonth}-$da";
	
			print "<span class='date-number'>$d</span>";//Print the date
	
			print "<div class='calendar-info'>";
			if(is_callable($this->_callback)) {
				$func = $this->_callback;
				$func($this->year, $curmonth, $da);
			}
			print "</div>";
	
			//Move to next day
			$d = $d + 1;
		} else {
			print "&nbsp;";
		}
		print "</td>";
		$column++;
		
		return $d;
	}
	
	/**
	 * Creates and returns the link to the given year, month calendar page. It uses the $link_template variable to create the link.
	 */
	private function _monthLink($year, $month) {
		if($month>12) {
			$month=1;
			$year++;
		}
		if($month<1) {
			$month=12;
			$year--;
		}
		$month = ($month>9) ? $month : '0'.$month;
		
		$link = str_replace(array("%YEAR%", "%MONTH%"), array($year, $month), $this->link_template);
		
		return $link;
	}
	
	/**
	 * The function that make sure that the limits are enforced in the calendar
	 */
	private function _withinLimit($year, $month) {
		if(!isset($this->limit['from']) and !isset($this->limit['to'])) return true; //No Limits
		
		if(isset($this->limit['from']['year'])		and $year < $this->limit['from']['year'])	return false;
		elseif(isset($this->limit['to']['year'])	and $year > $this->limit['to']['year'])		return false;
		elseif(isset($this->limit['from']['year'])	and isset($this->limit['from']['month'])	and
			$year == $this->limit['from']['year'] 	and $month < $this->limit['from']['month'])	return false;
		elseif(isset($this->limit['to']['year'])	and isset($this->limit['to']['month'])		and
			$year == $this->limit['to']['year']		and $month > $this->limit['to']['month'])	return false;
		
		return true;
	}
	
	/**
	 * If a limit is violated or an invalid date is given, this function is called.
	 */
	private function _error404() {
		header("HTTP/1.0 404 Not Found");
		exit;
	}
	
	//////////////////////////////////////////////// HTML Producing functions /////////////////////////////////////
	/**
	 * Creates the links in the top of the calendar table. This includes the '<< Last Year',  '&lt; LastMonth', Current month , 'Next Month >', 'Next Year >>'
	 * The links will be shown only if they are within the accepted limits.
	 */
	function printCalendarNavigationLinks() {
		//Get the names of some months - for the top part of the table.
		$last_month = t(date('F',mktime(0,0,0,$this->month-1,1,$this->year)));
		$this_month = t(date('F',mktime(0,0,0,$this->month,1,$this->year))) . ' ' . $this->year;
		$next_month = t(date('F',mktime(0,0,0,$this->month+1,1,$this->year)));
		?>
<tr><th><?php if($this->_withinLimit($this->year-1, $this->month)) { ?><a href="<?=$this->_monthLink($this->year-1, $this->month)?>">&lt;&lt; <?=$this->year-1?></a><?php } ?></th>
<th><?php if($this->_withinLimit($this->year, $this->month-1)) { ?><a href="<?=$this->_monthLink($this->year, $this->month-1)?>">&lt; <?=$last_month?></a><?php } ?></th>
<th colspan='3' style="text-align:center;"><?=$this_month?></th>
<th style="text-align:right;"><?php if($this->_withinLimit($this->year, $this->month+1)) { ?><a href="<?=$this->_monthLink($this->year, $this->month+1)?>"><?=$next_month?> &gt;</a><?php } ?></th>
<th style="text-align:right;"><?php if($this->_withinLimit($this->year+1, $this->month)) { ?><a href="<?=$this->_monthLink($this->year+1, $this->month)?>"><?=$this->year+1?> &gt;&gt;</a><?php } ?></th></tr>
		
		<?php
	}

	/**
	 * Prints out the Weekday names that are at the top of the table.
	 */
	function printDayNames() {
		print '<tr class=\'weekday-names\'><td>'.t('Sun').'</td><td>'.t('Mon').'</td><td>'.t('Tue').'</td><td>'.t('Wed').'</td><td>'.t('Thu').'</td><td>'.t('Fri').'</td><td>'.t('Sat').'</td></tr>';
	}
	
	/// Prints out the top of the table - calls printCalendarNavigationLinks() and printDayNames()
	function printTableStart() {
		?>
<table class='calendar'>
		<?php
		$this->printCalendarNavigationLinks();
		$this->printDayNames();
	}
	
	/**
	 * This function creates the table cells - It is a 5x7 nested loop that prints out the &lt;td> tags. It will call the each_day() in every cell. 
	 *	If the cell is for the current date, it will add a class 'today' to that cell.
	 */
	function printTableBody() {
		$d=0;
		for($i=0; $i<=5; $i++) { //5 Rows in the calendar
			$class = ($i%2) ? 'odd-row' : 'even-row';
			print "<tr class='$class'>";
			for($j=0;$j<7;$j++) { //7 Columns
				$d = $this->_each_day();
			}
			print "</tr>\n";
			if($d >= $this->_month_days[$this->month-1]) break;
		}
	}
	
	/// Print out the end of the table - "&lt;/table>"
	function printTableEnd() {
		print "</table>";
	}
	
	/// Prints the full table with just 1 call - this calls printTableStart(), printTableBody() and printTableEnd()
	function display() {
		if(!$this->_withinLimit($this->year, $this->month)) $this->_error404();
		
		$this->printTableStart();
		$this->printTableBody();
		$this->printTableEnd();
	}
	
}
