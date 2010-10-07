package com.adobe.protocols.dict.events
{
	import flash.events.Event;
	import com.adobe.protocols.dict.Dict;

	public class DictionaryServerEvent
		extends Event
	{
		private var _servers:Array;
		
		public function DictionaryServerEvent()
		{
			super(Dict.SERVERS);
		}
		
		public function set servers(servers:Array):void
		{
			this._servers = servers;
		}
		
		public function get servers():Array
		{
			return this._servers;
		}
	}
}