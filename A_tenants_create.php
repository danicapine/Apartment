<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $user_type = 'tenant';
    $roomId = $_POST['roomId'];
    $rentStartDate = $_POST['rentStartDate'];
    $address = $_POST['address'];
    $phoneNumber = $_POST['phoneNumber'];

    // Generate a default password (e.g., a random string or a hash)
    $password = password_hash('defaultpassword', PASSWORD_BCRYPT);

    // Insert user
    $sql = "INSERT INTO users (name, password, user_type) VALUES (:name, :password, :user_type)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':password', $password);  // Bind the password
    $stmt->bindParam(':user_type', $user_type);
    $stmt->execute();
    $userId = $conn->lastInsertId();

    // Insert tenant
    $sql = "INSERT INTO tenants (userId, roomId, rentStartDate, address, phoneNumber) VALUES (:userId, :roomId, :rentStartDate, :address, :phoneNumber)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':roomId', $roomId);
    $stmt->bindParam(':rentStartDate', $rentStartDate);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phoneNumber', $phoneNumber);
    $stmt->execute();

    if ($stmt) {
        header("Location: A_tenant_info.php?msg=New record created successfully");
        exit; // Mahalaga ang paglagay ng exit o die dito para itigil ang pagproseso ng script pagkatapos ng redirect
    } else {
        echo "Failed: " . $stmt->errorInfo()[2]; // Kung may error sa query, ito ang ipapakita
    }    
}

// Fetch available rooms
$sql = "SELECT * FROM rooms WHERE id = id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Rental</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 600px;
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
        <h3>Create Tenant</h3>
        <form method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required style="border-radius: 18px">
            </div>
            <div class="form-group">
                <label for="roomId">Room</label>
                <select class="form-control" id="roomId" name="roomId" required>
                    <option value="">Select Room</option>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo $room['id']; ?>"><?php echo $room['roomName']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="rentStartDate">Rent Start Date</label>
                <input type="date" class="form-control" id="rentStartDate" name="rentStartDate" required style="border-radius: 18px">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" required style="border-radius: 18px">
            </div>
            <div class="form-group">
                <label for="phoneNumber">Phone Number</label>
                <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" required style="border-radius: 18px">
            </div>
            <button type="submit" class="btn btn-primary" style="border-radius: 18px; padding-left:40px; padding-right:40px">Create</button>
            <a href="A_tenant_info.php" class="btn btn-secondary" style="border-radius: 18px; padding-left:30px; padding-right:30px">Cancel</a>
        </form>
    </div>
</body>
</html>
