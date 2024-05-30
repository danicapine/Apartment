<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    $roomId = $_POST['roomId'];
    $paymentAmount = $_POST['paymentAmount'];
    $paymentDueDate = date('Y-m-d');

    // Check if the selected room is available
    $sql = "SELECT Status FROM rooms WHERE id = :roomId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':roomId', $roomId);
    $stmt->execute();
    $roomStatus = $stmt->fetchColumn();

    if ($roomStatus === 'Available') {
        try {
            // Insert payment record
            $sql = "INSERT INTO payments (userId, roomId, paymentDueDate, paymentAmount) VALUES (:userId, :roomId, :paymentDueDate, :paymentAmount)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':roomId', $roomId);
            $stmt->bindParam(':paymentDueDate', $paymentDueDate);
            $stmt->bindParam(':paymentAmount', $paymentAmount);
            $stmt->execute();

            // Update room status to "Occupied"
            $sql = "UPDATE rooms SET Status = 'Occupied' WHERE id = :roomId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':roomId', $roomId);
            $stmt->execute();

            // Update payment status based on due date
            $sql = "UPDATE payments SET paymentStatus = CASE
                    WHEN DATE_ADD(paymentDueDate, INTERVAL 1 MONTH) < CURDATE() THEN 'Not Paid'
                    ELSE 'Paid'
                    END WHERE userId = :userId AND roomId = :roomId AND paymentDueDate = :paymentDueDate";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':roomId', $roomId);
            $stmt->bindParam(':paymentDueDate', $paymentDueDate);
            $stmt->execute();

            header('Location: rooms2.php');
            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error: The selected room is currently occupied and cannot be rented.";
    }
}

// Fetch Juan Dela Cruz from the users table
$sql = "SELECT id, name FROM users WHERE name = 'Juan Dela Cruz'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch only available rooms
$sql = "SELECT id, roomName FROM rooms WHERE Status = 'Available'";
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
            color: #1a5311; /* Dark green for a money theme */
            text-align: center;
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
            color: #1a5311; /* Dark green for a money theme */
        }
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 4px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .form-control:focus {
            border-color: #28a745; /* Green when focused */
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25); /* Green shadow when focused */
        }
        .btn-primary {
            background-color: #1a5311; /* Dark green for a money theme */
            border-color: #1a5311; /* Dark green for a money theme */
        }
        .btn-primary:hover {
            background-color: #0d2f07; /* Darker green on hover */
            border-color: #0d2f07; /* Darker green on hover */
        }
        .btn-secondary {
            color: #1a5311; /* Dark green for a money theme */
            background-color: #f8f9fa;
            border-color: #ccc;
        }
        .btn-secondary:hover {
            color: #1a5311; /* Dark green for a money theme */
            background-color: #e2e6ea;
            border-color: #ccc;
        }
    </style>
    <script>
        // Function to show confirmation message
        function showConfirmation() {
            if (confirm("Are you sure you want to proceed with the payment?")) {
                // If user clicks 'OK', submit the form
                document.getElementById("paymentForm").submit();
            } else {
                // If user clicks 'Cancel', do nothing
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h3>Payment Process</h3>
        <form method="post" id="paymentForm">
            <div class="form-group">
                <label for="userId">Name</label>
                <input type="hidden" name="userId" value="1">
                <input type="text" class="form-control" id="userId" value="Juan Dela Cruz" readonly>
            </div>
            <div class="form-group">
                <label for="roomId">Room Name</label>
                <select class="form-control" id="roomId" name="roomId" required>
                    <option value="">Select Room</option>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo $room['id']; ?>"><?php echo $room['roomName']; ?> (Available)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="paymentAmount">Payment Amount</label>
                <input type="number" step="0.01" class="form-control" id="paymentAmount" name="paymentAmount" required>
            </div>
            <button type="button" class="btn btn-primary" onclick="showConfirmation()">Create Payment</button>
            <a href="rooms2.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>