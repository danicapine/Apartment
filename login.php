<?php
@include 'connection.php';
session_start();

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Select user based on email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if($stmt->rowCount() > 0){
        $row = $stmt->fetch();
        $hashed_password = $row['password'];

        // Verify password
        if(password_verify($password, $hashed_password)){
            if($row['user_type'] == 'admin'){
                $_SESSION['admin_name'] = $row['name'];
                header('location:A_index.php');
            } elseif($row['user_type'] == 'user'){
                $_SESSION['user_name'] = $row['name'];
                header('location:user_interface.php');
            }
        } else {
            $error[] = 'Incorrect email or password!';
        }
    } else {
        $error[] = 'Incorrect email or password!';
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
   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/login_signup.css">
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
      <h3>Login Now</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         }
      }
      ?>
      <input type="email" name="email" required placeholder="Enter your email">
      <div class="password-container">
         <input type="password" id="password" name="password" required placeholder="Enter your password">
         <i class="fas fa-eye-slash" id="togglePassword" onclick="togglePassword('password', 'togglePassword')"></i>
      </div>
      <input type="submit" name="submit" value="Login Now" class="form-btn">
      <p>Don't have an account? <a href="signup.php">Register Now</a></p>
   </form>
</div>
</body>
</html>

