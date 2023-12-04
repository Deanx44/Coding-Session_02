<?php
include("../db.php"); 
include("../student.php"); 
include("../student_details.php"); 

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id']; 

    
    $db = new Database();
    $student = new Student($db);
    $details = new StudentDetails($db);

    // Call the delete method to delete the student record
    if ($student->delete($id) && $details->delete($id)) {
        echo "Record deleted successfully.";
        header("Location: students.view.php");
    } else {
        echo "Failed to delete the record.";
    }
}
?>
