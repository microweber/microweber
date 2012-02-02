package com.adobe.protocols.dict.events
{
	import flash.events.Event;
	import com.adobe.protocols.dict.Dict;

	public class MatchEvent
		extends Event
	{
		private var _matches:Array;
		
		public function MatchEvent()
		{
			super(Dict.MATCH);
		}
		
		public function set matches(matches:Array):void
		{
			this._matches = matches;
		}
		
		public function get matches():Array
		{
			return this._matches;
		}
	}
}