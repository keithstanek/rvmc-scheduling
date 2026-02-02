<?php  
$title = "Course Admin";
$breadcrumb = "Courses";

require dirname(__FILE__) . "/page-includes/header.php";  
require dirname(__FILE__) . "/page-includes/nav-bar.php";  
require dirname(__FILE__) . "/page-includes/page-wrapper-start.php";  

// check to see if they submitted the form with a proper action
$dbMessage = "";
$dbMessageBg = "bg-primary";
$action = "insert"; // the default action for the page load
$courseDao = new CourseDao();

$course = new Course();

// check ternary operator --> true ? do this : else this;
isset($_POST["id"]) ? $course->id = $_POST["id"] : $course->id = "";
isset($_POST["name"]) ? $course->name = $_POST["name"] : $course->name = "";


// if they submitted the form, take the values from above and insert/update/delete the record
if ( isset($_POST["btnInsert"]) && $_POST["btnInsert"] == "insert" ) {
    $dbMessage = $courseDao->insert($course);
} else if ( isset($_POST["btnUpdate"]) && $_POST["btnUpdate"] == "update" ) {
    $dbMessage = $courseDao->update($course);
} else if ( isset($_POST["btnDelete"]) && $_POST["btnDelete"] == "delete" ) {
    $dbMessage = $courseDao->delete($course);
}

// if they came in through the link to update/delete, lets get the values from the database
// these values will be sent in the url, so we will use the GET method ---> Check the difference between GET/POST if you need help
if ( isset($_GET["id"]) ) {
    $id = $_GET["id"];

    // get the course data from the table
    $course = $courseDao->getCourseByCourseID($id);
}

if ( str_contains($dbMessage, "ERROR") ) {
    $dbMessageBg = "bg-error";
}
?>

<div class="container-fluid">
    <h2>Course Administration Page</h2>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="course.php" method="post">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <input type="text" name="name" class="form-control" id="floatingInput1" placeholder="John" value="<?=$course->name?>">
                                    <label for="floatingInput">Course Name</label>
                                </div>
							</div>
                        <div class="row">
                            <div class="col-12">
                                <input type="hidden" name="id" value="<?=$course->id?>">
                                <?php
                                if ($course->id == "") {
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
            document.getElementById("id").value = -1;

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
                                    <th scope="col">Course Name</th>
                                    <!-- <th scope="col">Course Type</th> -->
                                    <th scope="col">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $courses = $courseDao->getAllcourses();

                            foreach ($courses as $course) {
                            ?>
                                <tr>
                                    <td scope="row"><a href="course.php?id=<?=$course->id?>"><?= $course->id ?></a></td>
                                    <td><?= $course->name ?></td>
                                    <td>
                                        <a href="course.php?id=<?=$course->id?>" class="fa fa-copy"></a> &nbsp;
                                        <a href="course.php?id=<?=$course->id?>" class="fa fa-trash"></a>

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