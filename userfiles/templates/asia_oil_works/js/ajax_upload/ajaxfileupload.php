<html>
	<head>
		<title>Ajax File Uploader Plugin For Jquery</title>
<link href="ajaxfileupload.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="ajaxfileupload.js"></script>
	<script type="text/javascript">
	function ajaxFileUpload()
	{
		$("#loading")
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});

		$.ajaxFileUpload
		(
			{
				url:'doajaxfileupload.php',
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'json',
				data:{name:'logan', id:'id'},
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}else
						{
							alert(data.msg);
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
		
		return false;

	}
	</script>	
	</head>

	<body>
<div id="wrapper">
    <div id="content">
    	<h1>Ajax File Upload Demo</h1>
    	<p>Jquery File Upload Plugin  - upload your files with only one input field</p>
				<p>
				need any Web-based Information System?<br> Please <a href="http://www.phpletter.com/">Contact Us</a><br>
				We are specialized in <br>
				<ul>
					<li>Website Design</li>
					<li>Survey System Creation</li>
					<li>E-commerce Site Development</li>
				</ul>    	
		<img id="loading" src="loading.gif" style="display:none;">
		<form name="form" action="" method="POST" enctype="multipart/form-data">
		<table cellpadding="0" cellspacing="0" class="tableForm">

		<thead>
			<tr>
				<th>Please select a file and click Upload button</th>
			</tr>
		</thead>
		<tbody>	
			<tr>
				<td><input id="fileToUpload" type="file" size="45" name="fileToUpload" class="input"></td>			</tr>

		</tbody>
			<tfoot>
				<tr>
					<td><button class="button" id="buttonUpload" onclick="return ajaxFileUpload();">Upload</button></td>
				</tr>
			</tfoot>
	
	</table>
		</form>    	
    </div>
    

	</body>
</html>