<?php
$title = "Students Admin";
$breadcrumb = "Students";

require dirname(__FILE__) . "/page-includes/header.php";
require dirname(__FILE__) . "/page-includes/nav-bar.php";
require dirname(__FILE__) . "/page-includes/page-wrapper-start.php";


// check to see if they submitted the form with a proper action
$dbMessage = "";
$dbMessageBg = "bg-primary";
$action = "insert"; // the default action for the page load
$studentDao = new StudentDao();

$student = new Student();

// check ternary operator --> true ? do this : else this;
isset($_POST["id"]) ? $student->student_id = $_POST["id"] : $student->student_id = "";
isset($_POST["firstname"]) ? $student->first_name = $_POST["firstname"] : $student->first_name = "";
isset($_POST["lastname"]) ? $student->last_name = $_POST["lastname"] : $student->last_name = "";
isset($_POST["DOB"]) ? $student->DOB = $_POST["DOB"] : $student->DOB = "";
isset($_POST["ParentID"]) ? $student->parent_id = $_POST["ParentID"] : $student->parent_id = "";


// if they submitted the form, take the values from above and insert/update/delete the record
if ( isset($_POST["btnInsert"]) && $_POST["btnInsert"] == "insert" ) {
    $dbMessage = $studentDao->insert($student);
} else if ( isset($_POST["btnUpdate"]) && $_POST["btnUpdate"] == "update" ) {
    $dbMessage = $studentDao->update($student);
} else if ( isset($_POST["btnDelete"]) && $_POST["btnDelete"] == "delete" ) {
    $dbMessage = $studentDao->delete($student);
}

// if they came in through the link to update/delete, lets get the values from the database
// these values will be sent in the url, so we will use the GET method ---> Check the difference between GET/POST if you need help
if ( isset($_GET["id"]) ) {
    $id = $_GET["id"];

    // get the person data from the table
    $student = $studentDao->getstudentById($id);
}

if ( str_contains($dbMessage, "ERROR") ) {
    $dbMessageBg = "bg-error";
}


?>

<div class="container-fluid">
    <h2>Student Administration Page</h2>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="student.php" method="post">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <input type="text" name="firstname" class="form-control" id="floatingInput" placeholder="John" value="<?=$student->first_name?>">
                                    <label for="floatingInput">First Name</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" name="DOB" class="form-control" id="floatingInput" placeholder="MM-DD-YYYY" value="<?=$student->DOB?>">
                                    <label for="floatingInput">Date of Birth</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <input type="text" name="lastname" class="form-control" id="floatingInput" placeholder="Doe" value="<?=$student->last_name?>">
                                    <label for="floatingInput">Last Name</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" name="ParentID" class="form-control" id="floatingInput" placeholder="Doe" value="<?=$student->parent_id?>">
                                    <label for="floatingInput">Parent</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <input type="hidden" name="id" value="<?=$student->student_id?>">
                                <?php
                                if ($student->student_id == "") {
                                ?>
                                <button type="submit" name="btnInsert" value="insert" class="btn btn-primary">Insert</button>
                                <?php
                                } else {
                                ?>
                                <button type="submit" name="btnUpdate" value="update" class="btn btn-primary">Update</button>
                                <button type="submit" name="btnDelete" value="delete" class="btn btn-warning">Delete</button>
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


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">DOB</th>
                                    <th scope="col">parent_id</th>
                                    <th scope="col">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $students = $studentDao->getAllstudents();

                            foreach ($students as $student) {
                            ?>
                                <tr>
                                    <td scope="row"><a href="student.php?id=<?=$student->student_id?>"><?= $student->student_id ?></a></td>
                                    <td><?= $student->first_name ?></td>
                                    <td><?= $student->last_name ?></td>
                                    <td><?= $student->DOB ?></td>
                                    <td><?= $student->parent_id ?></td>
                                    <td>
                                        <a href="student.php?id=<?=$student->student_id?>" class="fa fa-copy"></a> &nbsp;
                                        <a href="student.php?id=<?=$student->student_id?>" class="fa fa-trash"></a>

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




