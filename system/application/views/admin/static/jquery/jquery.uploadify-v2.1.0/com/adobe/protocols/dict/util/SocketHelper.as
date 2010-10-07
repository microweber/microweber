package com.adobe.protocols.dict.util
{
	import com.adobe.net.proxies.RFC2817Socket;
	import flash.events.ProgressEvent;

	public class SocketHelper
		extends RFC2817Socket
	{
		private var terminator:String = "\r\n.\r\n";
		private var buffer:String;
		public static var COMPLETE_RESPONSE:String = "completeResponse";

		public function SocketHelper()
		{
			super();
			buffer = new String();
			addEventListener(ProgressEvent.SOCKET_DATA, incomingData);
		}

		private function incomingData(event:ProgressEvent):void
		{
			buffer += readUTFBytes(bytesAvailable);
			buffer = buffer.replace(/250[^\r\n]+\r\n/, ""); // Get rid of all 250s. Don't need them.
			var codeStr:String = buffer.substring(0, 3);
			if (!isNaN(parseInt(codeStr)))
			{
				var code:uint = uint(codeStr);
				if (code == 150 || code >= 200)
				{
					buffer = buffer.replace("\r\n", this.terminator);
				}
			}

			while (buffer.indexOf(this.terminator) != -1)
			{
				var chunk:String = buffer.substring(0, buffer.indexOf(this.terminator));
				buffer = buffer.substring(chunk.length + this.terminator.length, buffer.length);
				throwResponseEvent(chunk);
			}
		}
		
		private function throwResponseEvent(response:String):void
		{
			var responseEvent:CompleteResponseEvent = new CompleteResponseEvent();
			responseEvent.response = response;
			dispatchEvent(responseEvent);			
		}
	}
}