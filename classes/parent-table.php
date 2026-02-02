<div style="text-align: center">
    <h3>Parent Table Database Connection Test</h3>
</div>

<?php
$Dao = new ParentDao();
$Records = $Dao->getAllParents();

?>
<table style="margin-left: auto; margin-right: auto;">
    <tr style="font-weight: bold">
        <td>Parent ID</td>
        <td>First Name</td>
		<td>Last Name</td>
        <td>Email</td>
        <td>Phone</td>
    </tr>
    <?php
    foreach ($Records as $row) {
        ?>
        <tr>
            <td><?=$row->parent_id ?></td>
            <td><?=$row->first_name ?></td>
            <td><?=$row->last_name ?></td>
            <td><?=$row->email ?></td>
			<td><?=$row->phone ?></td>
        </tr>
        <?php
    }
    ?>
</table>