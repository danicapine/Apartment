<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit;
}

// Include necessary files and functions
include 'connection.php';

// Fetch user's current profile information from the database
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = fetchData($sql);

if (!empty($result)) {
    $user = $result[0];
} else {
    // Redirect or handle error if user not found
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form data
    $newName = $_POST['name'];
    // You can similarly process other fields like picture and password
    
    // Update the database with the new profile information
    $updateSql = "UPDATE users SET name = :name WHERE username = :username";
    $stmt = $conn->prepare($updateSql);
    $stmt->bindParam(':name', $newName);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Redirect to profile page or handle success message
    header("Location: profile.php");
    exit;
}
?>

<!-- HTML form for editing profile -->
<form method="POST" enctype="multipart/form-data">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"><br>

    <!-- Add fields for other profile information like picture and password -->

    <button type="submit">Save Changes</button>
</form>
