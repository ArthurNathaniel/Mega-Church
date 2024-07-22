<?php
include 'db.php';

if (isset($_GET['id'])) {
    $member_id = intval($_GET['id']);

   
    $stmt = $conn->prepare("SELECT m.profile_picture, m.full_name, m.email, m.phone_number, m.date_of_birth, m.gender, m.address, m.marital_status, m.occupation, mn.ministry_name FROM members m JOIN ministries mn ON m.ministry_id = mn.id WHERE m.id = ?");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();


    echo json_encode($member);

    $stmt->close();
}

$conn->close();
?>
