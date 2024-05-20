<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    $roomId = $_POST['roomId'];
    $paymentDueDate = $_POST['paymentDate'];
    $paymentAmount = $_POST['paymentAmount'];
    $paymentStatus = $_POST['paymentStatus'];

    // Debugging output
    echo "UserId: $userId, RoomId: $roomId, PaymentDueDate: $paymentDueDate, PaymentAmount: $paymentAmount, PaymentStatus: $paymentStatus";

    try {
        $sql = "INSERT INTO payments (userId, roomId, paymentDueDate, paymentAmount, paymentStatus) VALUES (:userId, :roomId, :paymentDate, :paymentAmount, :paymentStatus)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':roomId', $roomId);
        $stmt->bindParam(':paymentDate', $paymentDueDate);
        $stmt->bindParam(':paymentAmount', $paymentAmount);
        $stmt->bindParam(':paymentStatus', $paymentStatus);
        $stmt->execute();

        header('Location: A_payments.php');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch user names from the users table
$sql = "SELECT id, name FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch room names from the rooms table
$sql = "SELECT id, roomName FROM rooms";
$stmt = $conn->prepare($sql);
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Payment</title>
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.6);
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
        <h3>Payment Process</h3>
        <form method="post">
            <div class="form-group">
                <label for="userId">User Name</label>
                <select class="form-control" id="userId" name="userId" required>
                    <option value="">Select User</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user['id']; ?>"><?php echo $user['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="roomId">Room Name</label>
                <select class="form-control" id="roomId" name="roomId" required>
                    <option value="">Select Room</option>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo $room['id']; ?>"><?php echo $room['roomName']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="paymentDate">Payment Date</label>
                <input type="date" class="form-control" id="paymentDate" name="paymentDate" required>
            </div>
            <div class="form-group">
                <label for="paymentAmount">Payment Amount</label>
                <input type="number" step="0.01" class="form-control" id="paymentAmount" name="paymentAmount" required>
            </div>
            <div class="form-group">
                <label for="paymentStatus">Payment Status</label>
                <select class="form-control" id="paymentStatus" name="paymentStatus" required>
                    <option value="">Select Payment Status</option>
                    <option value="Paid">Paid</option>
                    <option value="Unpaid">Unpaid</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create Payment</button>
            <a href="A_payments.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
