
<div style="text-align: center">
    <h3>Test Database connection</h3>
</div>

<?php
$testDao = new TestDao();
$testRecords = $testDao->getAllRecords();

?>
<table style="margin-left: auto; margin-right: auto;">
    <tr style="font-weight: bold">
        <td>ID</td>
        <td>First Name</td>
        <td>Middle Name</td>
        <td>Last Name</td>
    </tr>
    <?php
    foreach ($testRecords as $row) {
        ?>
        <tr>
            <td><?=$row->id ?></td>
            <td><?=$row->first ?></td>
            <td><?=$row->middle ?></td>
            <td><?=$row->last ?></td>
        </tr>
        <?php
    }
    ?>
</table>