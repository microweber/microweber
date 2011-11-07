//function below added by logan (cailongqun [at] yahoo [dot] com [dot] cn) from www.phpletter.com
function selectFile(url)
{
  if(url != '' )
  {     
      window.opener.SetUrl( url ) ;
      window.close() ;
      
  }else
  {
     alert(noFileSelected);
  }
  return false;
  

}



function cancelSelectFile()
{
  // close popup window
  window.close() ;
  return false;
}