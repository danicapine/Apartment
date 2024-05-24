<?php
include 'connection.php';
session_start();

// Initialize the search variable
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the query to include search functionality
$query = "SELECT * FROM recycle_bin";
if (!empty($search)) {
    $query .= " WHERE archived_table LIKE :search OR archived_data LIKE :search";
}
$query .= " ORDER BY archived_date DESC";

$stmt = $conn->prepare($query);

// Bind the search parameter if it exists
if (!empty($search)) {
    $searchParam = '%' . $search . '%';
    $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
}

$stmt->execute();
$archived_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Recycle Bin</title>
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
            background-color: #1E90FF;
            color: white;
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
            transform: scale(1.01); /* Scale up the row on hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add a subtle shadow on hover */
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
                    <i class="fa-regular fa-pen-to-square fs-6 primary-text5 rounded-full secondary-bg p-1"></i> Data Visualization
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
                    <h3 class="fs-4 m-0" id="hhh">Recycle Bin</h3>
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
                <h3 class="text-center mb-3 animated-heading">List of Deleted data</h3>
                <div class="header">
                    <div class="search-bar">
                        <form method="GET" action="A_recycle_bin.php">
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
                                    <th>Archived Date</th>
                                    <th>Archived Table</th>
                                    <th>Archived Data</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($archived_data as $data): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($data['id']); ?></td>
                                    <td><?php echo htmlspecialchars($data['archived_date']); ?></td>
                                    <td><?php echo htmlspecialchars($data['archived_table']); ?></td>
                                    <td><?php echo htmlspecialchars($data['archived_data']); ?></td>
                                    <td>
                                        <a href="A_recyclebin_view.php?id=<?php echo $data['id']; ?>" class="btn btn-info btn-sm mr-2" style="border-radius: 18px">
                                            <i class="fas fa-eye"></i> <!-- Font Awesome icon for view -->
                                        </a>
                                        <button class="btn btn-success btn-restore"  data-id="<?php echo htmlspecialchars($data['id']); ?>" style="border-radius: 18px">
                                            <i class="fas fa-trash-restore"></i>
                                        </button>
                                        <button class="btn btn-danger btn-permanent-delete" data-id="<?php echo htmlspecialchars($data['id']); ?>" style="border-radius: 18px">
                                            <i class="fas fa-trash-alt"></i> 
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-restore').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (confirm('Are you sure you want to restore this item?')) {
                    restoreData(id);
                }
            });
        });

        document.querySelectorAll('.btn-permanent-delete').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (confirm('Are you sure you want to permanently delete this item?')) {
                    permanentlyDeleteData(id);
                }
            });
        });

        function restoreData(id) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'A_restore.php?id=' + id, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    window.location.reload();
                } else {
                    console.error('Error restoring data:', xhr.statusText);
                }
            };
            xhr.onerror = function() {
                console.error('Network error occurred while restoring data');
            };
            xhr.send();
        }

        function permanentlyDeleteData(id) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'A_permanent_delete.php?id=' + id, true);
            xhr.onload = function() {
                if(xhr.status === 200) {
                    window.location.reload();
                } else {
                    console.error('Error permanently deleting data:', xhr.statusText);
                }
            };
            xhr.onerror = function() {
                console.error('Network error occurred while permanently deleting data');
            };
            xhr.send();
        }
    });
    </script>

    <!-- JS CDN Link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JS Link -->
    <script src="js/adminscript.js"></script>
</body>
</html>
