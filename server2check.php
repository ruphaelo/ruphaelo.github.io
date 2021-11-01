<?php
session_start();

$table =$_SESSION['varTable'];

$id = '1';

$db = "m138026";

$c = mysqli_connect("localhost","root","",$db);

$sql = "SELECT * FROM $table WHERE id=$id";

$result = $c->query($sql);

while($row = $result->fetch_assoc()) {
  echo $row['row3'] . 'XXXhereXXXcomesXXXaXXXnewXXXarrayXXX' . $row['row4'] . 'XXXhereXXXcomesXXXaXXXnewXXXarrayXXX' . $row['row5'] . 'XXXhereXXXcomesXXXaXXXnewXXXarrayXXX' . $row['row6'] . 'XXXhereXXXcomesXXXaXXXnewXXXarrayXXX' . $row['row7'];
}

exit();
?>