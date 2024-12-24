<!DOCTYPE html>
<html>
    <head>
        <title>W3.CSS</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: center;
            }
            th {
                background-color: #f2f2f2;
            }
            .action-buttons {
                display: flex;
                justify-content: center;
                gap: 10px;
            }
            .delete-btn, .update-btn {
                color: white;
                padding: 5px 10px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 14px;
            }
            .delete-btn {
                background-color: #f44336;
            }
            .update-btn {
                background-color: #4CAF50;
            }
        </style>
    </head>
    <body>
        <!-- Sidebar -->
        <div class="custom-sidebar" id="sidebar">
            <!-- Sidebar content -->
            <div class="logo">
                <img src="img/FSKTMLogo.png" alt="FSKTM Logo">
            </div>
            <div class="menu-title">Menus</div>
            <a href="#" class="menu-item active">
                <img src="img/dashboard.png" alt="Logout Icon" class="menu-icon"> Dashboard
            </a>
            <a href="#" class="menu-item">
                <img src="img/userInfo.png" alt="Logout Icon" class="menu-icon"> Athlete Information
            </a>
            <a href="#" class="menu-item">
                <img src="img/group.png" alt="Logout Icon" class="menu-icon"> AJK Information
            </a>
            <a href="#" class="menu-item">
                <img src="img/schedule.png" alt="Logout Icon" class="menu-icon"> Training Schedule
            </a>
            <a href="#" class="menu-item">
                <img src="img/Athlete.png" alt="Logout Icon" class="menu-icon"> Athlete Registration
            </a>
            <a href="#" class="menu-item">
                <img src="img/aboutUs.png" alt="Logout Icon" class="menu-icon"> About Us
            </a>
            <a href="#" class="menu-item">
                <img src="img/logout.png" alt="Logout Icon" class="menu-icon"> Logout
            </a>
        </div>

        <!-- Main Content -->
        <div class="content" id="content">
            <input class="icon" type="image" src="img/sidebarIcon.png" onclick="toggleSidebar()"/>
            <h1>Hi, User Name</h1>
            <p>Welcome to admin page</p>

            <!-- Main Content Box -->
            <h1>AJK Details</h1>
            <?php
            // Database connection
            $servername = "localhost";
            $username = "root"; 
            $password = ""; 
            $dbname = "SAF"; 

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Handle DELETE operation
            if (isset($_POST['matric'])) {
                $matric = filter_var($_POST['matric'], FILTER_SANITIZE_STRING);
                if ($matric) {
                    $deleteQuery = "DELETE FROM AJK WHERE MATRIX_NUMBER = ?";
                    $stmt = $conn->prepare($deleteQuery);
                    $stmt->bind_param("s", $matric);
                    if ($stmt->execute()) {
                        echo json_encode(['success' => true, 'message' => 'Record deleted successfully!']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error deleting record.']);
                    }
                    $stmt->close();
                    exit();
                } else {
                    echo json_encode(['success' => false, 'message' => 'Invalid matric number provided.']);
                }
            }

            // Fetch all records
            $query = "SELECT * FROM AJK";
            $result = $conn->query($query);
            ?>
            <table>
                <tr>
                    <th>FULL_NAME</th>
                    <th>MATRIX_NUMBER</th>
                    <th>IC_NUMBER</th>
                    <th>GENDER</th>
                    <th>RELIGION</th>
                    <th>RACE</th>
                    <th>EMAIL</th>
                    <th>YEAR</th>
                    <th>COURSE</th>
                    <th>POSITION</th>
                    <th>SEMESTER</th>
                    <th>Actions</th>
                </tr>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr data-matric="<?php echo htmlspecialchars($row['MATRIX_NUMBER']); ?>">
                            <td><?php echo htmlspecialchars($row['FULL_NAME']); ?></td>
                            <td><?php echo htmlspecialchars($row['MATRIX_NUMBER']); ?></td>
                            <td><?php echo htmlspecialchars($row['IC_NUMBER']); ?></td>
                            <td><?php echo htmlspecialchars($row['GENDER']); ?></td>
                            <td><?php echo htmlspecialchars($row['RELIGION']); ?></td>
                            <td><?php echo htmlspecialchars($row['RACE']); ?></td>
                            <td><?php echo htmlspecialchars($row['EMAIL']); ?></td>
                            <td><?php echo htmlspecialchars($row['YEAR']); ?></td>
                            <td><?php echo htmlspecialchars($row['COURSE']); ?></td>
                            <td><?php echo htmlspecialchars($row['POSITION']); ?></td>
                            <td><?php echo htmlspecialchars($row['SEMESTER']); ?></td>
                            <td class="action-buttons">
                                <button class="update-btn">Update</button>
                                <button class="delete-btn">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12">No records found</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <!-- JavaScript -->
        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const content = document.getElementById('content');
                sidebar.classList.toggle('hidden');
                content.classList.toggle('sidebar-hidden');
            }

            document.addEventListener("DOMContentLoaded", () => {
                const attachEventListeners = () => {
                    document.querySelectorAll(".delete-btn").forEach(button => {
                        button.addEventListener("click", async (event) => {
                            const row = event.target.closest("tr");
                            const matric = row.getAttribute("data-matric");

                            if (confirm(`Are you sure you want to delete the record with Matric: ${matric}?`)) {
                                try {
                                    const response = await fetch("display.php", {
                                        method: "POST",
                                        headers: { 
                                            "Content-Type": "application/x-www-form-urlencoded"
                                        },
                                        body: `matric=${encodeURIComponent(matric)}`
                                    });

                                    const data = await response.json();

                                    if (data.success) {
                                        alert(data.message);
                                        location.reload();
                                    } else {
                                        alert(`Failed to delete: ${data.message}`);
                                    }
                                } catch (error) {
                                    console.error("Error deleting record:", error);
                                }
                            }
                        });
                    });

                    document.querySelectorAll(".update-btn").forEach(button => {
                        button.addEventListener("click", (event) => {
                            const row = event.target.closest("tr");
                            const matric = row.getAttribute("data-matric");

                            window.location.href = `ajkinfo2.php?matric=${encodeURIComponent(matric)}`;
                        });
                    });
                };

                attachEventListeners();
            });
        </script>
    </body>
</html>

<?php $conn->close(); ?>