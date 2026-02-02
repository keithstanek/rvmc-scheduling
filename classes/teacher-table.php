<div style="text-align: center">
    <h3>Teacher Table Database Connection Test</h3>
</div>

<?php
$Dao = new TeacherDao();
$Records = $Dao->getAllTeachers();

?>
<table style="margin-left: auto; margin-right: auto;">
    <tr style="font-weight: bold">
        <td>TeacherID</td>
        <td>teacher_name</td>
        <td>teacher_image</td>
        <td>teacher_phone</td>
    </tr>
    <?php
    foreach ($Records as $row) {
        ?>
        <tr>
            <td><?=$row->TeacherID ?></td>
            <td><?=$row->teacher_name ?></td>
            <td><?=$row->teacher_image ?></td>
            <td><?=$row->teacher_phone ?></td>
        </tr>
        <?php
    }
    ?>
</table>