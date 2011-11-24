package com.adobe.protocols.dict
{
	public class Definition
	{
		private var _definition:String;
		private var _database:String;
		private var _term:String;
		
		public function set definition(definition:String):void
		{
			this._definition = definition;
		}
		
		public function get definition():String
		{
			return this._definition;
		}

		public function set database(database:String):void
		{
			this._database = database;
		}
		
		public function get database():String
		{
			return this._database;
		}

		public function set term(term:String):void
		{
			this._term = term;
		}
		
		public function get term():String
		{
			return this._term;
		}
	}
}