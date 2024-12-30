<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "agenda";

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the ID of the agenda to delete
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL to delete the agenda
    $sql = "DELETE FROM agenda WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('അജണ്ട മായിച്ചു!');
                window.location.href = 'view_agenda.php';
              </script>";
    } else {
        echo "Error deleting agenda: " . $conn->error;
    }
}

$conn->close();
?>
