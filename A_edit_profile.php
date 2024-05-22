<?php
@include 'connection.php';
session_start();

if(!isset($_SESSION['username']) || $_SESSION['username'] !== 'Admin') {
    header('Location: login.php');
    exit();
}

if(isset($_POST['submit'])){
    $new_username = $_POST['username'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Update the username and password in the database
    $stmt = $conn->prepare("UPDATE users SET username = :username, password = :password WHERE user_type = 'admin'");
    $stmt->bindParam(':username', $new_username);
    $stmt->bindParam(':password', $new_password);

    if($stmt->execute()){
        $_SESSION['username'] = $new_username; // Update session with new username
        header('Location: A_index.php?message=Profile updated successfully!');
        exit();
    } else {
        $error_message = "Failed to update profile. Please try again.";
    }
}

// Fetch the current admin details
$stmt = $conn->prepare("SELECT * FROM users WHERE user_type = 'admin'");
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/login_signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script>
        function togglePassword(fieldId, iconId) {
            var field = document.getElementById(fieldId);
            var icon = document.getElementById(iconId);
            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                field.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }
    </script>

    <style>
        .password-container {
            position: relative;
            margin-bottom: 10px;
        }

        .password-container input {
            width: calc(100% - 30px);
        }

        .password-container i {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="form-container">
    <form action="" method="post">
        <h3>Edit Profile</h3>
        <?php
        if(isset($error_message)){
            echo '<span class="error-msg">'.$error_message.'</span>';
        }
        ?>
        <input type="text" name="username" required placeholder="Enter new username" value="<?php echo htmlspecialchars($admin['username']); ?>">
        <div class="password-container">
            <input type="password" id="password" name="password" required placeholder="Enter new password">
            <i class="fas fa-eye-slash" id="togglePassword" onclick="togglePassword('password', 'togglePassword')"></i>
        </div>
        <input type="submit" name="submit" value="Update Profile" class="form-btn">
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
