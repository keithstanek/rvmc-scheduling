<?php
// database connection code
//reference site https://www.raghwendra.com/blog/how-to-connect-html-to-database-with-mysql-using-php-example/
if(isset($_POST['txtName']))
{
	// $con = mysqli_connect('localhost', 'database_user', 'database_password','database');
	$con = mysqli_('localhost', 'root', '','db_rvmc');

	// get the post records

	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$instructor = $_POST['instructor'];
	$instrument = $_POST['instrument'];

	// database insert SQL code
	$sql = "INSERT INTO `tbl_checkin` (`Id`, `fldfname`, `fldlname`, `fldinstructor`, `fldinstrument`) VALUES 
	('0', '$fname', '$lname', '$instructor', '$instrument')";

	// insert in database 
	$rs = mysqli_query($con, $sql);
	if($rs)
	{
		echo "Contact Records Inserted";
	}
}
else
{
	echo "Are you a genuine visitor?";
	
}
?>


 //reference site https://www.raghwendra.com/blog/how-to-connect-html-to-database-with-mysql-using-php-example/