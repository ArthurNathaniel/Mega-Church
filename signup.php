<?php
include 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check for duplicate username
    $check_sql = "SELECT id FROM admins WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $error = "Username already exists.";
    } else {
        $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            $stmt->close();
            $check_stmt->close();
            $conn->close();
            header("Location: login.php");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $check_stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <div class="login_all">
        <div class="login_image"></div>
        <div class="login_forms">
            <h2>Admin Signup</h2>
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
                    <button type="submit">Signup</button>
                </div>
                <div class="forms">
                    <p>Already have an account <a href="login.php">Login here</a></p>
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
