<div style="text-align: center">
    <h3>Lesson Table Database Connection Test</h3>
</div>

<?php
$Dao = new LessonDao();
$Records = $Dao->getAllLessons();

?>
<table style="margin-left: auto; margin-right: auto;">
    <tr style="font-weight: bold">
        <td>Lesson ID</td>
        <td>Lesson Name</td>
		<td>Lesson Type</td>
    </tr>
    <?php
    foreach ($Records as $row) {
        ?>
        <tr>
            <td><?=$row->lessonID ?></td>
            <td><?=$row->Lesson_Name ?></td>
            <td><?=$row->Lesson_Type ?></td>
        </tr>
        <?php
    }
    ?>
</table>