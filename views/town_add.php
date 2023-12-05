<?php
include_once("../db.php");
include_once("../town_city.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [    
    
    'name' => $_POST['name'],
    ];

    
    $database = new Database();
    $town_city = new TownCity($database);
    $town_city_id = $town_city->create($data);
    
    echo "Record added successfully.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/styles.css">

    <title>Add Student Data</title>
</head>
<body>
    
    <?php include('../templates/header.html'); ?>
    <?php include('../includes/navbar.php'); ?>

    <div class="content">
    <h1>Add Town City</h1>
    <form action="" method="post" class="centered-form">


        <label for="name">Town Name:</label>
        <input type="text" name="name" id="name" required>

        <input type="submit" value="Add Town City">
    </form>
    </div>
    
    <?php include('../templates/footer.html'); ?>
</body>
</html>
