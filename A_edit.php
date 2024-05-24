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
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- CSS CDN Link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS Link -->
    <link rel="stylesheet" href="css/adminstyle.css" />
    <style>
        h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            background-color: #ffffff;
            /* White background */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Soft shadow */
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #ffffff;
            /* White background */
            color: #000000;
            /* Black text */
        }

        /* Custom CSS para sa container ng mga graph */
        .chart-container {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        /* Custom CSS para sa mga graph */
        canvas.chart {
            max-width: 300px;
            width: 100%;
            height: auto;
        }

        /* Adjustments for smaller screens */
        @media (max-width: 768px) {
            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
                /* Baguhin ang kulay ng background ng graph */
        .chart-container {
            background-color: #ffffff; /* Baguhin ang kulay ayon sa iyong pagnanais */
        }

        /* Baguhin ang kulay ng mga linya sa mga graph */
        .chart-container canvas.chart {
            border-color: #ffffff; /* Baguhin ang kulay ayon sa iyong pagnanais */
        }

        /* Baguhin ang kulay ng mga label at text sa graph */
        .chart-container .chart-title,
        .chart-container .chart-label {
            color: #000000; /* Baguhin ang kulay ayon sa iyong pagnanais */
        }
    </style>
    <title>Data Visualization</title>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom"><i></i><h4>TTPD Homes</h4></div>
            <div class="list-group list-group-flush my-3">
                <li class="nav-item nav-category">
                    <span class="nav-link" id="nav">Navigation</span>
                </li>
                <a href="A_index.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i class="fas fa-home fs-6 primary-text1 rounded-full secondary-bg p-1"></i> Dashboard</a>
                <a href="A_tenant_info.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i class="fas fa-info-circle fs-6 primary-text2 rounded-full secondary-bg p-1"></i> Tenant's Info</a>
                <a href="A_rooms.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i class="fas fa-warehouse fs-6 primary-text3 rounded-full secondary-bg p-1"></i> Rooms</a>
                <a href="A_payments.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i class="fa-solid fa-credit-card fs-6 primary-text4 rounded-full secondary-bg p-1"></i> Payments</a>
                <a href="A_edit.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i class="fa-regular fa-pen-to-square fs-6 primary-text5 rounded-full secondary-bg p-1"></i> Data Visualization</a>
                <a href="A_concern.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i class="fa-regular fa-comment-dots fs-6 primary-text6 rounded-full secondary-bg p-1"></i> Concern/Request</a>
                <a href="A_recycle_bin.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i class="fa-solid fa-recycle fs-6 primary-text8 rounded-full secondary-bg p-1"></i> Recycle Bin</a>
                <a href="A_logout.php" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold"><i class="fas fa-power-off me-2"></i> Logout</a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-align-left dashicon fs-4 me-3" id="menu-toggle"></i>
                    <h3 class="fs-4 m-0" id="hhh">Data Visualization</h3>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle second-text fw-bold" href="#" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white">
                                <i class="fas fa-user me-2" style="color: white"></i><?php echo htmlspecialchars($username); ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="A_edit_profile.php">Edit Profile</a></li>
                                <li><a class="dropdown-item" href="A_logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="text-center my-4">Room Availability Pie Chart</h4>
                        <div class="chart-container">
                            <canvas id="roomPieChart" class="chart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 class="text-center my-4">Room Price Line Chart</h4>
                        <div class="chart-container">
                            <canvas id="roomLineChart" class="chart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 class="text-center my-4">Months Stayed Bar Chart</h4>
                        <div class="chart-container">
                            <canvas id="monthsStayedChart" class="chart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 class="text-center my-4">Payment Due Dates Line Chart</h4>
                        <div class="chart-container">
                            <canvas id="paymentDueDatesChart" class="chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /#page-content-wrapper -->
    </div>

    <!-- JS CDN Link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JS Link -->
    <script src="js/adminscript.js"></script>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart.js code here
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

