<?php
include'./DB_Connect.php';

$query = "SELECT * from books;";
$stmt = $db->query($query);
 
while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
  echo $row['name']."<b>：</b> ";
  echo $row['author']."( ";
  echo $row['publisher']." )<br/>";
 }

?>

