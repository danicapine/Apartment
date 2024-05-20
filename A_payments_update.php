<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $roomName = $_POST['roomName'];
    $paymentDueDate = $_POST['paymentDueDate'];
    $paymentAmount = $_POST['paymentAmount'];
    $paymentStatus = $_POST['paymentStatus'];

    // Update user
    $sql = "UPDATE users SET name = :name WHERE id = (SELECT userId FROM payments WHERE id = :id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Update rooms
    $sql = "UPDATE rooms SET roomName = :roomName WHERE id = (SELECT roomId FROM payments WHERE id = :id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':roomName', $roomName);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Update payments
    $sql = "UPDATE payments SET paymentDueDate = :paymentDueDate, paymentAmount = :paymentAmount, paymentStatus = :paymentStatus WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':paymentDueDate', $paymentDueDate);
    $stmt->bindParam(':paymentAmount', $paymentAmount);
    $stmt->bindParam(':paymentStatus', $paymentStatus);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    header('Location: payments.php');
    exit;
}

$id = $_GET['id'];
// Fetch payments data
$sql = "SELECT p.id, u.name AS name, r.roomName, p.paymentDueDate, p.paymentAmount, p.paymentStatus
FROM payments p
INNER JOIN users u ON p.userId = u.id
INNER JOIN rooms r ON p.roomId = r.id
WHERE p.id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$payment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$payment) {
    // Handle the case where the payment is not found
    echo "Payment not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Payment</title>
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
        <h3>Update Payment</h3>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($payment['id']); ?>">
            <div class="form-group">
                <label for="name">User Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($payment['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="roomName">Room Name</label>
                <input type="text" class="form-control" id="roomName" name="roomName" value="<?php echo htmlspecialchars($payment['roomName']); ?>" required>
            </div>
            <div class="form-group">
                <label for="paymentDueDate">Payment Date</label>
                <input type="date" class="form-control" id="paymentDueDate" name="paymentDueDate" value="<?php echo htmlspecialchars($payment['paymentDueDate']); ?>" required>
            </div>
            <div class="form-group">
                <label for="paymentAmount">Payment Amount</label>
                <input type="number" step="0.01" class="form-control" id="paymentAmount" name="paymentAmount" value="<?php echo htmlspecialchars($payment['paymentAmount']); ?>" required>
            </div>
            <div class="form-group">
                <label for="paymentStatus">Payment Status</label>
                <select class="form-control" id="paymentStatus" name="paymentStatus" required>
                    <option value="Paid" <?php if ($payment['paymentStatus'] === 'Paid') echo 'selected'; ?>>Paid</option>
                    <option value="Unpaid" <?php if ($payment['paymentStatus'] === 'Unpaid') echo 'selected'; ?>>Unpaid</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Payment</button>
            <a href="payments.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
