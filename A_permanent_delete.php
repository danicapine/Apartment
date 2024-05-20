<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the record from recycle_bin
    $delete_stmt = $conn->prepare("DELETE FROM recycle_bin WHERE id = :id");
    $delete_stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $delete_stmt->execute();

    header("Location: recycle_bin.php");
    exit();
}
?>
