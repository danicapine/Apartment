<?php
include 'connection.php';

$id = $_GET['id'];
$query = "SELECT p.id, u.name, r.roomName, p.paymentDueDate, p.paymentAmount, p.paymentStatus
          FROM payments p
          INNER JOIN users u ON p.userId = u.id
          INNER JOIN rooms r ON p.roomId = r.id
          WHERE p.id = :id";

$stmt = $conn->prepare($query); // Corrected variable name
$stmt->bindParam(':id', $id);
$stmt->execute();
$payment = $stmt->fetch(PDO::FETCH_ASSOC); // Changed variable name from $payments to $payment
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 500px;
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
        <h3>Payment Details</h3>
        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" value="<?php echo $payment['name']; ?>" readonly>
        </div>
        <div class="form-group">
            <label>Room Name</label>
            <input type="text" class="form-control" value="<?php echo $payment['roomName']; ?>" readonly>
        </div>
        <div class="form-group">
            <label>Due Date</label>
            <input type="text" class="form-control" value="<?php echo $payment['paymentDueDate']; ?>" readonly>
        </div>
        <div class="form-group">
            <label>Amount</label>
            <input type="text" class="form-control" value="<?php echo $payment['paymentAmount']; ?>" readonly>
        </div>
        <div class="form-group">
            <label>Status</label>
            <input type="text" class="form-control" value="<?php echo $payment['paymentStatus']; ?>" readonly>
        </div>
        <a href="A_payments.php" class="btn btn-primary">Back to List</a>
    </div>
</body>
</html>
