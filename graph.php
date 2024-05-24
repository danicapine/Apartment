<?php
// Database connection details
$host = 'localhost';
$dbname = 'rental_system';
$username = 'root';
$password = '';

try {
    // Connect to the database using PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to get the counts of available and occupied rooms
    $sql = "SELECT COUNT(CASE WHEN Status = 'Available' THEN 1 END) AS available_rooms,
                   COUNT(CASE WHEN Status = 'Occupied' THEN 1 END) AS occupied_rooms
            FROM rooms";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $availableRooms = $result['available_rooms'];
    $occupiedRooms = $result['occupied_rooms'];

    // Query to get the room names and prices
    $sql = "SELECT roomName, roomPrice FROM rooms";
    $stmt = $pdo->query($sql);
    $roomData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Query to get the tenant information with the number of months stayed
    $sql = "SELECT tenants.id, tenants.userid, tenants.rentStartDate, TIMESTAMPDIFF(MONTH, tenants.rentStartDate, CURDATE()) AS monthsStayed
            FROM tenants";
    $stmt = $pdo->query($sql);
    $tenantData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Query to get the payment information with the days remaining until due date
    $sql = "SELECT payments.id, payments.paymentDueDate, payments.userid, DATEDIFF(payments.paymentDueDate, CURDATE()) AS daysRemaining
            FROM payments";
    $stmt = $pdo->query($sql);
    $paymentData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Room Availability, Price Charts, Months Stayed, and Payment Due Dates</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Custom CSS para sa container ng mga graph */
        .chart-container {
            position: relative;
            width: 100%;
            margin: 0 auto;
            display: flex;
            justify-content: center;
        }

        /* Custom CSS para sa mga graph */
        canvas.chart {
            max-width: 300px;
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <h4 class="text-center my-4">Room Availability Pie Chart</h4>
                <div class="chart-container">
                    <canvas id="roomPieChart" class="chart"></canvas>
                </div>
            </div>
            <div class="col-md-3">
                <h4 class="text-center my-4">Room Price Line Chart</h4>
                <div class="chart-container">
                    <canvas id="roomLineChart" class="chart"></canvas>
                </div>
            </div>
            <div class="col-md-3">
                <h4 class="text-center my-4">Months Stayed Bar Chart</h4>
                <div class="chart-container">
                    <canvas id="monthsStayedChart" class="chart"></canvas>
                </div>
            </div>
            <div class="col-md-3">
                <h4 class="text-center my-4">Payment Due Dates Line Chart</h4>
                <div class="chart-container">
                    <canvas id="paymentDueDatesChart" class="chart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Pie Chart
        var ctx1 = document.getElementById('roomPieChart').getContext('2d');
        var roomPieChart = new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: ['Available Rooms', 'Occupied Rooms'],
                datasets: [{
                    data: [<?php echo $availableRooms . ', ' . $occupiedRooms; ?>],
                    backgroundColor: ['#28a745', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Line Chart
        var ctx2 = document.getElementById('roomLineChart').getContext('2d');
        var roomLineChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: [<?php echo '"' . implode('", "', array_column($roomData, 'roomName')) . '"'; ?>],
                datasets: [{
                    label: 'Room Price',
                    data: [<?php echo implode(', ', array_column($roomData, 'roomPrice')); ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Bar Chart
        var ctx3 = document.getElementById('monthsStayedChart').getContext('2d');
        var monthsStayedChart = new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: [<?php echo '"' . implode('", "', array_column($tenantData, 'userid')) . '"'; ?>],
                datasets: [{
                    label: 'Months Stayed',
                    data: [<?php echo implode(', ', array_column($tenantData, 'monthsStayed')); ?>],
                    backgroundColor: 'rgba(255, 159, 64, 0.5)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });

        // Line Chart for Payment Due Dates
        var ctx4 = document.getElementById('paymentDueDatesChart').getContext('2d');
        var paymentDueDatesChart = new Chart(ctx4, {
            type: 'line',
            data: {
                labels: [<?php echo '"' . implode('", "', array_column($paymentData, 'userid')) . '"'; ?>],
                datasets: [{
                    label: 'Days Remaining',
                    data: [<?php echo implode(', ', array_column($paymentData, 'daysRemaining')); ?>],
                    backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
responsive: true,
maintainAspectRatio: false,
scales: {
y: {
beginAtZero: true,
precision: 0
}
}
}
});
</script>

</body>
</html>