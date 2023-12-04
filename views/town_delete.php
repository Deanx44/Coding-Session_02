<?php
include("../db.php"); 
include("../town_city.php"); 

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id']; 

    
    $db = new Database();
    $town_city = new TownCity($db);

    
    if ($town_city->delete($id)) {
        echo "Record deleted successfully.";
    } else {
        echo "Failed to delete the record.";
    }
    header("Location: towns.view.php");
}
?>