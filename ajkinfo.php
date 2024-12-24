<!DOCTYPE html>
<html>
    <head>
        <title>W3.CSS</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="img/styles.css">
        <style>
            /* Popup Modal */
            .popup {
                display: none; /* Initially hidden */
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                justify-content: center;
                align-items: center;
            }
            .popup-content {
                background-color: white;
                padding: 20px;
                border-radius: 5px;
                text-align: center;
            }
            .popup .btn {
                background-color: #4CAF50;
                color: white;
                padding: 10px 20px;
                border: none;
                cursor: pointer;
                border-radius: 5px;
            }
            .popup .btn:hover {
                background-color: #45a049;
            }
        </style>
    </head>
    <body>
        <!-- Sidebar and Main Content as before -->

        <!-- Main Content Box -->
        <div class="contentBox">
            <h2>Update AJK Details</h2>
            <form id="ajkForm" action="" method="POST" onsubmit="showPopup(event)">
                <!-- Form sections as before -->

                <div class="form-actions">
                    <button type="submit">Update</button>
                </div>
            </form>
        </div>

        <!-- Popup Modal -->
        <div id="successPopup" class="popup">
            <div class="popup-content">
                <h3>Registration Successful</h3>
                <button class="btn" onclick="redirectToDisplay()">OK</button>
            </div>
        </div>

        <!-- JavaScript -->
        <script>
            // Function to show the popup message when the form is submitted
            function showPopup(event) {
                event.preventDefault(); // Prevent form submission to keep the page loaded
                console.log('Popup should show now');  // Debugging: Check if this runs

                // Show custom popup with success message
                const popup = document.getElementById('successPopup');
                popup.style.display = 'flex'; // Make the popup visible
                console.log('Popup is now visible'); // Debugging: Confirm if the popup becomes visible

                // Optional: simulate a successful submission, or perform AJAX to save data
                // setTimeout() is used here to simulate the time before redirecting.
            }

            // Function to redirect to display.php after clicking OK
            function redirectToDisplay() {
                window.location.href = 'display.php'; // Redirect to display.php after OK is clicked
            }
        </script>
    </body>
</html>