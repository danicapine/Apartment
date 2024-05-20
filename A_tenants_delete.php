<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Insert the deleted data into the recycle_bin table
    $tenant = $conn->prepare("SELECT * FROM tenants WHERE id = :id");
    $tenant->bindValue(':id', $id, PDO::PARAM_INT);
    $tenant->execute();
    $tenant_data = $tenant->fetch(PDO::FETCH_ASSOC);

    if ($tenant_data) {
        $archived_data = json_encode($tenant_data);
        $archived_table = 'tenants';
        $archived_date = date('Y-m-d H:i:s');

        $insert_stmt = $conn->prepare("INSERT INTO recycle_bin (archived_date, archived_table, archived_data) VALUES (:archived_date, :archived_table, :archived_data)");
        $insert_stmt->bindValue(':archived_date', $archived_date, PDO::PARAM_STR);
        $insert_stmt->bindValue(':archived_table', $archived_table, PDO::PARAM_STR);
        $insert_stmt->bindValue(':archived_data', $archived_data, PDO::PARAM_STR);
        $insert_stmt->execute();

        // Delete the tenant from the original table
        $delete_stmt = $conn->prepare("DELETE FROM tenants WHERE id = :id");
        $delete_stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $delete_stmt->execute();
    }

    header("Location: A_tenant_info.php");
    exit();
}    
?>
