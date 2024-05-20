<?php
// Assuming 'connection.php' sets up the connection as a PDO instance in $conn
include 'connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Insert the deleted data into the recycle_bin table
    $room_stmt = $conn->prepare("SELECT * FROM rooms WHERE id = :id");
    $room_stmt->execute(['id' => $id]);
    $room_data = $room_stmt->fetch(PDO::FETCH_ASSOC);

    $archived_data = json_encode($room_data);
    $archived_table = 'rooms';
    $archived_date = date('Y-m-d H:i:s');

    $insert_stmt = $conn->prepare("INSERT INTO recycle_bin (archived_date, archived_table, archived_data) VALUES (:archived_date, :archived_table, :archived_data)");
    $insert_stmt->execute([
        'archived_date' => $archived_date,
        'archived_table' => $archived_table,
        'archived_data' => $archived_data
    ]);

    // Delete the room from the original table
    $delete_stmt = $conn->prepare("DELETE FROM rooms WHERE id = :id");
    $delete_stmt->execute(['id' => $id]);

    header("Location: A_rooms.php");
    exit();
}
?>
