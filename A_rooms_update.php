<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $roomName = $_POST['roomName'];
    $roomDescription = $_POST['roomDescription'];
    $roomPrice = $_POST['roomPrice'];
    $Status = isset($_POST['status']) ? $_POST['status'] : '';

    // Check if a new file was uploaded
    if (isset($_FILES['roomPicture']) && $_FILES['roomPicture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['roomPicture']['tmp_name'];
        $fileName = $_FILES['roomPicture']['name'];
        $fileSize = $_FILES['roomPicture']['size'];
        $fileType = $_FILES['roomPicture']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $allowedfileExtensions = array('jpg', 'gif', 'png', 'webp');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = './uploads/';
            $dest_path = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $roomPicture = $fileName; // Save only the file name, not the full path
            } else {
                $message = 'There was an error moving the uploaded file.';
                // Handle the error (log it, show a message, etc.)
            }
        } else {
            $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
            // Handle the error (log it, show a message, etc.)
        }
    } else {
        // No new file uploaded, keep the existing picture
        $roomPicture = $_POST['existingPicture'];
    }

    $sql = "UPDATE rooms SET roomName = :roomName, roomDescription = :roomDescription, roomPrice = :roomPrice, roomPicture = :roomPicture, Status = :status WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':roomName', $roomName);
    $stmt->bindParam(':roomDescription', $roomDescription);
    $stmt->bindParam(':roomPrice', $roomPrice);
    $stmt->bindParam(':roomPicture', $roomPicture);
    $stmt->bindParam(':status', $Status);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    header('Location: rooms.php');
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM rooms WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$room = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Room</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
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
        .form-group {
            margin-bottom: 20px;
        }
        .form-check-input {
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Update Room</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($room['id']); ?>">
            <input type="hidden" name="existingPicture" value="<?php echo htmlspecialchars($room['roomPicture']); ?>">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="roomName">Room Name</label>
                    <input type="text" class="form-control" id="roomName" name="roomName" value="<?php echo htmlspecialchars($room['roomName']); ?>" required style="border-radius: 18px">
                </div>
                <div class="form-group col-md-6">
                    <label for="roomPrice">Price</label>
                    <input type="number" class="form-control" id="roomPrice" name="roomPrice" value="<?php echo htmlspecialchars($room['roomPrice']); ?>" step="0.01" required style="border-radius: 18px">
                </div>
            </div>
            <div class="form-group">
                <label for="roomDescription">Description</label>
                <textarea class="form-control" id="roomDescription" name="roomDescription" rows="3" required style="border-radius: 18px"><?php echo htmlspecialchars($room['roomDescription']); ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="roomPicture">Picture</label>
                    <input type="file" class="form-control-file" id="roomPicture" name="roomPicture" style="border-radius: 18px">
                    <?php if ($room['roomPicture']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($room['roomPicture']); ?>" alt="Room Picture" style="max-width: 100%; margin-top: 10px;">
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required style="border-radius: 18px">
                        <option value="Available">Available</option>
                        <option value="Occupied">Occupied</option>
                    </select>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary" style="border-radius: 18px; padding-left:30px; padding-right:30px">Update</button>
                <a href="rooms.php" class="btn btn-secondary" style="border-radius: 18px; padding-left:30px; padding-right:30px">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
