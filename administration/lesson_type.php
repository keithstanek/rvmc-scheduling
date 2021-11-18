<?php
$title = "Parents Admin";
$breadcrumb = "Parents";

require dirname(__FILE__) . "/page-includes/header.php";
require dirname(__FILE__) . "/page-includes/nav-bar.php";
require dirname(__FILE__) . "/page-includes/page-wrapper-start.php";


// check to see if they submitted the form with a proper action
$dbMessage = "";
$dbMessageBg = "bg-primary";
$action = "insert"; // the default action for the page load
$parentDao = new ParentDao();

$parent = new Guardian();

// check ternary operator --> true ? do this : else this;
isset($_POST["id"]) ? $parent->id = $_POST["id"] : $parent->id = "";
isset($_POST["first_name"]) ? $parent->firstName = $_POST["first_name"] : $parent->firstName = "";
isset($_POST["last_name"]) ? $parent->lastName = $_POST["last_name"] : $parent->lastName = "";
isset($_POST["email"]) ? $parent->email = $_POST["email"] : $parent->email = "";
isset($_POST["phone"]) ? $parent->phone = $_POST["phone"] : $parent->phone = "";


// if they submitted the form, take the values from above and insert/update/delete the record
if ( isset($_POST["btnInsert"]) && $_POST["btnInsert"] == "insert" ) {
    $dbMessage = $parentDao->insert($parent);
} else if ( isset($_POST["btnUpdate"]) && $_POST["btnUpdate"] == "update" ) {
    $dbMessage = $parentDao->update($parent);
} else if ( isset($_POST["btnDelete"]) && $_POST["btnDelete"] == "delete" ) {
    $dbMessage = $parentDao->delete($parent);
}

// if they came in through the link to update/delete, lets get the values from the database
// these values will be sent in the url, so we will use the GET method ---> Check the difference between GET/POST if you need help
if ( isset($_GET["id"]) ) {
    $id = $_GET["id"];

    // get the person data from the table
    $parent = $parentDao->getParentById($id);
}

if ( str_contains($dbMessage, "ERROR") ) {
    $dbMessageBg = "bg-error";
}


?>

<div class="container-fluid">
    <h2>Parent Administration Page</h2>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="parent.php" method="post">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <input type="text" name="first_name" class="form-control" id="floatingInput" placeholder="John" value="<?=$parent->firstName?>">
                                    <label for="floatingInput">First Name</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating mb-3">
                                    <input type="text" name="last_name" class="form-control" id="floatingInput3" placeholder="Doe" value="<?=$parent->lastName?>">
                                    <label for="floatingInput3">Last Name</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" name="btnInsert" value="search" class="btn btn-primary" >Search</button>
                                
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
                                    <th scope="col" id="tblFname">First Name</th>
                                    <th scope="col" id="tblLname">Last Name</th>
                                    <th scope="col" id="tblLesType">Lesson Type</th>
                                    <th scope="col">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                           
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

function freset(){
            
            
			document.getElementById("floatingInput").value = "";   //parent form input fname
		    document.getElementById("floatingInput2").value = "";  //parent form input lname
            document.getElementById("sid").value = -1;
            
          }
</script>

<?php  require dirname(__FILE__) . "/page-includes/footer.php";  ?>
