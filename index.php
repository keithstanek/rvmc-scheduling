<?php
require dirname(__FILE__) . "/classes/all_classes_include.php";

?>

Test Database connection<br>

<?php
try {
    $db = DbUtil::getConnection();

    $sql = "select * from test";
    $stmt = $db->prepare($sql);
    if ($stmt->execute()) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
                id [<?= $row["id"] ?>]
                first [<?= $row["first"] ?>]
                middle [<?= $row["middle"] ?>]
                last [<?= $row["last"] ?>]
                <br>
            <?php
        }
    }

    $db = null;
} catch (PDOException $e) {
    echo 'DATABASE ERROR' . $e->getMessage() . '<br>';
}

?>
<hr>
<?php
phpinfo();
?>
