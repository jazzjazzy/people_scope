<div style="border: green 1px solid; font-family: Arial, Helvetica, sans-serif; font-size: 15px; color:#000; padding:10px; background-color: #8AE234; width:100%; border:solid 3px green"><h4>PHP NOTICE:: Error Information[8]</h4><div> Message :: <strong>Uninitialized string offset: 0</strong></div><br />
			<div>File :: /home/workspace/people_scope/classes/advertisement.class.php</div>
			<div>Line :: 758</div></pre>
			</div><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title><style type="text/css">
<!--

body {
	background-color: #160e0e;
	background-image: url(images/bg-Image.jpg);
	background-repeat: repeat-x;
	font-family: arial;
}

a:LINK, a:VISITED{
	color:#fff;
}
a:HOVER{
	/*color:#CCC;*/
}
h1{
	margin:0px 0px 0px 0px;
}

#title-input{
	width:95%;
	height: 40px;
	font-family: arial;
	font-size: 35px;
}

#title {
	height: 100px;
}

#main {
	margin:0px 0px; 
	padding:0px;
	text-align:center;
	width: 100%;
}

.tab{
	padding-top:10px;
	margin-left:0px;
	float:left;
}

#tab-header {
	background-image: url(images/tab-header.png);
	height: 38px;
	text-align:center;
	font-family:Arial, Helvetica, sans-serif;
	font-size:10px;
	vertical-align:middle;
	padding-top:1px;
}
#tab-text {
	background-image: url(images/tab-text.jpg);
	height: 28px;
	font-size:10px;
	padding:10px 10px 0 10px;
}

#tab-body {
	font-size:13px;
	background-color:black;
	padding-left:10px;
	padding-right:20px;
}

#tab-foot {
	background-image: url(images/tab-foot.png);
	height: 20px;
}

#content {
	width: 1024px;
	text-align:left;
	margin:0px auto;
}

#col-left{

	float:left;
	padding: 0px 10px;
	text-align: left;
	width:750px;
}
#col-right{
	width:224px;
	float:left;
	padding: 16px 10px;
}

body,td,th {
	color: #FFFFFF;
}

.label{
	float:left;
	width:130px;
	height:20px;
}

.value{
	float:left;
	width:200px;
	height:20px;
}

.menu li{
	width: 150px;
	height: 50px;
	text-align: center;
	float: left;
	color: #fff;
	padding-top: 10px;
	margin: 5px 5px 5px 5px;
	font-size: 15px;
	cursor: pointer;
	list-style: none;
	background-image: url("images/button.png");
}

.new-account{
	width: 150px;
	height: 50px;
	text-align: center;
	float: left;
	color: #fff;
	padding-top: 10px;
	margin: 5px 5px;
	font-size: 15px;
	font-weight:700;
	cursor: pointer;
	list-style: none;
	background-image: url("images/button.png");
}

.menu li:hover{
	background-image: url("images/button-selected.png");
	color: #000;
}

.menu a{
	text-decoration: none;
	color: #fff;
	font-weight:700;
}

.clear{
	clear:both;
}

.button {
	width: 75px;
	height: 19px;
	text-align: center;
	float: left;
	padding-top: 2px;
	margin: 10px 2px;
	color: #fff;
	font-size: 10px;
	cursor: pointer;
	background-image: url("images/button-small.png");
}

.button a:link,.button a:visited,.button a:active {
	color: #fff;
	text-decoration: none;
}

.button:hover {
	background-image: url("images/button-small-ov.png");
}
/****filter******/
table.table_lists {
	/*width: 1192px;*/
	border: 0.1pt solid #000;
}

tr.table_lists {
	min-width: 400px;
}

.table_lists tr.row:hover {
	background-color: #C0C0C0;
	border-top: 5px solid #C0C0C0;
	cursor: pointer;
}

.table_lists th {
	padding: 5px 10px;
	background-color: #000;
}

.table_lists td {
	border: 1px solid #eee;
	padding:2px 3px 2px 3px;
}

.table_lists tr.recognised td {
	background: rgb(255, 239, 252);
}

.table_lists tr.recognised.even td {
	background: rgb(255, 223, 248);
}

.table_lists td.recognised.even {
	background: rgb(255, 223, 248);
}

.table_lists tr{
	cursor: pointer;
}

.table_lists tr:hover{
	background-color: #ab9c9c;
}

.filter input, .filter select {
	width:95%;
}  

.even{
	background-color: #999898;
}

.selectedRow{
	background-color: rgb(0, 0, 252) !important;
}

.warning{
 	color:#FEFF5F;
 	/*background-color:#EEEFB3; 
 	border:solid 1px red; 
 	width:100%*/
}
-->
</style>
<script type="text/javascript" src="js/jquery-latest.js"></script>
<script type="text/javascript" src="https://github.com/jamespadolsey/jQuery-Lint.git"></script>
<script type="text/javascript" src="js/table-layout.js"></script>
</head>

<body>
<div id="title"><img src="images/title.png" width="356" height="82" /></div>
<div id="main">
		<div id="content">
		<ul class="menu">
		<li onclick="location.href='administration.php'">administration</li>
		<li onclick="location.href='users.php'">Users</li>
		<li onclick="location.href='division.php'">division</li>
		<li onclick="location.href='advertisement.php'">advertisement</li>
		</ul>
		
		<br class="clear" />
		<div id="col-left">
			<form action="advertisement.php?action=save" method="post" name="createAdvertisement">

<style>

</style>
<link media="print" rel="stylesheet" href="css/bulkprint.css" type="text/css">
<div id="row1">
<h1><div id="staff-title"></div><div style="clear:both;"></div></h1>
<div class="container">
	<div class="tab">
			<div id="tab-header">
				<h1>User Details</h1>
			</div>
			<div id="tab-text">
				Information on a selected user 
			</div>
			<div id="tab-body">
				<div class="noprint"><div class="button" onclick="document.createAdvertisement.submit(); return false">Save</div><div class="button" onclick="location.href='advertisement.php?action=list'">Cancel</div></div><br class="clear"/>
								<div class="label">advertisement_id :</div> <div class="value"><input type='text' name='advertisement_id' id='advertisement_id' value=""></div><br class="clear"/>
				<div class="label">title :</div> <div class="value"><input type='text' name='title' id='title' value=""></div><br class="clear"/>
				<div class="label">catagory_id :</div> <div class="value"><input type='text' name='catagory_id' id='catagory_id' value=""></div><br class="clear"/>
				<div class="label">template_id :</div> <div class="value"><input type='text' name='template_id' id='template_id' value=""></div><br class="clear"/>
				<div class="label">office_id :</div> <div class="value"><input type='text' name='office_id' id='office_id' value=""></div><br class="clear"/>
				<div class="label">dept_id :</div> <div class="value"><input type='text' name='dept_id' id='dept_id' value=""></div><br class="clear"/>
				<div class="label">role_id :</div> <div class="value"><input type='text' name='role_id' id='role_id' value=""></div><br class="clear"/>
				<div class="label">state_id :</div> <div class="value"><input type='text' name='state_id' id='state_id' value=""></div><br class="clear"/>
				<div class="label">store_location_id :</div> <div class="value"><input type='text' name='store_location_id' id='store_location_id' value=""></div><br class="clear"/>
				<div class="label">storerole_id :</div> <div class="value"><input type='text' name='storerole_id' id='storerole_id' value=""></div><br class="clear"/>
				<div class="label">start_date :</div> <div class="value"><input type='text' name='start_date' id='start_date' value=""></div><br class="clear"/>
				<div class="label">end_date :</div> <div class="value"><input type='text' name='end_date' id='end_date' value=""></div><br class="clear"/>
				<div class="label">discription :</div> <div class="value"><input type='text' name='discription' id='discription' value=""></div><br class="clear"/>
				<div class="label">requirments :</div> <div class="value"><input type='text' name='requirments' id='requirments' value=""></div><br class="clear"/>
				<div class="label">upload_resume :</div> <div class="value"><input type='checkbox' name='upload_resume' id='upload_resume' value='1'  /></div><br class="clear"/>
				<div class="label">cover_letter :</div> <div class="value"><input type='checkbox' name='cover_letter' id='cover_letter' value='1'  /></div><br class="clear"/>
				<div class="label">status :</div> <div class="value"><input type='checkbox' name='status' id='status' value='1'  /></div><br class="clear"/>
				<div class="label">employmenttype :</div> <div class="value"><input type='text' name='employmenttype' id='employmenttype' value=""></div><br class="clear"/>
				<div class="label">create_by :</div> <div class="value"><input type='text' name='create_by' id='create_by' value=""></div><br class="clear"/>
				<div class="label">modify_by :</div> <div class="value"><input type='text' name='modify_by' id='modify_by' value=""></div><br class="clear"/>
				<div class="label">delete_by :</div> <div class="value"><input type='text' name='delete_by' id='delete_by' value=""></div><br class="clear"/>
				<div class="label">tracking_id :</div> <div class="value"><input type='text' name='tracking_id' id='tracking_id' value=""></div><br class="clear"/>
				<div class="label">question_id :</div> <div class="value"><input type='text' name='question_id' id='question_id' value=""></div><br class="clear"/>

			<br class="clear"/>
			</div>
			<div id="tab-foot"></div>
	</div>
	<div  class="tab">
	<div id="tab-header">
			<h1>Account Details</h1>
		</div>
		<div id="tab-text">
			Information about the element, create, Modify and delete dates 
		</div>
		<div id="tab-body">
			<div style="width:120px">Created on : </div><input type='text' name='create_date' id='create_date' value=""><br />
			<div style="width:120px">Modified on :</div><input type='text' name='modify_date' id='modify_date' value=""><br />
			<div style="width:120px">Deleted on :</div><input type='text' name='delete_date' id='delete_date' value=""><br />
			<br class="clear"/>
		</div>
		<div id="tab-foot"></div>
	</div>
</div>
<br class="clear"/>

</div>


			</div>
		</div><br class="clear:both"/>
</div> 
</div> 

</body>
</html>
