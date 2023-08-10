<?php

include '../Includes/dbcon.php';

$cid = intval($_GET['cid']);

$queryss = $conn_pdo->prepare("SELECT * FROM tblclassdivision WHERE classId = ?");
$queryss->execute([$cid]);
$countt = $queryss->rowCount();

echo '<select required name="classArmId" class="form-control mb-3">';
echo '<option value="">--Select Class Arm--</option>';

while ($row = $queryss->fetch(PDO::FETCH_ASSOC)) {
    echo '<option value="'.$row['Id'].'">'.$row['classArmName'].'</option>';
}

echo '</select>';

?>
