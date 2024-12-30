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

// Fetch all departments from the database
$departments_result = $conn->query("SELECT id, name FROM departments"); // Assuming you have a 'departments' table

// Handle new agenda submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $agenda = $conn->real_escape_string($_POST['agenda']);
    $department_id = (int)$_POST['department']; // Department ID selected by the user

    // Insert the new agenda
    $sql = "INSERT INTO agenda (department_id, agenda, date) VALUES ('$department_id', '$agenda', CURDATE())";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('പുതിയ അജണ്ട വിജയകരമായി ചേർത്തു!');
                window.location.href='view_agenda.php';
              </script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ml">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Agenda</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
  <!-- Heading -->
  <h4 class="heading">പുതിയ അജണ്ട ചേർക്കുക</h4>

  <!-- Add Agenda Form -->
  <form method="POST">
    <div class="mb-3">
      <label for="department" class="form-label">വിഭാഗം</label>
      <select class="form-control" id="department" name="department" required>
        <option value="">വിഭാഗം തിരഞ്ഞെടുത്തു</option>
        <?php while ($row = $departments_result->fetch_assoc()): ?>
          <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    
    <div class="mb-3">
      <label for="agenda" class="form-label">അജണ്ട</label>
      <textarea class="form-control" id="agenda" name="agenda" rows="4" required></textarea>
    </div>
    <button type="submit" name="submit" class="btn btn-success">ചേർക്കുക</button>
    <a href="view_agenda.php" class="btn btn-secondary">റദ്ദാക്കുക</a>
  </form>
</div>
</body>
</html>

<?php $conn->close(); ?>
