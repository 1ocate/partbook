<?php
session_start();
require_once '../config.php';
require_once '../dbh.php';


//get search term
$searchTerm = $_GET['term'];
$searchPartno = $_GET['partno'];
//get matched data from table
//$query = $conn->query("SELECT partno FROM part_list WHERE partno LIKE '%".$searchTerm."%' ORDER BY nama ASC limit 100");
//$query = $conn->query("SELECT distinct machine FROM part_list WHERE partno = ".$searchPartno."' limit 10");
//$query = $conn->query("SELECT distinct machine FROM part_list  WHERE partno in (SELECT distinct machine FROM part_list WHERE partno = '".$searchPartno."') and machine LIKE '%".$searchTerm."%'  limit 100");
$query = $conn->query("SELECT distinct machine FROM part_list  WHERE partno = '".$searchPartno."' and machine LIKE '%".$searchTerm."%'  limit 100");


while ($row = $query->fetch_assoc()) {
    $data[] = $row['machine'];
}
//return json data
echo json_encode($data);
?>

