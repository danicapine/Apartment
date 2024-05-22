<?php
@include 'connection.php';
session_start();

if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the entered username and password match the default admin credentials
    if($username === 'admin' && $password === 'admin'){
        $_SESSION['username'] = 'Admin';
        header('Location: A_index.php');
        exit();
    }

    // Prepare statement to select user based on username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        $row = $stmt->fetch();
        $hashed_password = $row['password'];

        // Verify password
        if(password_verify($password, $hashed_password)){
            $_SESSION['username'] = $row['username']; // Store the username in the session
            if($row['user_type'] == 'admin'){
                header('Location: A_index.php');
            } elseif($row['user_type'] == 'user'){
                header('Location: homepage2.php');
            }
            exit();
        } else {
            $error[] = 'Incorrect username or password!';
        }
    } else {
        $error[] = 'Incorrect username or password!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login Form</title>
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
      <h3>Login</h3>
      <?php
      if(isset($error)){
         foreach($error as $err){
            echo '<span class="error-msg">'.$err.'</span>';
         }
      }
      ?>
      <input type="text" name="username" required placeholder="Username">
      <div class="password-container">
         <input type="password" id="password" name="password" required placeholder="Password">
         <i class="fas fa-eye-slash" id="togglePassword" onclick="togglePassword('password', 'togglePassword')"></i>
      </div>
      <input type="submit" name="submit" value="Login" class="form-btn">
      <p>Don't have an account? <a href="signup.php">Register</a></p>
   </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
