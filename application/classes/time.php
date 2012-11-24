<?php
// Extends the DateTime class to make it easier to caculate time differences and
// display human-readable representations
class Time extends DateTime {
	function __construct($time = 'NOW', DateTimeZone $timezone = NULL) {
		if (is_int ( $time ) || ctype_digit ( $time ))
			$time = "@$time";
		if (is_array ( $time ))
			$time = self::fromArray ( $time );
		if ($timezone)
			parent::__construct ( $time, $timezone );
		else
			parent::__construct ( $time );
	}
	function diff($now = 'NOW', $absolute = FALSE) {
		if (! ($now instanceof DateTime))
			$now = new Time ( $now );
		return parent::diff ( $now, $absolute );
	}
	function getSQL() {
		return $this->format ( 'Y-m-d H:i:s' );
	}
	function difference($d = 'NOW', $l = 1) {
		$d = $this->diff ( $d );
		$u = array (
				'y' => 'year',
				'm' => 'month',
				'd' => 'day',
				'h' => 'hour',
				'i' => 'minute',
				's' => 'second' 
		);
		$r = array ();
		foreach ( $u as $k => $n ) {
			$v = $d->$k;
			if ($v)
				$r [] = "$v $n" . ($v > 1 ? 's' : '');
			if (count ( $r ) == $l)
				return implode ( ',', $r );
		}
	}
	function humanFriendly($format = 'M j,Y \a\t g:ia') {
		$d = $this->diff ();
		$t = $this->getTimestamp ();
		if (! $d->d) {
			$s = $this->difference ();
			return $t > time () ? "in $s" : "$s ago";
		}
		return $this->format ( $format );
	}
	public static function show($time) {
		$t = new Time ( $time );
		return $t->humanFriendly ();
	}
}
