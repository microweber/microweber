package com.adobe.protocols.dict.util
{
	import flash.events.Event;

	public class CompleteResponseEvent
		extends Event
	{
		private var _response:String;

		public function CompleteResponseEvent()
		{
			super(SocketHelper.COMPLETE_RESPONSE);
		}

		public function set response(response:String):void
		{
			this._response = response;
		}
		
		public function get response():String
		{
			return this._response;
		}
	}
}