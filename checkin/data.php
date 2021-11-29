<?php
require dirname(__FILE__) . "/../classes/all_classes_include.php";

$id = "";
if (isset($_GET["id"]) ) {
    $id = $_GET["id"];
}

if ($id == "") {
    echo "";
    return;
}

$teacherDao = new TeacherDao();

$teacher = $teacherDao->getTeacherByid($id);

$output = "";
foreach($teacher->lessons as $lesson) {
    $output .=  $lesson->Lesson_Name . ",";
}

echo substr($output, 0, -1);