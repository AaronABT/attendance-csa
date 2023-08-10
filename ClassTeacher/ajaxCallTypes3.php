<?php

include '../Includes/dbcon.php';

// get all the event names
$sql = "SELECT * FROM `tblevents`";
$result = $conn->query($sql);
echo "<select name='event' id='event' class='form-control'>";
while ($row = $result->fetch_assoc()) {
    unset($id, $name);
    $id = $row['Id'];
    $name = $row['eventName'];
    echo '<option value="' . $id . '">' . $name . '</option>';
}
echo "</select>";
?>

