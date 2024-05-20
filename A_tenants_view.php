<?php
include 'connection.php';

$id = $_GET['id'];
$sql = "SELECT u.name, u.email, u.user_type, t.rentStartDate, t.address, t.phoneNumber, r.roomName 
        FROM tenants t
        INNER JOIN users u ON t.userId = u.id
        INNER JOIN rooms r ON t.roomId = r.id
        WHERE t.id = :id";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$tenant = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tenant</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        }
        h3 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            color: #555;
        }
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 4px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            background-color: #f8f9fa;
            color: #333;
            padding: 10px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s;
            display: block;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        /* Additional Styles */
        input[type="text"], input[type="email"] {
            width: 100%;
            box-sizing: border-box;
        }
        .form-group label {
            margin-bottom: 5px;
            display: block;
        }
        .form-group:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>View Tenant</h3>
        <div class="form-group">
            <label>Name:</label>
            <input type="text" class="form-control" value="<?php echo $tenant['name']; ?>" readonly>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" class="form-control" value="<?php echo $tenant['email']; ?>" readonly>
        </div>
        <div class="form-group">
            <label>User Type:</label>
            <input type="text" class="form-control" value="<?php echo $tenant['user_type']; ?>" readonly>
        </div>
        <div class="form-group">
            <label>Room Name:</label>
            <input type="text" class="form-control" value="<?php echo $tenant['roomName']; ?>" readonly>
        </div>
        <div class="form-group">
            <label>Rent Start Date:</label>
            <input type="text" class="form-control" value="<?php echo $tenant['rentStartDate']; ?>" readonly>
        </div>
        <div class="form-group">
            <label>Address:</label>
            <input type="text" class="form-control" value="<?php echo $tenant['address']; ?>" readonly>
        </div>
        <div class="form-group">
            <label>Phone Number:</label>
            <input type="text" class="form-control" value="<?php echo $tenant['phoneNumber']; ?>" readonly>
        </div>
        <a href="tenant_info.php" class="btn btn-primary">Back to List</a>
    </div>
</body>
</html>
