<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomName = $_POST['roomName'];
    $roomDescription = $_POST['roomDescription'];
    $roomPrice = $_POST['roomPrice'];
    $roomPicture = $_POST['roomPicture'];
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    $sql = "INSERT INTO rooms (roomName, roomDescription, roomPrice, roomPicture, status) VALUES (:roomName, :roomDescription, :roomPrice, :roomPicture, :status)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':roomName', $roomName);
    $stmt->bindParam(':roomDescription', $roomDescription);
    $stmt->bindParam(':roomPrice', $roomPrice);
    $stmt->bindParam(':roomPicture', $roomPicture);
    $stmt->bindParam(':status', $status);
    $stmt->execute();

    header('Location: A_rooms.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Room</title>
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
        <h3>Create Room</h3>
        <form method="post">
            <div class="form-group">
                <label for="roomName">Room Name</label>
                <input type="text" class="form-control" id="roomName" name="roomName" required style="border-radius: 18px">
            </div>
            <div class="form-group">
                <label for="roomDescription">Description</label>
                <textarea class="form-control" id="roomDescription" name="roomDescription" rows="3" required style="border-radius: 18px"></textarea>
            </div>
            <div class="form-group">
                <label for="roomPrice">Price</label>
                <input type="number" class="form-control" id="roomPrice" name="roomPrice" step="0.01" required style="border-radius: 18px">
            </div>
            <div class="form-group">
                <label for="roomPicture">Picture</label>
                <input type="file" class="form-control-file" id="roomPicture" name="roomPicture" required style="border-radius: 18px">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required style="border-radius: 18px">
                    <option value="Available">Available</option>
                    <option value="Occupied">Occupied</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="border-radius: 18px; padding-left:40px; padding-right:40px">Create</button>
            <a href="A_rooms.php" class="btn btn-secondary" style="border-radius: 18px; padding-left:30px; padding-right:30px">Cancel</a>
        </form>
    </div>
</body>
</html>