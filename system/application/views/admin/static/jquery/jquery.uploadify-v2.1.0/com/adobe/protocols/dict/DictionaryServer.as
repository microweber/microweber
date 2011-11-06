package com.adobe.protocols.dict
{
	public class DictionaryServer
	{
		private var _server:String;
		private var _description:String;

		public function set server(server:String):void
		{
			this._server = server;
		}
		
		public function get server():String
		{
			return this._server;
		}

		public function set description(description:String):void
		{
			this._description = description;
		}
		
		public function get description():String
		{
			return this._description;
		}
	}
}