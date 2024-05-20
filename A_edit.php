<?php
session_start();

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
    <title>Edit</title>
    <style>
       h3 {
        text-align: center;
        margin-bottom: 20px;
        }

        table {
        background-color: #FFFFFF; /* White background */
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
        }

        th, td {
        padding: 10px;
        text-align: center;
        }

        th {
        background-color: #FFFFFF; /* White background */
        color: #000000; /* Black text */
        }
    </style>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom"><i
                       ></i><h4>TTPD Homes</h4></div>
            <div class="list-group list-group-flush my-3">
            <li class="nav-item nav-category">
            <span class="nav-link" id="nav">Navigation</span>
            </li>
                <a href="A_index.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                        class="fas fa-home fs-6 primary-text1 rounded-full secondary-bg p-1"></i> Dashboard</a>
                <a href="A_tenant_info.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                        class="fas fa-info-circle fs-6 primary-text2 rounded-full secondary-bg p-1"></i> Tenant's Info</a>
                <a href="A_rooms.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                        class="fas fa-warehouse fs-6 primary-text3 rounded-full secondary-bg p-1"></i> Rooms</a>
                <a href="A_payments.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                        class="fa-solid fa-credit-card fs-6 primary-text4 rounded-full secondary-bg p-1"></i> Payments</a>
                <a href="A_edit.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                        class="fa-regular fa-pen-to-square fs-6 primary-text5 rounded-full secondary-bg p-1"></i> Edit</a>
                <a href="A_concern.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                        class="fa-regular fa-comment-dots fs-6 primary-text6 rounded-full secondary-bg p-1"></i> Concern/Request</a>
                <a href="A_recycle_bin.php" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                        class="fa-solid fa-recycle fs-6 primary-text8 rounded-full secondary-bg p-1"></i> Recycle Bin</a>
                <a href="A_logout.php" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold"><i
                        class="fas fa-power-off me-2"></i> Logout</a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-align-left dashicon fs-4 me-3" id="menu-toggle"></i>
                    <h3 class="fs-4 m-0" id="hhh">Edit user interfaces</h3>
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
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="#">Settings</a></li>
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid px-4">
                <div class="row g-3 my-2"></div>
                <div class="row my-5">
                    <h3 class="fs-4 mb-3">List of Available Rooms</h3>
                    <div class="col">
                        <table class="table bg-white rounded shadow-sm  table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Room ID</th>
                                    <th scope="col">Room Name</th>
                                    <th scope="col">Room Price</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">101</th>
                                    <td>Dorotheia</td>
                                    <td>$50,000</td>
                                    <td>Available</td>
                                </tr>
                                <tr>
                                    <th scope="row">101</th>
                                    <td>Dorotheia</td>
                                    <td>$50,000</td>
                                    <td>Available</td>
                                </tr>
                                <tr>
                                    <th scope="row">101</th>
                                    <td>Dorotheia</td>
                                    <td>$50,000</td>
                                    <td>Available</td>
                                </tr>
                                <tr>
                                    <th scope="row">101</th>
                                    <td>Dorotheia</td>
                                    <td>$50,000</td>
                                    <td>Available</td>
                                </tr>
                                <tr>
                                    <th scope="row">101</th>
                                    <td>Dorotheia</td>
                                    <td>$50,000</td>
                                    <td>Available</td>
                                </tr>
                                
                            </tbody>
                        </table>
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
</body>

</html>