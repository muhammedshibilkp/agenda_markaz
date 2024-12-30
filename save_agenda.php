<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "agenda";

$conn = new mysqli($host, $user, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departments = $_POST['department'];
    $agendas = $_POST['agenda'];
    $date = $_POST['date'];

    // Prepare the SQL query with placeholders
    $stmt = $conn->prepare("INSERT INTO agenda (department, agenda, date) VALUES (?, ?, ?)");

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Loop through the arrays and bind parameters to insert multiple agendas
    for ($i = 0; $i < count($agendas); $i++) {
        $department = $departments[$i];
        $agenda = $agendas[$i];

        // Bind the parameters
        $stmt->bind_param("sss", $department, $agenda, $date);

        // Execute the query
        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error . "<br>";
        }
    }

    // Close the prepared statement
    $stmt->close();

    // Redirect to the view_agenda page with a success message
    echo "<script>
            alert('Agenda(s) submitted successfully!');
            window.location.href='view_agenda.php';
          </script>";
}

$conn->close();
?>
