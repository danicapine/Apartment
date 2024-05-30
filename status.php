<?php
include 'connection.php';

// Base query to select payment information with JOIN to users table
$query = "SELECT p.id, u.name, r.roomName, p.paymentAmount, p.paymentDueDate, p.paymentStatus
          FROM payments p
          INNER JOIN rooms r ON p.roomId = r.id
          INNER JOIN users u ON p.userId = u.id";

// Check if there is a search term and update the query accordingly
$search = isset($_GET['search']) ? $_GET['search'] : '';
if (!empty($search)) {
    $query .= " WHERE u.name LIKE :search OR r.roomName LIKE :search OR p.paymentDueDate LIKE :search OR p.paymentAmount LIKE :search OR p.paymentStatus LIKE :search";
}

// Sort the payments by payment due date in descending order
$query .= " ORDER BY p.paymentDueDate DESC";

try {
    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    if (!empty($search)) {
        $stmt->bindValue(':search', "%$search%");
    }
    $stmt->execute();
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate RoomStatus and Notification dynamically
    foreach ($payments as &$payment) {
        $dueDate = new DateTime($payment['paymentDueDate']);
        $currentDate = new DateTime();
        $interval = $currentDate->diff($dueDate);
        $daysRemaining = $interval->format('%a');

        // RoomStatus
        $payment['roomStatus'] = ($payment['paymentStatus'] == 'paid') ? 'Not Rented' : 'Rented';

        // Notification
        if ($daysRemaining > 0) {
            $payment['notification'] = "$daysRemaining days remaining";
        } elseif ($daysRemaining == 0) {
            $payment['notification'] = "Due date is today";
        } else {
            $payment['notification'] = "You've reached the due date";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'user';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Rent Details</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,700,900|Oswald:400,700"> 
    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/mediaelementplayer.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="css/fl-bigmug-line.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include('mainheader.php'); ?>

<div class="site-mobile-menu">
    <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
            <span class="icon-close2 js-menu-toggle"></span>
        </div>
    </div>
    <div class="site-mobile-menu-body"></div>
</div> <!-- .site-mobile-menu -->

<div class="site-blocks-cover overlay" style="background-image: url('images/image.png');" data-aos="fade" data-stellar-background-ratio="0.5">
    <div class="container-fluid px-4">
        <div class="row my-5">
            <h3 class="text-center mb-4 animated-heading">Rent Details</h3>
            <div class="table-responsive">
                <table class="table table-striped" style="border-collapse: collapse; width: 100%; background-color: white;">
                <thead style="background-color: #1E90FF; color: white;">
                        <tr>
                            <th style="padding: 12px; border: 1px solid #ddd;">Room Name</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Payment Amount</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Payment Due Date</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Payment Status</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Room Status</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Notification</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment): ?>
                            <tr style="transition: background-color 0.3s; background-color: white;">
                                <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($payment['roomName']); ?></td>
                                <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($payment['paymentAmount']); ?></td>
                                <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($payment['paymentDueDate']); ?></td>
                                <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($payment['paymentStatus']); ?></td>
                                <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($payment['roomStatus']); ?></td>
                                <td style="padding: 12px; border: 1px solid #ddd;"><?php echo htmlspecialchars($payment['notification']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

<?php include('footer2.php'); ?>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/jquery-migrate-3.0.1.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/mediaelement-and-player.min.js"></script>
<script src="js/jquery.stellar.min.js"></script>
<script src="js/jquery.countdown.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/bootstrap-datepicker.min.js"></script>
<script src="js/aos.js"></script>
<script src="js/circleaudioplayer.js"></script>
<script src="js/main.js"></script>

</body>
</html>