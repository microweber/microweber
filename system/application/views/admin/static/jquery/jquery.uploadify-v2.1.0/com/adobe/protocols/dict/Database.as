package com.adobe.protocols.dict
{
	public class Database
	{
		private var _name:String;
		private var _description:String;

		public function Database(name:String, description:String)
		{
			this._name = name;
			this._description = description;
		}

		public function set name(name:String):void
		{
			this._name = name;
		}

		public function get name():String
		{
			return this._name;
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