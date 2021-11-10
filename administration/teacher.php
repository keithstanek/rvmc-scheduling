<?php  require dirname(__FILE__) . "/page-includes/header.php";  ?>
<?php  require dirname(__FILE__) . "/page-includes/nav-bar.php";  ?>
<?php  require dirname(__FILE__) . "/page-includes/page-wrapper-start.php";  ?>

<?php


?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-floating mb-3">
                            <input type="text" name="teacher_name" class="form-control" id="floatingInput" placeholder="John" value="<?=$teacher->teacher_name?>">
                            <label for="floatingInput">First Name</label>
                    </div>
                    <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="<?=$parent->email?>">
                            <label for="floatingInput">Email address</label>
                    </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php  require dirname(__FILE__) . "/page-includes/footer.php";  ?>
