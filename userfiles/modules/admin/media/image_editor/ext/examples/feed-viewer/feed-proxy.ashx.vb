Imports System.Web
Imports System.Web.Services
Imports System
Imports System.IO
Imports System.Net

Public Class feedproxy1
    Implements System.Web.IHttpHandler

  Sub ProcessRequest(ByVal context As HttpContext) Implements IHttpHandler.ProcessRequest

    'Address of URL
    'Dim URL As String = "http://www.jackslocum.com/yui/feed/"
    Dim URL As String = context.Request.Form("feed")
    'Only allow http:// prefix
    If IsNothing(URL) Then
      Exit Sub
    End If
    If URL.Substring(0, 7) = "http://" Then
      Try
        'Dim enc As Encoding = Encoding.GetEncoding("UTF-8")
        Dim enc As Encoding = Encoding.GetEncoding("ISO-8859-1")

        Dim request As HttpWebRequest = WebRequest.Create(URL)
        Dim response As HttpWebResponse = request.GetResponse()
        Dim reader As StreamReader = New StreamReader(response.GetResponseStream(), enc)
        Dim str As String '= reader.ReadLine()
        Dim reply As String = ""
        'Do While str.Length > 0 And Not reader.EndOfStream
        Do While Not reader.EndOfStream
          str = reader.ReadLine()
          reply &= str & vbCrLf
          'Console.WriteLine(str)
        Loop
        context.Response.ContentType = "text/xml"
        context.Response.ContentEncoding = enc
        context.Response.Cache.SetExpires(DateTime.Now.AddSeconds(60))
        context.Response.Cache.SetCacheability(HttpCacheability.Public)

        context.Response.Write(reply)
      Catch ex As Exception
      End Try

    End If

  End Sub

    ReadOnly Property IsReusable() As Boolean Implements IHttpHandler.IsReusable
        Get
            Return False
        End Get
    End Property

End Class