<?php
// Database connection (replace with your credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Lab_5b";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $matric = $_GET['matric'];

    $sql = "DELETE FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);

    if ($stmt->execute()) {
        // Check if any users remain after deletion
        $sql_count = "SELECT COUNT(*) FROM users";
        $result = $conn->query($sql_count);
        $row = $result->fetch_row();
        $user_count = $row[0];

        if ($user_count == 0) {
            // Log out the user (adjust the logout mechanism based on your session handling)
            session_start();
            session_destroy();
            echo "Last user deleted. Logging out...";
            header("refresh:2; url=login.php"); // Redirect to login page
            exit();
        } else {
            echo "User deleted successfully! Redirecting to dashboard...";
            header("refresh:2; url=admin.php"); // Redirect to dashboard
            exit();
        }
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>