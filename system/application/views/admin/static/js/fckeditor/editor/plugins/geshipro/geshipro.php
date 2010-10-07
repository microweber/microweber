<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Geshi Highlighter for FCKeditor</title>
		<script src="scripts/mootools.js" type="text/javascript"></script>
		<script src="./scripts/geshipro.js" type="text/javascript"></script>
		<link href="../../dialog/common/fck_dialog_common.css" rel="stylesheet" type="text/css" />
		<link href="css/styles.css" rel="stylesheet" type="text/css" />
	</head>

	<body scroll="no">

		<form id="f1" name="f1">
			<div id="divSourceCode" class="show">
				<fieldset>
					<legend><span fckLang="GeshiproSourceCode">Source Code</span></legend>
						<textarea name="source" id="source"></textarea>
				</fieldset>
				<fieldset>
					<legend><span fckLang="GeshiproLanguage">Language</span></legend>
					<select name="lang" id="lang">
						<?php
							$handle = fopen("./geshi/lang.txt", "r");
							if ($handle) {
								while (($data = fgetcsv($handle, 1000, "|")) !== FALSE) {
									$default = NULL;
									if ($data[0] == "php") {
										$default = "selected=\"selected\"";
									}
									print "<option value=\"{$data[0]}\" $default>{$data[1]}</option>\r\n" ;
								}
								fclose($handle);
							}
						?>
					</select>
				</fieldset>
			</div>
			<div id="divOptions" class="hide">
				<fieldset id="lines">
					<legend><span fckLang="GeshiproLineNumbers">Line Numbers</span></legend>
					<select name="linenum" id="linenum">
						<option value="1" selected="selected" fckLang="GeshiproNoLines">None</option>
						<option value="2" fckLang="GeshiproNormalLines">Normal</option>
						<option value="3" fckLang="GeshiproFancyLines">Fancy</option>
					</select>
				</fieldset>
				<fieldset id="tabwidth">
					<legend><span fcklang="GeshiproTabs">Tab Width</span></legend>
					<input type="text" name="tabwidth" size="1" value="" />
				</fieldset>
				<fieldset id="cssclasses">
					<legend><span fcklang="GeshiproCssClasses">CSS Classes</span></legend>
					<input type="radio" name="css_classes" value="0" checked="checked" /><span fcklang="GeshiproCssClassesDisable">Disabled</span><br />
					<input type="radio" name="css_classes" value="1" /><span fcklang="GeshiproCssClassesEnable">Enabled</span>
				</fieldset>
				<fieldset id="codecontainer">
					<legend><span fcklang="GeshiproCodeContainer">Code Container</span></legend>
					<select name="code_container" id="code_container">
						<option value="1" selected="selected" fckLang="GeshiproContainerNone">No Container</option>
						<option value="2" selected="selected" fckLang="GeshiproContainerPre">pre Container</option>
						<option value="3" fckLang="GeshiproContainerDiv">div Container</option>
					</select>
				</fieldset>
			</div>
		</form>
		<div id="wait"><span fckLang="GeshiproWaitMsg">Waiting for geshi to respond...</span></div>
	</body>
</html>