<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the archived data
    $stmt = $conn->prepare("SELECT * FROM recycle_bin WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $archived_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($archived_data) {
        $data = json_decode($archived_data['archived_data'], true);
        $table = $archived_data['archived_table'];

        // Prepare insert statement based on the archived table
        if ($table === 'payments') {
            $insert_stmt = $conn->prepare("INSERT INTO payments (userId, roomId, paymentDueDate, paymentAmount, paymentStatus) VALUES (:userId, :roomId, :paymentDueDate, :paymentAmount, :paymentStatus)");
            $insert_stmt->bindValue(':userId', $data['userId'], PDO::PARAM_INT);
            $insert_stmt->bindValue(':roomId', $data['roomId'], PDO::PARAM_INT);
            $insert_stmt->bindValue(':paymentDueDate', $data['paymentDueDate'], PDO::PARAM_STR);
            $insert_stmt->bindValue(':paymentAmount', $data['paymentAmount'], PDO::PARAM_STR);
            $insert_stmt->bindValue(':paymentStatus', $data['paymentStatus'], PDO::PARAM_STR);
            $insert_stmt->execute();
        } elseif ($table === 'tenants') {
            $insert_stmt = $conn->prepare("INSERT INTO tenants (userId, roomId, rentStartDate, address, phoneNumber) VALUES (:userId, :roomId, :rentStartDate, :address, :phoneNumber)");
            $insert_stmt->bindValue(':userId', $data['userId'], PDO::PARAM_INT);
            $insert_stmt->bindValue(':roomId', $data['roomId'], PDO::PARAM_INT);
            $insert_stmt->bindValue(':rentStartDate', $data['rentStartDate'], PDO::PARAM_STR);
            $insert_stmt->bindValue(':address', $data['address'], PDO::PARAM_STR);
            $insert_stmt->bindValue(':phoneNumber', $data['phoneNumber'], PDO::PARAM_STR);
            $insert_stmt->execute();
        } elseif ($table === 'rooms') {
            $insert_stmt = $conn->prepare("INSERT INTO rooms (roomName, roomDescription, roomPrice, roomPicture, Status) VALUES (:roomName, :roomDescription, :roomPrice, :roomPicture, :Status)");
            $insert_stmt->bindValue(':roomName', $data['roomName'], PDO::PARAM_STR);
            $insert_stmt->bindValue(':roomDescription', $data['roomDescription'], PDO::PARAM_STR);
            $insert_stmt->bindValue(':roomPrice', $data['roomPrice'], PDO::PARAM_STR);
            $insert_stmt->bindValue(':roomPicture', $data['roomPicture'], PDO::PARAM_STR);
            $insert_stmt->bindValue(':Status', $data['Status'], PDO::PARAM_STR);
            $insert_stmt->execute();
        }

        // Delete the record from recycle_bin
        $delete_stmt = $conn->prepare("DELETE FROM recycle_bin WHERE id = :id");
        $delete_stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $delete_stmt->execute();
    }

    header("Location: recycle_bin.php");
    exit();
}
?>