<!DOCTYPE html>
<html>
<head>
    <title>Logout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            text-align: center;
        }

        button {
            padding: 10px 20px;
            margin: 0 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        button[value="no"] {
            background-color: #ff6347; /* Red */
        }

        .message {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
            color: #ff6347; /* Red */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Logout Confirmation</h2>
        <?php
        if(isset($_POST['logout']) && $_POST['logout'] == 'no') {
            // User chose not to logout, redirect or display a message
            echo '<div class="message">Logout canceled. Redirecting...</div>';
            header('refresh:1;url=index.php'); // Redirect to dashboard or any other page
            exit();
        }elseif(isset($_POST['logout']) && $_POST['logout'] == 'yes') {
            echo '<div class="message">Successful</div>';
            header('refresh:1;url=login.php'); // Redirect to dashboard or any other page
            exit();
        }
        ?>
        
        <form method="post" action="A_logout.php">
            <p>Are you sure you want to logout?</p>
            <button type="submit" name="logout" value="yes">Yes</button>
            <button type="submit" name="logout" value="no">No</button>
        </form>
    </div>
</body>
</html>

