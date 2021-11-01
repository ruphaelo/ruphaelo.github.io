<?php
session_start();

$table =$_SESSION['varTable'];

$id = '1';

$db = "m138026";

$c = mysqli_connect("localhost","root","",$db);

$sql = "SELECT row4 FROM $table WHERE id=$id";

$result = $c->query($sql);

while($row = $result->fetch_assoc()) {
  echo $row['row4'];
}

exit();
?>