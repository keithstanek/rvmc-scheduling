<div style="text-align: center">
    <h3>Student Table Database Connection Test</h3>
</div>

<?php
$Dao = new StudentDao();
$Records = $Dao->getAllStudents();

?>
<table style="margin-left: auto; margin-right: auto;">
    <tr style="font-weight: bold">
        <td>Student ID</td>
        <td>First Name</td>
		<td>Last Name</td>
        <td>Date of Birth</td>
        <td>Parent ID</td>
    </tr>
    <?php
    foreach ($Records as $row) {
        ?>
        <tr>
            <td><?=$row->student_id ?></td>
            <td><?=$row->first_name ?></td>
            <td><?=$row->last_name ?></td>
            <td><?=$row->dob ?></td>
			<td><?=$row->parent_id ?></td>
        </tr>
        <?php
    }
    ?>
</table>