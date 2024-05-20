<?php
include 'connection.php'; // Make sure this file is included correctly and defines $conn

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    $error = []; // Initialize the error array

    // Check password format
    if (strlen($password) < 8 || 
        !preg_match("/[A-Z]/", $password) || 
        !preg_match("/[a-z]/", $password) || 
        !preg_match("/[0-9]/", $password) || 
        !preg_match("/[!@#$%^&*()\-_=+{};:,<.>]/", $password)) {
        $error[] = 'Password must be at least 8 characters long and contain uppercase letter, lowercase letter, number, and symbol!';
    }

    // Check if username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $row_count = $stmt->rowCount();
    if ($row_count > 0) {
        $error[] = 'User already exists!';
    } else {
        if ($password != $cpassword) {
            $error[] = 'Passwords do not match!';
        } else {
            // Set user type to 'user' by default
            $user_type = 'user';

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user data into the database
            $stmt = $conn->prepare("INSERT INTO users (name, username, password, user_type) VALUES(:name, :username, :password, :user_type)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password); // Use the hashed password
            $stmt->bindParam(':user_type', $user_type);
            $stmt->execute();

            header('Location: login.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register Form</title>
   <link rel="stylesheet" href="css/login_signup.css"> <!-- Check if the path to your CSS file is correct -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Include Font Awesome CSS -->
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
      <h3>Register Now</h3>
      <?php
      if (isset($error) && count($error) > 0) {
         foreach ($error as $err) {
            echo '<span class="error-msg">'.$err.'</span>';
         }
      }
      ?>
      <input type="text" name="name" required placeholder="Enter your name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES) : ''; ?>">
      <input type="text" name="username" required placeholder="Enter your email" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES) : ''; ?>">
      <div class="password-container">
         <input type="password" id="password" name="password" required placeholder="Enter your password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES) : ''; ?>">
         <i class="fas fa-eye-slash" id="togglePassword" onclick="togglePassword('password', 'togglePassword')"></i>
      </div>
      <div class="password-container">
         <input type="password" id="cpassword" name="cpassword" required placeholder="Confirm your password" value="<?php echo isset($_POST['cpassword']) ? htmlspecialchars($_POST['cpassword'], ENT_QUOTES) : ''; ?>">
         <i class="fas fa-eye-slash" id="toggleCPassword" onclick="togglePassword('cpassword', 'toggleCPassword')"></i>
      </div>
      <input type="submit" name="submit" value="Register Now" class="form-btn">
      <p>Already have an account? <a href="login.php">Login Now</a></p>
   </form>    
</div>
</body>
</html>

