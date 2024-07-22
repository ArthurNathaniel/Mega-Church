<?php
include 'db.php';

$ministry_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = "";
$success = "";

// Fetch the ministry details for editing
if ($ministry_id > 0) {
    $sql = "SELECT * FROM ministries WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ministry_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ministry = $result->fetch_assoc();
    $stmt->close();
} else {
    $error = "Invalid Ministry ID.";
}

// Handle the form submission for updating ministry details
if ($_SERVER["REQUEST_METHOD"] == "POST" && $ministry_id > 0) {
    $ministry_name = $_POST['ministry_name'];

    // Update the ministry details
    $sql = "UPDATE ministries SET ministry_name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $ministry_name, $ministry_id);

    if ($stmt->execute()) {
        $success = "Ministry updated successfully!";
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ministry</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/login.css">
    <style>
        .message {
            position: relative;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid transparent;
            border-radius: 5px;
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
            position: absolute;
            top: 5px;
            right: 10px;
            border: none;
            background: none;
            font-size: 1.2em;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

    <div class="all">
        <h2>Edit Ministry</h2>
        <?php if (!empty($error)): ?>
            <div class="message error">
                <?php echo $error; ?>
                <button class="close-btn">&times;</button>
            </div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="message success">
                <?php echo $success; ?>
                <button class="close-btn">&times;</button>
            </div>
        <?php endif; ?>

        <?php if ($ministry_id > 0 && $ministry): ?>
            <form method="post" action="">
                <div class="forms">
                    <label>Ministry Name:</label>
                    <input type="text" name="ministry_name" value="<?php echo htmlspecialchars($ministry['ministry_name']); ?>" required>
                </div>
                <div class="forms">
                    <button type="submit">Update Ministry</button>
                </div>
            </form>
        <?php else: ?>
            <p>Ministry not found.</p>
        <?php endif; ?>
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
        });
    </script>
</body>
</html>
