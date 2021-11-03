<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>RVMC Testing</title>
<style>
	body{
		margin: 0px;
		background-color: #969498;
		width: 100%;
	}
	header{
		background-color: #747277;
		width: 100%;
		min-height: 10vh;
		text-align: center;	
	}
	footer{
		position: bottom;
		background-color: #747277;
		width: 100%;
		min-height: 10vh;
		margin-top: 0px;
		padding-top: 0px;
	}
	.third{
		width: 32%;
		height: 250px;
		background-color: #D2D2D3;
		display: inline-block;
		margin-left: 1%;
		}
	.row{
		padding-top: 5vh;
	}
	.row:last-of-type{
		padding-bottom: 5vh;
	}
	form{
		margin:2vw;
	}
	header h1{
		width: 100%;
		height: auto;
		margin: 0px;
	}
	header h1 a{
		display: inline-block;
		padding-top: 2.5vh;
	}
	table, th, td {
		border: 1px solid black;
		padding: 10px;
	}
	
</style>
</head>

<header>
<h1><a href="database-test.php">Reset</a></h1>
</header>

<body>

<?php

require dirname(__FILE__) . "/classes/all_classes_include.php";
require dirname(__FILE__) . "/classes/teacher-table.php";
require dirname(__FILE__) . "/classes/lesson-table.php";
require dirname(__FILE__) . "/classes/student-table.php";
require dirname(__FILE__) . "/classes/parent-table.php";

?>
<br>
</body>

<footer>
Footer Test
</footer>

</html>