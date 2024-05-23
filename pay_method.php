<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $roomId = trim($_POST['roomId']);
    $paymentAmount = trim($_POST['paymentAmount']);

    // Validate input
    if (empty($name) || empty($roomId) || empty($paymentAmount) || !is_numeric($paymentAmount)) {
        $error = "Please provide a valid name, room, and payment amount.";
    } else {
        // Sanitize input
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $roomId = htmlspecialchars($roomId, ENT_QUOTES, 'UTF-8');
        $paymentAmount = htmlspecialchars($paymentAmount, ENT_QUOTES, 'UTF-8');

        // Calculate due date (assuming 30 days from the current date)
        $paymentDueDate = date('Y-m-d', strtotime('+30 days'));

        // Set the initial payment status
        $paymentStatus = 'Paid';

        try {
            // Start a transaction
            $conn->beginTransaction();

            // Insert the payment
            $sql = "INSERT INTO payments (name, roomId, paymentAmount, paymentDueDate, paymentStatus) VALUES (:name, :roomId, :paymentAmount, :paymentDueDate, :paymentStatus)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':roomId', $roomId);
            $stmt->bindParam(':paymentAmount', $paymentAmount);
            $stmt->bindParam(':paymentDueDate', $paymentDueDate);
            $stmt->bindParam(':paymentStatus', $paymentStatus);
            $stmt->execute();

            // Update the room status to "Occupied"
            $sql = "UPDATE rooms SET Status = 'Occupied' WHERE id = :roomId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':roomId', $roomId);
            $stmt->execute();

            // Commit the transaction
            $conn->commit();

            $successMessage = "Payment created successfully!";
        } catch (PDOException $e) {
            // Roll back the transaction if an error occurred
            $conn->rollBack();
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Fetch room names from the rooms table excluding those with status 'Occupied'
$sql = "SELECT id, roomName FROM rooms WHERE status != 'Occupied'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Update payment status based on due date
$sql = "UPDATE payments SET paymentStatus = CASE
            WHEN paymentDueDate < CURDATE() THEN 'Not Paid'
            ELSE 'Paid'
        END";
$stmt = $conn->prepare($sql);
$stmt->execute();
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
</head>
<body>
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
    <div class="container">
        <h3>Payment Process</h3>

        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form  id="paymentForm" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="roomId">Room Name</label>
                <select class="form-control" id="roomId" name="roomId" required>
                    <option value="">Select Room</option>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo $room['id']; ?>" <?php echo isset($roomId) && $roomId == $room['id'] ? 'selected' : ''; ?>><?php echo $room['roomName']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="paymentAmount">Payment Amount ($)</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                    </div>
                    <input type="number" step="0.01" class="form-control" id="paymentAmount" name="paymentAmount" value="<?php echo isset($paymentAmount) ? $paymentAmount : ''; ?>" required>
                </div>
            </div>

            <button type="button" class="btn btn-primary" onclick="showConfirmation()">Create Payment</button>
            <a href="rooms2.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
