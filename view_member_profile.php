<?php
session_start();
include 'db.php';

if (!isset($_SESSION['member_id'])) {
    header("Location: member_login.php");
    exit();
}

$member_id = $_SESSION['member_id'];


$stmt = $conn->prepare("SELECT full_name, email, phone_number, date_of_birth, gender, address, marital_status, ministry_id, occupation, profile_picture FROM members WHERE id = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$stmt->bind_result($full_name, $email, $phone_number, $date_of_birth, $gender, $address, $marital_status, $ministry_id, $occupation, $profile_picture);
$stmt->fetch();
$stmt->close();


$ministry_name = "";
$ministry_stmt = $conn->prepare("SELECT ministry_name FROM ministries WHERE id = ?");
$ministry_stmt->bind_param("i", $ministry_id);
$ministry_stmt->execute();
$ministry_stmt->bind_result($ministry_name);
$ministry_stmt->fetch();
$ministry_stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/login.css">
    <style>
        .profile-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
        }
        .profile-container img {
            border-radius: 50%;
        }
        .profile-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-container .form-group {
            margin-bottom: 15px;
        }
        .profile-container label {
            font-weight: bold;
        }
        .profile-container .form-group input,
        .profile-container .form-group select {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 8px;
            width: 100%;
        }
        .profile-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }
        .profile-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>View Profile</h2>
        <div class="form-group">
            <label>Full Name:</label>
            <input type="text" value="<?php echo htmlspecialchars($full_name); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Email Address:</label>
            <input type="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Phone Number:</label>
            <input type="text" value="<?php echo htmlspecialchars($phone_number); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Date of Birth:</label>
            <input type="date" value="<?php echo htmlspecialchars($date_of_birth); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Gender:</label>
            <input type="text" value="<?php echo htmlspecialchars($gender); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Address:</label>
            <input type="text" value="<?php echo htmlspecialchars($address); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Marital Status:</label>
            <input type="text" value="<?php echo htmlspecialchars($marital_status); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Ministry:</label>
            <input type="text" value="<?php echo htmlspecialchars($ministry_name); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Occupation:</label>
            <input type="text" value="<?php echo htmlspecialchars($occupation); ?>" readonly>
        </div>
        <div class="form-group">
            <label>Profile Picture:</label>
            <?php if (!empty($profile_picture)): ?>
                <img src="uploads/<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" width="150">
            <?php else: ?>
                <p>No profile picture uploaded.</p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <a href="edit_member_profile.php" class="button">Edit Profile</a>
        </div>
    </div>
</body>
</html>
