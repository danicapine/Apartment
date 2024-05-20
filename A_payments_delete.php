<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the payment data to be archived
    $payment = $conn->prepare("SELECT * FROM payments WHERE id = :id");
    $payment->bindValue(':id', $id, PDO::PARAM_INT);
    $payment->execute();
    $payment_data = $payment->fetch(PDO::FETCH_ASSOC);

    if ($payment_data) {
        $archived_data = json_encode($payment_data);
        $archived_table = 'payments';
        $archived_date = date('Y-m-d H:i:s');

        // Insert the archived data into the recycle_bin table
        $insert_stmt = $conn->prepare("INSERT INTO recycle_bin (archived_date, archived_table, archived_data) VALUES (:archived_date, :archived_table, :archived_data)");
        $insert_stmt->bindValue(':archived_date', $archived_date, PDO::PARAM_STR);
        $insert_stmt->bindValue(':archived_table', $archived_table, PDO::PARAM_STR);
        $insert_stmt->bindValue(':archived_data', $archived_data, PDO::PARAM_STR);
        $insert_stmt->execute();

        // Delete the payment from the original table
        $delete_stmt = $conn->prepare("DELETE FROM payments WHERE id = :id");
        $delete_stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $delete_stmt->execute();
    }

    header("Location: payments.php");
    exit();
}
?>
