<?php
include 'db.php';


$members = [];
$sql = "SELECT id, profile_picture, full_name, phone_number, occupation FROM members";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $members[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Members</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/login.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        td img {
            width: 50px;
            height: 50px;
           object-fit: contain;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .action-buttons a {
            text-decoration: none;
            color: #007bff;
        }
        .action-buttons a:hover {
            text-decoration: underline;
        }
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .profile-img {
            width: 150px;
            height: 150px;
         object-fit: contain;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

    <div class="all">
        <h2>Members List</h2>
        <table>
            <thead>
                <tr>
                    <th>Profile Image</th>
                    <th>Full Name</th>
                    <th>Phone Number</th>
                    <th>Occupation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($members)): ?>
                    <?php foreach ($members as $member): ?>
                        <tr>
                            <td><img src="uploads/<?php echo htmlspecialchars($member['profile_picture']); ?>" alt="Profile Image"></td>
                            <td><?php echo htmlspecialchars($member['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($member['phone_number']); ?></td>
                            <td><?php echo htmlspecialchars($member['occupation']); ?></td>
                            <td >
                                <span class="view-btn" data-id="<?php echo $member['id']; ?>">Click to View</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No members found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="memberModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Member Details</h2>
            <div id="modal-body">
                <!-- Member details will be loaded here -->
            </div>
        </div>
    </div>

    <script src="./js/sidebar.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewButtons = document.querySelectorAll('.view-btn');
            const modal = document.getElementById('memberModal');
            const closeButton = document.querySelector('.close');
            const modalBody = document.getElementById('modal-body');

            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const memberId = this.getAttribute('data-id');
                    fetchMemberDetails(memberId);
                });
            });

            closeButton.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });

            function fetchMemberDetails(id) {
                fetch('get_member_details.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        modalBody.innerHTML = `
                            <img src="uploads/${data.profile_picture}" alt="Profile Image" class="profile-img">
                            <p><strong>Full Name:</strong> ${data.full_name}</p>
                            <p><strong>Email:</strong> ${data.email}</p>
                            <p><strong>Phone Number:</strong> ${data.phone_number}</p>
                            <p><strong>Date of Birth:</strong> ${data.date_of_birth}</p>
                            <p><strong>Gender:</strong> ${data.gender}</p>
                            <p><strong>Address:</strong> ${data.address}</p>
                            <p><strong>Marital Status:</strong> ${data.marital_status}</p>
                            <p><strong>Ministry:</strong> ${data.ministry_name}</p>
                            <p><strong>Occupation:</strong> ${data.occupation}</p>
                        `;
                        modal.style.display = 'block';
                    })
                    .catch(error => console.error('Error fetching member details:', error));
            }
        });
    </script>
</body>
</html>
