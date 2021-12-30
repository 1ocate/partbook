<?php
session_start();
require_once 'config.php';
require_once 'dbh.php';


//get search term
//$searchTerm = $_GET['term'];
//get matched data from table
$query = $conn->query("SELECT partno FROM part_list");
while ($row = $query->fetch_assoc()) {
    $data[] = $row['partno'];
}
//return json data
echo json_encode($data);
?>

