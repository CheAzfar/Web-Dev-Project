<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'SAF';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userDetails = [];
$semesters = [];
$positions = [];

// Fetch positions and semesters from the database
$positionQuery = $conn->prepare("SELECT DISTINCT POSITION FROM AJK");
$positionQuery->execute();
$positionResult = $positionQuery->get_result();
while ($position = $positionResult->fetch_assoc()) {
    $positions[] = $position;
}
$positionQuery->close();

$semesterQuery = $conn->prepare("SELECT DISTINCT SEMESTER FROM AJK");
$semesterQuery->execute();
$semesterResult = $semesterQuery->get_result();
while ($semester = $semesterResult->fetch_assoc()) {
    $semesters[] = $semester;
}
$semesterQuery->close();

if (isset($_GET['matric'])) {
    $matric = filter_var($_GET['matric'], FILTER_SANITIZE_STRING);
    $query = $conn->prepare("SELECT * FROM AJK WHERE MATRIX_NUMBER = ?");
    $query->bind_param("s", $matric);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
        $userDetails = $result->fetch_assoc();
    } else {
        echo "<p>No record found for the provided matric number.</p>";
    }
    $query->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'];
    $icNo = $_POST['icNo'];
    $gender = $_POST['gender'];
    $religion = $_POST['religion'];
    $email = $_POST['email'];
    $year = $_POST['year'];
    $course = $_POST['course'];
    $position = $_POST['position'];
    $semester = $_POST['semester'];
    $matric = $_POST['matrixNo']; // Add matric number to update query

    // Validate gender and semester
    if (empty($gender) || empty($semester)) {
        echo "Gender and Semester are required!";
        exit();
    }

    // Validate other fields if necessary

    // Update user details in the AJK table
    $updateQuery = $conn->prepare("UPDATE AJK SET FULL_NAME = ?, IC_NUMBER = ?, GENDER = ?, RELIGION = ?, EMAIL = ?, YEAR = ?, COURSE = ?, POSITION = ?, SEMESTER = ? WHERE MATRIX_NUMBER = ?");
    $updateQuery->bind_param("ssssssssss", $name, $icNo, $gender, $religion, $email, $year, $course, $position, $semester, $matric);

    if ($updateQuery->execute()) {
        // Now update the SAF database with the new semester and gender
        $safUpdateQuery = $conn->prepare("UPDATE AJK SET SEMESTER = ?, GENDER = ? WHERE MATRIX_NUMBER = ?");
        $safUpdateQuery->bind_param("sss", $semester, $gender, $matric);

        if ($safUpdateQuery->execute()) {
            // Redirect after successful update
            header('Location: display.php');
            exit();
        } else {
            echo "<p>Error updating SAF record: " . $conn->error . "</p>";
        }
        $safUpdateQuery->close();
    } else {
        echo "<p>Error updating AJK record: " . $conn->error . "</p>";
    }
    $updateQuery->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>W3.CSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles.css">
</head>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .form-container {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            box-shadow:  0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            margin: auto;
            margin-top: 30px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-section {
            margin-bottom: 30px;
        }

        .form-section h3 {
            margin-bottom: 15px;
            color: #555;
            border-bottom: 2px solid #ccc;
            padding-bottom: 5px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-actions {
            text-align: center;
            margin-top: 20px;
        }

        .form-actions button {
            background-color: #28a745; /* Dark green */
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-actions button:hover {
            background-color: #218838; /* Darker green on hover */
        }
    </style>
<body>
    <!-- Sidebar -->
    <div class="custom-sidebar" id="sidebar">
        <!-- Sidebar content -->
        <div class="logo">
            <img src="/Project/PNG/Logo/FSKTMLogo.png" alt="FSKTM Logo">
        </div>
        <div class="menu-title">Menus</div>
        <a href="#" class="menu-item active">
            <img src="/Project/PNG/Icon/dashboard.png" alt="Dashboard Icon" class="menu-icon"> Dashboard
        </a>
        <a href="#" class="menu-item">
            <img src="/Project/PNG/Icon/userInfo.png" alt="User  Info Icon" class="menu-icon"> Athlete Information
        </a>
        <a href="#" class="menu-item">
            <img src="/Project/PNG/Icon/group.png" alt="Group Icon" class="menu-icon"> AJK Information
        </a>
        <a href="#" class="menu-item">
            <img src="/Project/PNG/Icon/schedule.png" alt="Schedule Icon" class="menu-icon"> Training Schedule
        </a>
        <a href="#" class="menu-item">
            <img src="/Project/PNG/Icon/Athlete.png" alt="Athlete Icon" class="menu-icon"> Athlete Registration
        </a>
        <a href="#" class="menu-item">
            <img src="img/aboutUs.png" alt="About Us Icon" class="menu-icon"> About Us
        </a>
        <a href="#" class="menu-item">
            <img src="img/logout.png" alt="Logout Icon" class="menu-icon"> Logout
        </a>
    </div>

    <!-- Main Content -->
    <div class="content" id="content">
        <input class="icon" type="image" src="img/sidebarIcon.png" onclick="toggleSidebar()"/>
        <h1>Hi, <?php echo htmlspecialchars($userDetails['FULL_NAME'] ?? 'User  Name', ENT_QUOTES, 'UTF-8'); ?></h1>
        <p>Welcome to admin page</p>

        <!-- Main Content Box -->
        <div class="form-container">
        <h2>Update AJK Details</h2>
        <form action="" method="POST">
            <div class="form-section">
                <h3>Personal Details</h3>
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userDetails['FULL_NAME'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="matrixNo">Matrix Number:</label>
                    <input type="text" id="matrixNo" name="matrixNo" value="<?php echo htmlspecialchars($userDetails['MATRIX_NUMBER'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="icNo">IC Number:</label>
                    <input type="text" id=" icNo" name="icNo" value="<?php echo htmlspecialchars($userDetails['IC_NUMBER'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender">
                        <option value="">Select Gender</option>
                        <option value="male" <?php echo (isset($userDetails['GENDER']) && $userDetails['GENDER'] === 'male') ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo (isset($userDetails['GENDER']) && $userDetails['GENDER'] === 'female') ? 'selected' : ''; ?>>Female</option>
                        <option value="other" <?php echo (isset($userDetails['GENDER']) && $userDetails['GENDER'] === 'other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="religion">Religion:</label>
                    <select id="religion" name="religion">
                        <option value="">Select Religion</option>
                        <option value="Islam" <?php echo (isset($userDetails['RELIGION']) && $userDetails['RELIGION'] === 'Islam') ? 'selected' : ''; ?>>Islam</option>
                        <option value="Christianity" <?php echo (isset($userDetails['RELIGION']) && $userDetails['RELIGION'] === 'Christianity') ? 'selected' : ''; ?>>Christianity</option>
                        <option value="Hinduism" <?php echo (isset($userDetails['RELIGION']) && $userDetails['RELIGION'] === 'Hinduism') ? 'selected' : ''; ?>>Hinduism</option>
                        <option value="Buddhism" <?php echo (isset($userDetails['RELIGION']) && $userDetails['RELIGION'] === 'Buddhism') ? 'selected' : ''; ?>>Buddhism</option>
                        <option value="Other" <?php echo (isset($userDetails['RELIGION']) && $userDetails['RELIGION'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userDetails['EMAIL'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
            </div>

            <div class="form-section">
                <h3>SAF Details</h3>
                <div class="form-group">
                    <label for="year">Year:</label>
                    <select id="year" name="year">
                        <option value="">Select Year</option>
                        <option value="1" <?php echo (isset($userDetails['YEAR']) && $userDetails['YEAR'] == '1') ? 'selected' : ''; ?>>1</option>
                        <option value="2" <?php echo (isset($userDetails['YEAR']) && $userDetails['YEAR'] == '2') ? 'selected' : ''; ?>>2</option>
                        <option value="3" <?php echo (isset($userDetails['YEAR']) && $userDetails['YEAR'] == '3') ? 'selected' : ''; ?>>3</option>
                        <option value="4" <?php echo (isset($userDetails['YEAR']) && $userDetails['YEAR'] == '4') ? 'selected' : ''; ?>>4</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="course">Course:</label>
                    <select id="course" name="course">
                        <option value="">Select Course</option>
                        <option value="BIW" <?php echo (isset($userDetails['COURSE']) && $userDetails['COURSE'] === 'BIW') ? 'selected' : ''; ?>>BIW</option>
                        <option value="BIS" <?php echo (isset($userDetails['COURSE']) && $userDetails['COURSE'] === 'BIS') ? 'selected' : ''; ?>>BIS</option>
                        <option value="BIT" <?php echo (isset($userDetails['COURSE']) && $userDetails['COURSE'] === 'BIT') ? 'selected' : ''; ?>>BIT</option>
                        <option value="BIP" <?php echo (isset($userDetails['COURSE']) && $userDetails['COURSE'] === 'BIP') ? 'selected' : ''; ?>>BIP</option>
                        <option value="BIM" <?php echo (isset($userDetails['COURSE']) && $userDetails['COURSE'] === 'BIM') ? 'selected' : ''; ?>>BIM</option>
                    </select>
                </div>

                <!-- Position Dropdown -->
                <div class="form-group">
                    <label for="position">Position:</label>
                    <select id="position" name="position">
                        <option value="">Select Position</option>
                        <option value="Food" <?php echo (isset($userDetails['POSITION']) && $userDetails['POSITION'] == 'Food') ? 'selected' : ''; ?>>Food AJK</option>
                        <option value="Media" <?php echo (isset($userDetails['POSITION']) && $userDetails['POSITION'] == 'Media') ? 'selected' : ''; ?>>Media AJK</option>
                        <option value="Manager" <?php echo (isset($userDetails['POSITION']) && $userDetails['POSITION'] == 'Manager') ? 'selected' : ''; ?>>Manager</option>
                        <option value="Assistant Manager" <?php echo (isset($userDetails['POSITION']) && $userDetails['POSITION'] == 'Assistant Manager') ? 'selected' : ''; ?>>Assistant Manager</option>
                        <option value="Gift" <?php echo (isset($userDetails['POSITION']) && $userDetails['POSITION'] == 'Gift') ? 'selected' : ''; ?>>Gift AJK</option>
                        <option value="Safety" <?php echo (isset($userDetails['POSITION']) && $userDetails['POSITION'] == 'Safety') ? 'selected' : ''; ?>>Safety AJK</option>
                        <option value="Protocol" <?php echo (isset($userDetails['POSITION']) && $userDetails['POSITION'] == 'Protocol') ? 'selected' : ''; ?>>Protocol AJK</option>
                    </select>
                </div>

                <!-- Semester Dropdown -->
                <div class="form-group">
                    <label for="semester">Semester:</label>
                    <select id="semester" name="semester">
                        <option value="">Select Semester</option>
                        <option value="1" <?php echo (isset($userDetails['SEMESTER']) && $userDetails['SEMESTER'] == '1') ? 'selected' : ''; ?>>SEMESTER 1</option>
                        <option value="2" <?php echo (isset($userDetails['SEMESTER']) && $userDetails['SEMESTER'] == '2') ? 'selected' : ''; ?>>SEMESTER 2</option>
                        <option value="3" <?php echo (isset($userDetails['SEMESTER']) && $userDetails['SEMESTER'] == '3') ? 'selected' : ''; ?>>SEMESTER 3</option>
                        <option value="4" <?php echo (isset($userDetails['SEMESTER']) && $userDetails['SEMESTER'] == '4') ? 'selected' : ''; ?>>SEMESTER 4</option>
                        <option value="5" <?php echo (isset($userDetails['SEMESTER']) && $userDetails['SEMESTER'] == '5') ? 'selected' : ''; ?>>SEMESTER 5</option>
                        <option value="6" <?php echo (isset($userDetails['SEMESTER']) && $userDetails['SEMESTER'] == '6') ? 'selected' : ''; ?>>SEMESTER 6</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit">Update</button>
            </div>
        </form>
    </div>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
        }
        
        // Listen for the form submission
        var gender = document.getElementById('gender').value;
        var semester = document.getElementById('semester').value;

    if (gender === "" || semester === "") {
        event.preventDefault(); 
        alert("Please select both gender and semester before submitting.");
    } else {
        alert("Update successful!");
        setTimeout(function() {
            window.location.href = 'display.php';
        }, 500); 
    }
});
    </script>
</body>
</html>