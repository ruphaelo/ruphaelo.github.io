<?php

session_start(); // start session for name of table  - after login

//-------------------------------------------------------------------------------------

$db = "m138026"; // same data base NoteTakingLogin.php

$table ="user"; // all users in one table

$d = mysqli_connect("localhost","root","");  // connect to mysql                         *change here*


//-------------------------------------------------------------------------------------


$queryDB = "CREATE DATABASE $db";         //create DB                          
$result = mysqli_query($d, $queryDB);

if(!$result){
 echo mysqli_error($d);
}
else{
 echo "Database was created";
}

//-------------------------------------------------------------------------------------

$c = mysqli_connect("localhost","root","","$db"); // connection to DB                     *change here*

if($c){
 echo "Connection has been successfully established.";
} else {
 echo "Could not connect to database. ";
}

//-------------------------------------------------------------------------------------



if(isset($_POST['registrate'])) {  // if click registrate


//. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . 



//. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . 

if (!empty($_POST)){

$user=$_POST['textfieldUser']; // get texfield
$pass=$_POST['textfieldPass'];
$clientdata='';

#PETER Hash the password to hide the plain text password!
$pass = password_hash($pass, PASSWORD_DEFAULT);

}

$queryTableTest= "CREATE TABLE IF NOT EXISTS `$user` (id INT NOT NULL AUTO_INCREMENT,row1 VARCHAR(30),row2 VARCHAR(255), row3 VARCHAR(10000), row4 VARCHAR(10000), row5 VARCHAR(10000), row6 VARCHAR(10000), row7 VARCHAR(10000), PRIMARY KEY (id))";  // create table with all users
$result = mysqli_query($c, $queryTableTest);

//. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . 

echo "<br><br><p>all registered users with password / numbers :</p>";

//. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . 

$queryGetTable = "SELECT * FROM $user";
$resultTable = mysqli_query($c, $queryGetTable);

$boolUserExists = False; // if user already exists

//. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . 

while($value = mysqli_fetch_assoc($resultTable)){ 
  $getValue0 = $value['id'];
  $getValue1 = $value['row1'];
  $getValue2 = $value['row2'];
  $getValue3 = $value['row3'];
  $getValue4 = $value['row4'];
  $getValue5 = $value['row5'];
  $getValue6 = $value['row6'];
  $getValue7 = $value['row7'];

    #PETER: Removed is_int call because password is now a hash
		if ($getValue1==$user || $user=="" ){  // check if user already exists or textfield = "" ......
				$boolUserExists = True;       
		   }

echo "<br />  <label>$getValue0 : $getValue1 $getValue2 $getValue3 $getValue4 $getValue5 $getValue6 $getValue7 </label> <br />"; // print registred users
}

//. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . 

if ( $boolUserExists == False){  // if user not exists add user + auto increment id

#PETER: Let's use a prepared statement to make the query more robust!

// 1. Step: Prepare the query

$queryString = "INSERT INTO $user (row1, row2, row3, row4, row5, row6, row7) VALUES (?,?,?,?,?,?,?)";
$statement = $c->prepare($queryString);

// 2. Step: Bind the parameters

$statement->bind_param('sssssss', $user, $pass, $clientdata, $clientdata, $clientdata, $clientdata, $clientdata);

// 3. Step: Execute the query
$statement->execute();

// $queryInput = "INSERT INTO $user (row1,row2,row3,row4,row5,row6,row7) VALUES ('$user','$pass','$clientdata','$clientdata','$clientdata','$clientdata','$clientdata') ";
// $result = mysqli_query($c, $queryInput);

// $queryInputID = "ALTER TABLE $user ADD id MEDIUMINT NOT NULL AUTO_INCREMENT KEY";
// mysqli_query($c, $queryInputID);

echo "<br> $user | $pass | $clientdata |  $clientdata |  $clientdata |  $clientdata |  $clientdata |  created"; // print user created

$_SESSION['id'] =$getValue0;
$_SESSION['varTable'] =$user; // save "user" in session for creating a note-table with the name after login
$_SESSION['clientdata1'] =$getValue3;
$_SESSION['clientdata2'] =$getValue4;
$_SESSION['clientdata3'] =$getValue5;
$_SESSION['clientdata4'] =$getValue6;
$_SESSION['clientdata5'] =$getValue7;

header("Location:steps.html"); // login if user and password are already registered and correct
exit();
session_destroy();
header("Location:home.html");
//echo  " <a href=\"steps.html\">notes</a> <br>";
}
else {
header("Location:home.html"); // login if user and password are already registered and correct´
echo "<br> username exists already or wrong input";// print user not created
}
}
//-------------------------------------------------------------------------------------

if(isset($_POST['login'])) {  // if click login

echo "login<br>";

//. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . 

$userLog=$_POST['textfieldLogUser']; // get textfield
$passLog=$_POST['textfieldLogPass'];

//. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . 

$queryGetTable = "SELECT * FROM $userLog";
$resultTable = mysqli_query($c, $queryGetTable);

//. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . 

while($value = mysqli_fetch_assoc($resultTable)){ 

  $getValue0 = $value['id'];
  $getValue1 = $value['row1'];
  $getValue2 = $value['row2'];
  $getValue3 = $value['row3'];
  $getValue4 = $value['row4'];
  $getValue5 = $value['row5'];
  $getValue6 = $value['row6'];
  $getValue7 = $value['row7'];



// if ( $getValue1 ==$userLog && $getValue2 ==$passLog){   //check if user and password are in table "user"
#PETER: Verify the password hash with password_verify
$verified = password_verify($passLog, $getValue2);
if ( $getValue1 ==$userLog && $verified){   //check if user and password are in table "user"

echo "yey<br>";

$_SESSION['id'] =$getValue0;
$_SESSION['varTable'] =$userLog; // save "user" in session for creating a note-table with the name after login
$_SESSION['clientdata1'] =$getValue3;
$_SESSION['clientdata2'] =$getValue4;
$_SESSION['clientdata3'] =$getValue5;
$_SESSION['clientdata4'] =$getValue6;
$_SESSION['clientdata5'] =$getValue7;

header("Location:steps.html"); // login if user and password are already registered and correct
exit();
session_destroy();
header("Location:home.html");
//echo  " <a href=\"steps.html\">notes</a> <br>";

}
else{
header("Location:home.html"); // login if user and password are already registered and correct´
}
}

}
//. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . 
//-------------------------------------------------------------------------------------



?>
