<?php
session_start();

function fetchData($sql) {
    include 'connection.php';

    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch(PDOException $e) {
        return "Error: " . $e->getMessage();
    } finally {
        $conn = null;
    }
}

$sql1 = "SELECT COUNT(*) AS total_rooms FROM rooms";
$sql2 = "SELECT COUNT(*) AS total_tenants FROM tenants";
$sql3 = "SELECT COUNT(*) AS total_duedate FROM payments";
$sql4 = "SELECT COUNT(*) AS total_requests FROM requests";
$sql5 = "SELECT id, roomName, roomPrice, roomPicture, Status FROM rooms WHERE Status = 'Available'";

$result1 = fetchData($sql1);
$result2 = fetchData($sql2);
$result3 = fetchData($sql3);
$result4 = fetchData($sql4);
$rooms = fetchData($sql5);

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'user';
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
    <link rel="stylesheet" href="css/adminstyle.css"/>
    <title>Dashboard</title>
    <style>
       h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            background-color: #FFFFFF;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #FFFFFF;
            color: #000000;
        }
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.4);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            text-align: center;
            vertical-align: middle !important;
        }
        .btn {
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-3px);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }
        .action-btns {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .animated-heading {
            animation: wave 2s ease-in-out infinite;
        }
        tbody tr:hover {
            background-color: #87CEEB;
            cursor: pointer;
            transform: scale(1.01);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .shadow-hover {
            position: relative;
            transition: all 0.3s ease;
        }
        .shadow-hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0);
            z-index: -1;
            transition: background-color 0.3s ease;
        }
        .shadow-hover:hover::before {
            background-color: rgba(255, 255, 255, 0.5);
        }
        .shadow-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
                <h4>TTPD Homes</h4>
            </div>
            <div class="list-group list-group-flush my-3">
                <li class="nav-item nav-category">
                    <span class="nav-link" id="nav">Navigation</span>
                </li>
                <a href="A_index.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-home fs-6 primary-text1 rounded-full secondary-bg p-1"></i> Dashboard
                </a>
                <a href="A_tenant_info.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-info-circle fs-6 primary-text2 rounded-full secondary-bg p-1"></i> Tenant's Info
                </a>
                <a href="A_rooms.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-warehouse fs-6 primary-text3 rounded-full secondary-bg p-1"></i> Rooms
                </a>
                <a href="A_payments.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fa-solid fa-credit-card fs-6 primary-text4 rounded-full secondary-bg p-1"></i> Payments
                </a>
                <a href="A_edit.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fa-regular fa-pen-to-square fs-6 primary-text5 rounded-full secondary-bg p-1"></i> Edit
                </a>
                <a href="A_concern.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fa-regular fa-comment-dots fs-6 primary-text6 rounded-full secondary-bg p-1"></i> Concern/Request
                </a>
                <a href="A_recycle_bin.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fa-solid fa-recycle fs-6 primary-text8 rounded-full secondary-bg p-1"></i> Recycle Bin
                </a>
                <a href="A_logout.php" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold">
                    <i class="fas fa-power-off me-2"></i> Logout
                </a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-align-left dashicon fs-4 me-3" id="menu-toggle"></i>
                    <h3 class="fs-4 m-0" id="hhh">Admin Dashboard</h3>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
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

            <div class="container-fluid px-4">
                <div class="row g-3 my-2">
                    <div class="col-md-3">
                        <div class="p-3 shadow-sm d-flex justify-content-around align-items-center rounded shadow-hover" id="room">
                            <div>
                                <?php
                                if (!is_array($result1)) {
                                    echo $result1; // Error occurred
                                } else {
                                    echo "<h3 class='fs-2'>" . $result1[0]['total_rooms'] . "</h3>";
                                }
                                ?>
                                <p class="fs-5">Available Rooms</p>
                            </div>
                            <i class="fa-solid fa-shop fs-1 primary-text11 rounded-full secondary-bg1 p-3"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 shadow-sm d-flex justify-content-around align-items-center rounded shadow-hover" id="tenant">
                            <div>
                                <?php
                                if (!is_array($result2)) {
                                    echo $result2; // Error occurred
                                } else {
                                    echo "<h3 class='fs-2'>" . $result2[0]['total_tenants'] . "</h3>";
                                }
                                ?>
                                <p class="fs-5">Total Of Tenants</p>
                            </div>
                            <i class="fa-solid fa-people-roof fs-1 primary-text12 rounded-full secondary-bg1 p-3"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 shadow-sm d-flex justify-content-around align-items-center rounded shadow-hover" id="duedate">
                            <div>
                                <?php
                                if (!is_array($result3)) {
                                    echo $result3; // Error occurred
                                } else {
                                    echo "<h3 class='fs-2'>" . $result3[0]['total_duedate'] . "</h3>";
                                }
                                ?>
                                <p class="fs-5">Due Date</p>
                            </div>
                            <i class="fa-regular fa-calendar-days fs-1 primary-text13 rounded-full secondary-bg1 p-3"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 shadow-sm d-flex justify-content-around align-items-center rounded shadow-hover" id="request">
                            <div>
                                <?php
                                if (!is_array($result4)) {
                                    echo $result4; // Error occurred
                                } else {
                                    echo "<h3 class='fs-2'>" . $result4[0]['total_requests'] . "</h3>";
                                }
                                ?>
                                <p class="fs-5">Request/Concern</p>
                            </div>
                            <i class="fa-solid fa-bell fs-1 primary-text14 rounded-full secondary-bg1 p-3"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid px-4">
                <div class="row g-3 my-2"></div>
                <div class="row my-5">
                <h3 class="text-center mb-3 animated-heading">List of Rooms</h3>
                <div class="header">
                <?php
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                ?>
                    <div class="search-bar">
                    <form method="GET" action="A_rooms.php">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>" style="border-top-left-radius: 18px; border-bottom-left-radius: 18px;">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" style="border-top-right-radius: 18px; border-bottom-right-radius: 18px;"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead style="background-color:#1E90FF; color: white;">
                            <tr>
                                <th>ID</th>
                                <th>Room Name</th>
                                <th>Price</th>
                                <th>Picture</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rooms as $room): ?>
                                <tr>
                                    <td><?php echo $room['id']; ?></td>
                                    <td><?php echo $room['roomName']; ?></td>
                                    <td><?php echo $room['roomPrice']; ?></td>
                                    <td>
                                        <?php if (!empty($room['roomPicture'])): ?>
                                            <img src="<?php echo htmlspecialchars($room['roomPicture']); ?>" alt="Room Image" style="width: 100px; height: auto;">
                                        <?php else: ?>
                                            <span>No Image Available</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $room['Status']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JS CDN Link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JS Link -->
    <script src="js/adminscript.js"></script>
</body>
</html>
