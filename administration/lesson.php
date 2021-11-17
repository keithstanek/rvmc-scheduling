<?php  
$title = "Lesson Admin";
$breadcrumb = "Lessons";

require dirname(__FILE__) . "/page-includes/header.php";  
require dirname(__FILE__) . "/page-includes/nav-bar.php";  
require dirname(__FILE__) . "/page-includes/page-wrapper-start.php";  

// check to see if they submitted the form with a proper action
$dbMessage = "";
$dbMessageBg = "bg-primary";
$action = "insert"; // the default action for the page load
$lessonDao = new LessonDao();

$lesson = new Lesson();

// check ternary operator --> true ? do this : else this;
isset($_POST["id"]) ? $lesson->id = $_POST["id"] : $lesson->id = "";
isset($_POST["Lesson_Name"]) ? $lesson->Lesson_Name = $_POST["Lesson_Name"] : $lesson->Lesson_Name = "";
isset($_POST["Lesson_Type"]) ? $lesson->Lesson_Type = $_POST["Lesson_Type"] : $lesson->Lesson_Type = "";


// if they submitted the form, take the values from above and insert/update/delete the record
if ( isset($_POST["btnInsert"]) && $_POST["btnInsert"] == "insert" ) {
    $dbMessage = $lessonDao->insert($lesson);
} else if ( isset($_POST["btnUpdate"]) && $_POST["btnUpdate"] == "update" ) {
    $dbMessage = $lessonDao->update($lesson);
} else if ( isset($_POST["btnDelete"]) && $_POST["btnDelete"] == "delete" ) {
    $dbMessage = $lessonDao->delete($lesson);
}

// if they came in through the link to update/delete, lets get the values from the database
// these values will be sent in the url, so we will use the GET method ---> Check the difference between GET/POST if you need help
if ( isset($_GET["id"]) ) {
    $id = $_GET["id"];

    // get the lesson data from the table
    $lesson = $lessonDao->getLessonByLessonID($id);
}

if ( str_contains($dbMessage, "ERROR") ) {
    $dbMessageBg = "bg-error";
}
?>

<div class="container-fluid">
    <h2>Lesson Administration Page</h2>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="lesson.php" method="post">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <input type="text" name="Lesson_Name" class="form-control" id="floatingInput1" placeholder="John" value="<?=$lesson->Lesson_Name?>">
                                    <label for="floatingInput">Lesson Name</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" name="Lesson_Type" class="form-control" id="floatingInput2" placeholder="Beginner, Intermediate, Advanced" value="<?=$lesson->Lesson_Type?>">
                                    <label for="floatingInput">Lesson Type</label>
                                </div>
							</div>
                        <div class="row">
                            <div class="col-12">
                                <input type="hidden" name="id" value="<?=$lesson->id?>">
                                <?php
                                if ($lesson->id == "") {
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
                                    <th scope="col">Lesson Name</th>
                                    <th scope="col">Lesson Type</th>
                                    <th scope="col">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $lessons = $lessonDao->getAlllessons();

                            foreach ($lessons as $lesson) {
                            ?>
                                <tr>
                                    <td scope="row"><a href="lesson.php?id=<?=$lesson->id?>"><?= $lesson->id ?></a></td>
                                    <td><?= $lesson->Lesson_Name ?></td>
                                    <td><?= $lesson->Lesson_Type ?></td>
                                    <td>
                                        <a href="lesson.php?id=<?=$lesson->id?>" class="fa fa-copy"></a> &nbsp;
                                        <a href="lesson.php?id=<?=$lesson->id?>" class="fa fa-trash"></a>

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