<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $roomId = $_POST['roomId'];
    $rentStartDate = $_POST['rentStartDate'];
    $address = $_POST['address'];
    $phoneNumber = $_POST['phoneNumber'];

    // Update user
    $sql = "UPDATE users SET name = :name WHERE id = (SELECT userId FROM tenants WHERE id = :id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Update tenant
    $sql = "UPDATE tenants SET roomId = :roomId, rentStartDate = :rentStartDate, address = :address, phoneNumber = :phoneNumber WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':roomId', $roomId);
    $stmt->bindParam(':rentStartDate', $rentStartDate);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phoneNumber', $phoneNumber);
    $stmt->bindParam(':id', $id);
    $result = $stmt->execute();

    if ($result) {
        header("Location: A_tenant_info.php?msg=Updated successfully");
        exit; // Important to stop further execution of the script after redirection
    } else {
        echo "Failed: " . $stmt->errorInfo()[2]; // Show error message if there's a query error
    }
}

$id = $_GET['id'];
// Fetch tenant data
$sql = "SELECT t.id, u.name, t.roomId, t.rentStartDate, t.address, t.phoneNumber
FROM tenants t
INNER JOIN users u ON t.userId = u.id
WHERE t.id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$tenant = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch available rooms
$sql = "SELECT * FROM rooms WHERE id=id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if tenant data is fetched properly
if (!$tenant) {
    echo "Tenant not found.";
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Tenant</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h3 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
            color: #333;
        }
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 4px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .form-control:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-secondary {
            color: #333;
            background-color: #f8f9fa;
            border-color: #ccc;
        }
        .btn-secondary:hover {
            color: #333;
            background-color: #e2e6ea;
            border-color: #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Update Tenant</h3>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $tenant['id']; ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($tenant['name']); ?>" required style="border-radius: 18px">
            </div>
            <div class="form-group">
                <label for="roomId">Room</label>
                <select class="form-control" id="roomId" name="roomId" required>
                    <option value="">Select Room</option>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo $room['id']; ?>" <?php if ($room['id'] == $tenant['roomId']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($room['roomName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="rentStartDate">Rent Start Date</label>
                <input type="date" class="form-control" id="rentStartDate" name="rentStartDate" value="<?php echo htmlspecialchars($tenant['rentStartDate']); ?>" required style="border-radius: 18px">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($tenant['address']); ?>" required style="border-radius: 18px">
            </div>
            <div class="form-group">
                <label for="phoneNumber">Phone Number</label>
                <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo htmlspecialchars($tenant['phoneNumber']); ?>" required style="border-radius: 18px">
            </div>
            <button type="submit" class="btn btn-primary" style="border-radius: 18px; padding-left:40px; padding-right:40px">Update</button>
            <a href="A_tenant_info.php" class="btn btn-secondary" style="border-radius: 18px; padding-left:30px; padding-right:30px">Cancel</a>
        </form>
    </div>
</body>
</html>
