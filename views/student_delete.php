<?php
include_once("../db.php"); 
include_once("../student.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id']; 

    
    $db = new Database();
    $student = new Student($db);

    
    if ($student->delete($id)) {
        echo "Record deleted successfully.";
    } else {
        echo "Failed to delete the record.";
    }
}
?>
