<?php
include 'db.php';

$error = "";
$success = "";

// Fetch ministries from the database
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
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check for duplicates
    $sql = "SELECT COUNT(*) FROM members WHERE full_name = ? AND email = ? AND phone_number = ? AND date_of_birth = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $full_name, $email, $phone_number, $date_of_birth);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $error = "A member with the same Full Name, Email, Phone Number, and Date of Birth already exists.";
    } else {
        // Upload profile picture
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_picture);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file);

        $sql = "INSERT INTO members (full_name, email, phone_number, date_of_birth, gender, address, marital_status, ministry_id, occupation, profile_picture, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssss", $full_name, $email, $phone_number, $date_of_birth, $gender, $address, $marital_status, $ministry_id, $occupation, $profile_picture, $username, $password);

        if ($stmt->execute()) {
            $success = "Member registered successfully!";
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
    <title>Register Member</title>
    <?php include 'cdn.php'?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
<?php include 'sidebar.php'?>
    <div class="all">
        <h2>Register Member</h2>
        <form method="post" action="" enctype="multipart/form-data">
        <div class="forms">
        <?php
            if (!empty($error)) {
                echo "<div class='error-message'>$error</div>";
            }
            if (!empty($success)) {
                echo "<div class='success-message'>$success</div>";
            }
            ?>
        </div>
          <div class="forms">
          <label>Full Name:</label>
          <input type="text" name="full_name" required>
          </div>
            
        <div class="forms">
        <label>Email Address:</label>
        <input type="email" name="email" required>
        </div>
            
          <div class="forms">
          <label>Phone Number:</label>
          <input type="text" name="phone_number" required>
          </div>
           <div class="forms">
             
           <label>Date of Birth:</label>
            <input type="date" name="date_of_birth" required>
           </div>
            
            <div class="forms">
            <label>Gender:</label>
            <select name="gender" required>
            <option value="" selected hidden>Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            </div>
            
           <div class="forms">
           <label>Address:</label>
           <input type="text" name="address" required>
           </div>
            
         <div class="forms">
         <label>Marital Status:</label>
            <select name="marital_status" required>
            <option value="" selected hidden>Select Marital Status</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Divorced">Divorced</option>
                <option value="Widowed">Widowed</option>
            </select>
         </div>
            
          <div class="forms">
          <label>Ministry:</label>
            <select name="ministry_id" required>
            <option value="" selected hidden>Select Ministry</option>

                <?php foreach ($ministries as $ministry): ?>
                    <option value="<?php echo $ministry['id']; ?>"><?php echo $ministry['ministry_name']; ?></option>
                <?php endforeach; ?>
            </select>
          </div>
            
          <div class="forms">
          <label>Occupation:</label>
          <input type="text" name="occupation" required>
          </div>
            
            <div class="forms">
            <label>Profile Picture:</label>
            <input type="file" name="profile_picture" required>
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
     <button type="submit">Register Member</button>
     </div>
        </form>
    </div>
    <script src="./js/sidebar.js"></script>
</body>
</html>
