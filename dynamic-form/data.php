<?php
session_start();
require_once '../config.php';
require_once '../dbh.php';


//get search term
$searchTerm = $_GET['term'];
if (!$searchTerm =="") {
//get matched data from table
//$query = $conn->query("SELECT partno FROM part_list WHERE partno LIKE '%".$searchTerm."%' ORDER BY nama ASC limit 100");
$query = $conn->query("SELECT distinct partno, partname, partnameeng FROM part_list  WHERE partno LIKE '%".$searchTerm."%' and partname is not null  limit 10");
while ($row = $query->fetch_assoc()) {
    $data[] = $row['partno']." ".$row['partname'];
}
//return json data
echo json_encode($data);
}
?>

