<?php
include "dblogin.php";
 
if($db === false) die("ERROR: Could not connect. " . mysqli_connect_error());
 
// $sql = "CREATE DATABASE videogames";
// if(mysqli_query($db, $sql)){
    // echo "Database created successfully";
// } else{
    // echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
// }

$sql = "CREATE TABLE consoles(console_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, name CHAR(70) NOT NULL)";
if (mysqli_query($db, $sql)){
    echo "Table created successfully";
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
}

$sql = "INSERT INTO consoles(console_id, name) VALUES (1, 'Wii U')";
if(mysqli_query($db, $sql)){
    echo "Records added successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
}
 
// Close connection
mysqli_close($db);
?>