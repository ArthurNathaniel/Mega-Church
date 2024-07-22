<?php
include 'db.php';
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
   <div class="login_all">
    <div class="login_image"></div>
    <div class="login_forms">
    <h2>Admin Login</h2>
    <form method="post" action="">
    <div class="forms">
    <?php
        if (!empty($error)) {
            echo "<div class='error-message'>
                    <span class='close-icon' onclick='closeError()'>&times;</span>
                    $error
                  </div>";
        }
        ?>
    </div>
       <div class="forms">
       <label>Username:</label>
       <input type="text" name="username" required>
       </div>
      <div class="forms">
      <label>Password:</label>
      <input type="password" name="password" required>
      </div>
    <div class="forms">
        <button type="submit">Login</button>
    </div>
       
    </form>
    </div>
   </div>

    <script>
        function closeError() {
            var errorDiv = document.querySelector('.error-message');
            errorDiv.style.display = 'none';
        }
    </script>
</body>
</html>
