<?php
include_once("../db.php");
include_once("../student.php");

$db = new Database();
$connection = $db->getConnection();
$student = new Student($db);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js">
</script>
</head>
<body>
    <?php include('../templates/header.html'); ?>
    <?php include('../includes/navbar.php'); ?>

    
    <div class="content">
        <canvas id="birthYearChart" width="600" height="600"></canvas>

        <script>
            var birthYearData = <?php echo json_encode($student->Millennials()); ?>;
            var labels = birthYearData.map(item => item.birth_year_group);
            var dataCounts = birthYearData.map(item => item.count);
            // Chart.js code for pie chart
            var ctx = document.getElementById('birthYearChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: dataCounts,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: false, 
                }
            });
        </script>
    </div>

  <div class="content">
        <h1>Millennials student</h1>
        <p>In pie graph above we diveded the student who are born from 2000 onward and those who born before 2000. We classified the students who is born in 2000 as Millennials while those who born before as Pre-Millennials.</p>
    </div>
        <?php include('../templates/footer.html'); ?>

</body>
</html>
