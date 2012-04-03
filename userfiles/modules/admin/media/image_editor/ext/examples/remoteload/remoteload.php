<?php
	session_start();
	$_SESSION['isAdmin'] = true;
?>
<html>
<head>
  <title>Remote Component Loading - Employee DB</title>
	<link rel="stylesheet" type="text/css" href="../../resources/css/ext-all.css" />
    
    <!-- GC -->
 	<!-- LIBS -->
 	<script type="text/javascript" src="../../adapter/ext/ext-base.js"></script>
 	<!-- ENDLIBS -->

    <script type="text/javascript" src="../../ext-all.js"></script>
    <script type="text/javascript" src="ComponentLoader.js"></script>
	<script type="text/javascript" src="EmployeeDetailsTab.js"></script>
	<script type="text/javascript" src="EmployeeDetails.js"></script>
	<script type="text/javascript" src="EmployeePropertyGrid.js"></script>
	<script type="text/javascript" src="EmployeeGrid.js"></script>
	<script type="text/javascript" src="EmployeeStore.js"></script>
    <script type="text/javascript" src="App.js"></script>	

</head>
<body>
	<textarea id="employeeDetailTpl" class="x-hidden">		
		<div>
			<table cellpadding="2" cellspacing="2">
				<tr>
					<td>First Name:</td>
					<td>{firstName}</td>
				</tr>
				<tr>
					<td>Last Name:</td>
					<td>{lastName}</td>
				</tr>
				<tr>
					<td>Title:</td>
					<td>{title}</td>
				</tr>
				<tr>
					<td>Telephone:</td>
					<td>{telephone}</td>
				</tr>							
		
	<?php
		if (true == $_SESSION['isAdmin']) {
	?>
				<tr>
					<td>Home Address</td>
					<td><tpl for="homeAddress">{street}<br/>{city}, {state}<br/></tpl></td>
				</tr>			
	
	<?php
		}
	?>
		</div>
	</textarea>
	
</body>
</html>
