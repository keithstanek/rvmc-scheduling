<?php
// TODO: SET THE PAGE NAME AND PAGE TITLE SHOW THEY SHOW UP PROPERLY IN THE BROWSER AND NAVIGATION
$pageName  = "REPLACE ME";
$pageTitle = "REPLACE ME";

require dirname(__FILE__) . "/page-includes/header.php";
require dirname(__FILE__) . "/page-includes/nav-bar.php";

?>
<!-- TODO: REMOVE THE <SCRIPT> BLOCK IF NO JAVASCRIPT IS USED ON THIS PAGE -->
<script>
$(document).ready(function() {

});

function dummyFunction() {
}
</script>
<?php

// TODO: REMOVE IF NO FORMS ARE BEING SUBMITTED ON THIS PAGE
// check to see if they submitted the form with a proper action
if (isset($_POST["action"]) && isset($_POST["listingId"]) &&
   ($_POST["action"] == "x" || $_POST["action"] == "y") ) {
}

?>

<div class="container">
    <!-- TODO: REPLACE ME WITH THE PAGE CONTENT HERE -->
</div>

<?php
require dirname(__FILE__) . "/page-includes/footer.php";
?>

