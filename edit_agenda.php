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

// Handle editing an existing agenda
if (isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $id = $_GET['id'];
    $agenda = $conn->real_escape_string($_POST['agenda']);

    // Update the agenda
    $sql = "UPDATE agenda SET agenda='$agenda' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('അജണ്ട വിജയകരമായി പുതുക്കി!');
                window.location.href='view_agenda.php'; // Redirect to view_agenda.php after edit
              </script>";
    } else {
        echo "Error: " . $conn->error;
    }
} elseif (isset($_GET['id'])) {
    // Fetch existing agenda details for editing
    $id = $_GET['id'];
    $sql = "SELECT agenda, date FROM agenda WHERE id = $id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

// Handle submitting a new agenda
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $agenda = $conn->real_escape_string($_POST['agenda']);

    // Insert the new agenda
    $sql = "INSERT INTO agenda (agenda, date) VALUES ('$agenda', CURDATE())";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('പുതിയ അജണ്ട വിജയകരമായി ചേർത്തു!');
                window.location.href='view_agenda.php'; // Redirect to view_agenda.php after adding
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
  <title>Edit or Add Agenda</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'ml-tt-revathy', Arial, sans-serif;
      font-size: 14px;
      background-color: #f9f9f9;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
      flex-direction: column;
    }
    .container {
      width: 90%;
      max-width: 800px;
      padding: 20px;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      margin-top: 20px;
    }
    .emblem {
      display: block;
      margin: 0 auto 10px;
      max-width: 100px;
    }
    .heading {
      text-align: center;
      color: #333;
      font-weight: bold;
    }
    .date {
      text-align: center;
      margin-bottom: 20px;
      font-size: 16px;
      color: #555;
    }
    .form-container {
      margin-top: 40px;
    }
  </style>
</head>
<body>
<div class="container">
  <!-- Emblem -->
  <img src="image/Logo_of_Markazu_Saqafathi_Sunniyya.png" alt="Emblem" class="emblem">

  <!-- Heading -->
  <h4 class="heading">മർകസു സഖാഫത്തി സുന്നിയ്യ</h4>
  <h5 class="heading">അജണ്ട തിരുത്തുക</h5>

  <!-- Date -->
  <?php if (isset($row)): ?>
    <div class="date">തീയതി: <?php echo htmlspecialchars($row['date']); ?></div>
  <?php endif; ?>

  <!-- Button to show the form for editing agenda -->
  <button class="btn btn-primary" onclick="document.getElementById('editForm').style.display='block'">Edit Agenda</button>

  <!-- Form to Edit Agenda (hidden initially) -->
  <?php if (isset($row)): ?>
    <div id="editForm" style="display: none;">
      <form method="POST">
        <div class="mb-3">
          <label for="agenda" class="form-label">അജണ്ട</label>
          <textarea class="form-control" id="agenda" name="agenda" rows="4" required><?php echo htmlspecialchars($row['agenda']); ?></textarea>
        </div>
        <button type="submit" name="edit" class="btn btn-primary">പുതുക്കുക</button>
        <a href="view_agenda.php" class="btn btn-secondary">റദ്ദാക്കുക</a>
      </form>
    </div>
  <?php endif; ?>

  <!-- Button to show the form for adding a new agenda -->
  <button class="btn btn-success mt-4" onclick="document.getElementById('newAgendaForm').style.display='block'">Add New Agenda</button>

  <!-- New Agenda Form (hidden initially) -->
  <div id="newAgendaForm" style="display: none;" class="form-container">
    <form method="POST">
      <div class="mb-3">
        <label for="agenda" class="form-label">അജണ്ട</label>
        <textarea class="form-control" id="agenda" name="agenda" rows="4" required></textarea>
      </div>
      <button type="submit" name="submit" class="btn btn-success">ചേർക്കുക</button>
      <a href="view_agenda.php" class="btn btn-secondary">റദ്ദാക്കുക</a>
    </form>
  </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
