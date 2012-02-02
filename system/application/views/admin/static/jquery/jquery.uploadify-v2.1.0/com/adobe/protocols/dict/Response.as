package com.adobe.protocols.dict
{
	public class Response
	{
		private var _code:uint;
		private var _headerText:String;
		private var _body:String;

		public function set code(code:uint):void
		{
			this._code = code;
		}

		public function set headerText(headerText:String):void
		{
			this._headerText = headerText;
		}

		public function set body(body:String):void
		{
			this._body = body;
		}

		public function get code():uint
		{
			return this._code;
		}

		public function get headerText():String
		{
			return this._headerText;
		}

		public function get body():String
		{
			return this._body;
		}
	}
}