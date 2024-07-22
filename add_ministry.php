<?php
include 'db.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ministry_name = $_POST['ministry_name'];

 
    $sql = "SELECT COUNT(*) FROM ministries WHERE ministry_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ministry_name);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $error = "A ministry with this name already exists.";
    } else {
       
        $sql = "INSERT INTO ministries (ministry_name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $ministry_name);

        if ($stmt->execute()) {
            $success = "Ministry added successfully!";
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Ministry</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/login.css">
    <style>
        .message {
          
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid transparent;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            width: 100%;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        .message .close-btn {
          background-color: transparent;
        
            font-size: 20px;
            font-weight: 900;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
    <div class="all">
        <h2>Add Ministry</h2>
        <form method="post" action="">
            <div class="forms">
                <?php if (!empty($error)): ?>
                    <div class="message error">
                        <?php echo $error; ?>
                        <span class="close-btn">&times;</span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="message success">
                        <?php echo $success; ?>
                        <span class="close-btn">&times;</span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="forms">
                <label>Ministry Name:</label>
                <input type="text" name="ministry_name" required>
            </div>
            <div class="forms">
                <button type="submit" id="submit-btn">Add Ministry</button>
            </div>
        </form>
    </div>
    <script src="./js/sidebar.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const closeButtons = document.querySelectorAll('.close-btn');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.parentElement.style.display = 'none';
                });
            });

            const submitButton = document.getElementById('submit-btn');
            submitButton.addEventListener('click', function() {
                this.textContent = 'Please wait...';
                this.disabled = true;
               
            });
        });
    </script>
</body>
</html>
