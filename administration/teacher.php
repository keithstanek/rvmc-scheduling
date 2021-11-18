<?php  
$title = "Teacher Admin";
$breadcrumb = "Teachers";

require dirname(__FILE__) . "/page-includes/header.php";  
require dirname(__FILE__) . "/page-includes/nav-bar.php";  
require dirname(__FILE__) . "/page-includes/page-wrapper-start.php";  

// check to see if they submitted the form with a proper action
$dbMessage = "";
$dbMessageBg = "bg-primary";
$action = "insert"; // the default action for the page load
$teacherDao = new TeacherDao();

$teacher = new Teacher();

// check ternary operator --> true ? do this : else this;
isset($_POST["id"]) ? $teacher->id = $_POST["id"] : $teacher->id = "";
isset($_POST["teacher_name"]) ? $teacher->teacher_name = $_POST["teacher_name"] : $teacher->teacher_name = "";
isset($_POST["teacher_email"]) ? $teacher->teacher_email = $_POST["teacher_email"] : $teacher->teacher_email = "";
isset($_POST["phone"]) ? $teacher->teacher_phone = $_POST["phone"] : $teacher->teacher_phone = "";


// if they submitted the form, take the values from above and insert/update/delete the record
if ( isset($_POST["btnInsert"]) && $_POST["btnInsert"] == "insert" ) {
    
    $dbMessage = $teacherDao->insert($teacher);
} else if ( isset($_POST["btnUpdate"]) && $_POST["btnUpdate"] == "update" ) {
    $dbMessage = $teacherDao->update($teacher);
} else if ( isset($_POST["btnDelete"]) && $_POST["btnDelete"] == "delete" ) {
    $dbMessage = $teacherDao->delete($teacher);
}

// if they came in through the link to update/delete, lets get the values from the database
// these values will be sent in the url, so we will use the GET method ---> Check the difference between GET/POST if you need help
if ( isset($_GET["id"]) ) {
    $id = $_GET["id"];

    // get the person data from the table
    $teacher = $teacherDao->getTeacherById($id);
}

if ( str_contains($dbMessage, "ERROR") ) {
    $dbMessageBg = "bg-error";
}
?>

<div class="container-fluid">
    <h2>Teacher Administration Page</h2>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="teacher.php" method="post">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <input type="text" name="teacher_name" class="form-control" id="floatingInput1" placeholder="John" value="<?=$teacher->teacher_name?>">
                                    <label for="floatingInput">Teacher Name</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="email" name="teacher_email" class="form-control" id="floatingInput2" placeholder="name@example.com" value="<?=$teacher->teacher_email?>">
                                    <label for="floatingInput">Email address</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" name="phone" class="form-control" id="floatingInput3" placeholder="123-456-7890" value="<?=$teacher->teacher_phone?>">
                                    <label for="floatingInput">Phone No.</label>
                                </div>
                                <div class="form-floating mb-3">
                                <select id="lessons" multiple name="lessonids">
                                    <option value=""></option>    
                                    <?php
                                        $lessonDao = new LessonDao();
                                        $lessons = $lessonDao->getAllLessons(); //loop through all lessons
                                        foreach ($lessons as $lesson) {
                                            $selected = "";
                                            foreach($teacher->lessons as $l){          //searching for valid lesson selected under teacher
                                                if($l->id==$lesson->id){      
                                                    $selected = "selected";     //once selected is found break the loop
                                                    break;
                                                }
                                            } 
                                        ?>
                                            <option <?=$selected ?> value="<?= $lesson->id ?>"> <?= $lesson->Lesson_Name ?></option>  
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <input type="hidden" name="id" value="<?=$teacher->id?>">
                                <?php
                                if ($teacher->id == "") {
                                ?>
                                <button type="submit" name="btnInsert" value="insert" class="btn btn-primary">Insert</button>
                                <?php
                                } else {
                                ?>
                                <button type="submit" name="btnUpdate" value="update" class="btn btn-primary">Update</button>
                                <button type="submit" name="btnDelete" value="delete" class="btn btn-warning">Delete</button>
                                <button type="submit" name="btnReset" value="reset" class="btn btn-danger" onclick="freset();">Reset</button>
								<?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                        if ($dbMessage != "") {
                        ?>
                            <br>
                                <div class="row">
                                    <div class="col">
                                    <button style="width: 100%" class="btn btn-block text-white <?= $dbMessageBg ?>"><?= $dbMessage ?></button>
                                    </div>
                            </div>
                        <?php
                        }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<Script>
    //form reset function                                                                                       
    function freset(){

            document.getElementById("floatingInput1").value = "";   //parent form input fname
            document.getElementById("floatingInput2").value = "";  //parent form input lname
            document.getElementById("floatingInput3").value = "";  //parent form input email
            document.getElementById("pid").value = -1;

          }
    </script>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Teacher Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Lessons Taught</th>
                                    <th scope="col">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $teachers = $teacherDao->getAllteachers();

                            foreach ($teachers as $teacher) {
                            ?>
                                <tr>
                                    <th scope="row"><a href="teacher.php?id=<?=$teacher->id?>"><?= $teacher->id ?></a></th>
                                    <td><?= $teacher->teacher_name ?></td>
                                    <td><?= $teacher->teacher_email ?></td>
                                    <td><?= $teacher->teacher_phone ?></td>
                                    <td><?= $teacher->teacher_lessons ?></td>
                                    <td>
                                        <a href="teacher.php?id=<?=$teacher->id?>" class="fa fa-copy"></a> &nbsp;
                                        <a href="teacher.php?id=<?=$teacher->id?>" class="fa fa-trash"></a>

                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php  require dirname(__FILE__) . "/page-includes/footer.php";  ?>
