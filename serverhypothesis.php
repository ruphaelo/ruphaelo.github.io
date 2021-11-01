<?php
session_start();

$table =$_SESSION['varTable'];

$id = '1';

$db = "m138026";

$c = mysqli_connect("localhost","root","",$db);

$a=$_POST['data'];

$sql = "UPDATE $table SET row5 = '$a' WHERE id = $id";

$c->query($sql);

mysqli_query($c, $queryInput);

exit();
?>