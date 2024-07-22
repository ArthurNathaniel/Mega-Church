<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}


$total_members_query = "SELECT COUNT(*) FROM members";
$total_members_result = $conn->query($total_members_query);
$total_members = $total_members_result->fetch_row()[0];


$gender_query = "SELECT gender, COUNT(*) as count FROM members GROUP BY gender";
$gender_result = $conn->query($gender_query);
$gender_data = [];
while ($row = $gender_result->fetch_assoc()) {
    $gender_data[$row['gender']] = $row['count'];
}


$marital_status_query = "SELECT marital_status, COUNT(*) as count FROM members GROUP BY marital_status";
$marital_status_result = $conn->query($marital_status_query);
$marital_status_data = [];
while ($row = $marital_status_result->fetch_assoc()) {
    $marital_status_data[$row['marital_status']] = $row['count'];
}


$ministry_query = "SELECT ministries.ministry_name, COUNT(members.id) as count FROM members 
JOIN ministries ON members.ministry_id = ministries.id GROUP BY ministries.ministry_name";
$ministry_result = $conn->query($ministry_query);
$ministry_data = [];
while ($row = $ministry_result->fetch_assoc()) {
    $ministry_data[$row['ministry_name']] = $row['count'];
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/dashboard.css">

</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="all">
    <div class="heading">
    <h2>Members Statistic </h2>
    </div>
  
        <div class="chart_all">
            <div class="chart">
                <h3>Total Members</h3>
                <canvas id="totalMembersChart"></canvas>
            </div>
            <div class="chart">
                <h3>Gender Distribution</h3>
                <canvas id="genderChart"></canvas>
            </div>
            <div class="chart">
                <h3>Marital Status Distribution</h3>
                <canvas id="maritalStatusChart"></canvas>
            </div>
            <div class="chart">
                <h3>Ministry Distribution</h3>
                <canvas id="ministryChart"></canvas>
            </div>
        </div>
    </div>
    <script>
      
        var ctx = document.getElementById('totalMembersChart').getContext('2d');
        var totalMembersChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Members'],
                datasets: [{
                    label: 'Number of Members',
                    data: [<?php echo $total_members; ?>],
                    backgroundColor: '#007bff'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        
        var ctx = document.getElementById('genderChart').getContext('2d');
        var genderChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_keys($gender_data)); ?>,
                datasets: [{
                    label: 'Gender Distribution',
                    data: <?php echo json_encode(array_values($gender_data)); ?>,
                    backgroundColor: ['#ff6384', '#36a2eb']
                }]
            }
        });

    
        var ctx = document.getElementById('maritalStatusChart').getContext('2d');
        var maritalStatusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_keys($marital_status_data)); ?>,
                datasets: [{
                    label: 'Marital Status Distribution',
                    data: <?php echo json_encode(array_values($marital_status_data)); ?>,
                    backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56', '#4bc0c0']
                }]
            }
        });

      
        var ctx = document.getElementById('ministryChart').getContext('2d');
        var ministryChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_keys($ministry_data)); ?>,
                datasets: [{
                    label: 'Ministry Distribution',
                    data: <?php echo json_encode(array_values($ministry_data)); ?>,
                    backgroundColor: '#ffcd56'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script src="./js/sidebar.js"></script>
</body>

</html>