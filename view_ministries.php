<?php
include 'db.php';


if (isset($_GET['delete'])) {
    $ministry_id = $_GET['delete'];

    $sql = "DELETE FROM ministries WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ministry_id);

    if ($stmt->execute()) {
        $success = "Ministry deleted successfully!";
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}


$sql = "SELECT * FROM ministries";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Ministries</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/login.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .actions {
            text-align: center;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            color: white;
            cursor: pointer;
        }
        .edit-btn {
            background-color: #4CAF50;
        }
        .delete-btn {
            background-color: #f44336;
        }
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
        <h2>View Ministries</h2>
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

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ministry Name</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['ministry_name']); ?></td>
                            <td class="actions">
                                <a href="edit_ministry.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn edit-btn">Edit</a>
                                <a href="?delete=<?php echo htmlspecialchars($row['id']); ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this ministry?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No ministries found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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
