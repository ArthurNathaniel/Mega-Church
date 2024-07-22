<?php
session_start();
include 'db.php';

if (!isset($_SESSION['member_id'])) {
    header("Location: member_login.php");
    exit();
}

$member_id = $_SESSION['member_id'];
$error = "";
$success = "";

// Fetch member details
$stmt = $conn->prepare("SELECT full_name, email, phone_number, date_of_birth, gender, address, marital_status, ministry_id, occupation, profile_picture FROM members WHERE id = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$stmt->bind_result($full_name, $email, $phone_number, $date_of_birth, $gender, $address, $marital_status, $ministry_id, $occupation, $profile_picture);
$stmt->fetch();
$stmt->close();

// Fetch ministries for dropdown
$ministries = [];
$sql = "SELECT id, ministry_name FROM ministries";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $ministries[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $marital_status = $_POST['marital_status'];
    $ministry_id = $_POST['ministry_id'];
    $occupation = $_POST['occupation'];
    $profile_picture = $_FILES['profile_picture']['name'];

    // Upload profile picture
    if (!empty($profile_picture)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_picture);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file);
    } else {
        $profile_picture = $_POST['existing_profile_picture'];
    }

    // Update profile
    $sql = "UPDATE members SET full_name = ?, email = ?, phone_number = ?, date_of_birth = ?, gender = ?, address = ?, marital_status = ?, ministry_id = ?, occupation = ?, profile_picture = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssi", $full_name, $email, $phone_number, $date_of_birth, $gender, $address, $marital_status, $ministry_id, $occupation, $profile_picture, $member_id);

    if ($stmt->execute()) {
        $success = "Profile updated successfully!";
        header("Location: view_member_profile.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
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
    <title>Edit Profile</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <div class="profile-container">
        <h2>Edit Profile</h2>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>
            </div>
            <div class="form-group">
                <label>Email Address:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group">
                <label>Phone Number:</label>
                <input type="text" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" required>
            </div>
            <div class="form-group">
                <label>Date of Birth:</label>
                <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($date_of_birth); ?>" required>
            </div>
            <div class="form-group">
                <label>Gender:</label>
                <select name="gender" required>
                    <option value="Male" <?php echo ($gender == "Male") ? "selected" : ""; ?>>Male</option>
                    <option value="Female" <?php echo ($gender == "Female") ? "selected" : ""; ?>>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label>Address:</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
            </div>
            <div class="form-group">
                <label>Marital Status:</label>
                <select name="marital_status" required>
                    <option value="Single" <?php echo ($marital_status == "Single") ? "selected" : ""; ?>>Single</option>
                    <option value="Married" <?php echo ($marital_status == "Married") ? "selected" : ""; ?>>Married</option>
                    <option value="Divorced" <?php echo ($marital_status == "Divorced") ? "selected" : ""; ?>>Divorced</option>
                    <option value="Widowed" <?php echo ($marital_status == "Widowed") ? "selected" : ""; ?>>Widowed</option>
                </select>
            </div>
            <div class="form-group">
                <label>Ministry:</label>
                <select name="ministry_id" required>
                    <?php foreach ($ministries as $ministry): ?>
                        <option value="<?php echo $ministry['id']; ?>" <?php echo ($ministry['id'] == $ministry_id) ? "selected" : ""; ?>><?php echo $ministry['ministry_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Occupation:</label>
                <input type="text" name="occupation" value="<?php echo htmlspecialchars($occupation); ?>" required>
            </div>
            <div class="form-group">
                <label>Profile Picture:</label>
                <?php if (!empty($profile_picture)): ?>
                    <img src="uploads/<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" width="150">
                <?php else: ?>
                    <p>No profile picture uploaded.</p>
                <?php endif; ?>
                <input type="file" name="profile_picture">
                <input type="hidden" name="existing_profile_picture" value="<?php echo htmlspecialchars($profile_picture); ?>">
            </div>
            <div class="form-group">
                <button type="submit">Update Profile</button>
            </div>
        </form>
        <a href="view_member_profile.php" class="button">Cancel</a>
    </div>
</body>
</html>
