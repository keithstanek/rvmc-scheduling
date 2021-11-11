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
	form {
		display: inline-block;
		padding-top: 2vh;
		padding-right: 2vw;
	}
	
</style>
</head>

<header>
<h1><a href="database-test.php">Reset</a></h1>
</header>

<body>

<?php
require dirname(__FILE__) . "/classes/all_classes_include.php";

	$Teacher = NULL;
	$Student = NULL;
	$Guardian = NULL;
	$Lesson = NULL;

	$Teacher = new Teacher;
		$TeacherDao = new TeacherDao;
	$Student = new Student;
		$StudentDao = new StudentDao;
	$Guardian = new Guardian;
		$GuardianDao = new ParentDao;
	$Lesson = new Lesson;
		$LessonDao = new LessonDao;
?>

Teacher Form
<form action="database-test.php" method="post">
	<!-- <label for="A1">TeacherID</label><input type="text" name="A1"><br> -->
	<label for="A2">Teacher Name</label><input type="text" name="A2"><br>
	<label for="A3">Teacher Image</label><input type="text" name="A3"><br>
	<label for="A4">Teacher Phone</label><input type="text" name="A4"><br>
	<input type="submit" value="Submit" name="TeacherSubmit" onclick="
<?php
			 if(array_key_exists('TeacherSubmit', $_POST)) {
				//$Teacher->__set('TeacherID', $_POST['A1']);
				$Teacher->__set('teacher_name', $_POST['A2']);
				$Teacher->__set('teacher_photo', $_POST['A3']);
				$Teacher->__set('teacher_phone', $_POST['A4']);
			
			$TeacherDao->insert($Teacher);
			}
			
		?>
		">
</form>

Student Form
<form action="database-test.php" method="post">
	<!-- <label for="B1">StudentID</label><input type="text" name="B1"><br> -->
	<label for="B2">First Name</label><input type="text" name="B2"><br>
	<label for="B3">Last Name</label><input type="text" name="B3"><br>
	<label for="B4">Date of Birth (YYYY-MM-DD)</label><input type="date" name="B4"><br>
	<label for="B5">ParentID</label><input type="text" name="B5"><br>
	<input type="submit" value="Submit" name="StudentSubmit" onclick="
<?php
			 if(array_key_exists('StudentSubmit', $_POST)) {
				//$Student->__set('student_id', $_POST['B1']);
				$Student->__set('first_name', $_POST['B2']);
				$Student->__set('last_name', $_POST['B3']);
				$Student->__set('DOB', $_POST['B4']);
				$Student->__set('parent_id', $_POST['B5']);
			
			$StudentDao->insert($Student);
			}
			
?>
		">
</form>

Parent Form
<form action="database-test.php" method="post">
	<label for="C1">ParentID</label><input type="text" name="C1"><br>
	<label for="C2">First Name</label><input type="text" name="C2"><br>
	<label for="C3">Last Name</label><input type="text" name="C3"><br>
	<label for="C4">Email</label><input type="text" name="C4"><br>
	<label for="C5">Phone</label><input type="text" name="C5"><br>
	<input type="submit" value="Submit" name="ParentSubmit" onclick="
<?php
			 if(array_key_exists('ParentSubmit', $_POST)) {
				$Guardian->__set('parent_id', $_POST['C1']);
				$Guardian->__set('first_name', $_POST['C2']);
				$Guardian->__set('last_name', $_POST['C3']);
				$Guardian->__set('email', $_POST['C4']);
				$Guardian->__set('phone', $_POST['C5']);
			
			$GuardianDao->insert($Guardian);
			}
			
		?>
		">
</form>

Lesson Form
<form action="database-test.php" method="post">
	<!-- <label for="D1">Lesson ID</label><input type="text" name="D1"><br> -->
	<label for="D2">Lesson Name</label><input type="text" name="D2"><br>
	<label for="D3">Lesson Type</label><input type="text" name="D3"><br>
	<input type="submit" value="Submit" name="LessonSubmit" onclick="
<?php
			 if(array_key_exists('LessonSubmit', $_POST)) {
				//$Lesson->__set('lessonID', $_POST['D1']);
				$Lesson->__set('Lesson_Name', $_POST['D2']);
				$Lesson->__set('Lesson_Type', $_POST['D3']);
			
			$LessonDao->insert($Lesson);
			}
			
		?>
		">
</form>

<?php

require dirname(__FILE__) . "/classes/teacher-table.php";
require dirname(__FILE__) . "/classes/student-table.php";
require dirname(__FILE__) . "/classes/parent-table.php";
require dirname(__FILE__) . "/classes/lesson-table.php";

?>
<br>
</body>

<footer>
Footer Test
</footer>

</html>