package com.adobe.protocols.dict.events
{
	import flash.events.Event;
	import com.adobe.protocols.dict.Dict;

	public class DisconnectedEvent extends Event
	{
		public function DisconnectedEvent()
		{
			super(Dict.DISCONNECTED);
		}
		
	}
}