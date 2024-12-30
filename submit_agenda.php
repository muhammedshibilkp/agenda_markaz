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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departments = $_POST['department'];
    $agendas = $_POST['agenda'];
    $date = $_POST['date'];

    // Prepare the SQL query with placeholders for agenda insertion
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

        // Handle file uploads for each agenda
        if (isset($_FILES['documents']['name'][$i]) && $_FILES['documents']['name'][$i] != '') {
            $fileName = $_FILES['documents']['name'][$i];
            $fileTmpName = $_FILES['documents']['tmp_name'][$i];
            $fileSize = $_FILES['documents']['size'][$i];
            $fileError = $_FILES['documents']['error'][$i];

            if ($fileError === 0) {
                // Generate a unique name for the file
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $newFileName = uniqid('', true) . "." . $fileExt;
                $uploadDirectory = "uploads/";
                $fileDestination = $uploadDirectory . $newFileName;

                // Move the file to the uploads directory
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    // Insert the file path into the database
                    $stmt = $conn->prepare("UPDATE agenda SET document = ? WHERE department = ? AND agenda = ? AND date = ?");
                    $stmt->bind_param("ssss", $fileDestination, $department, $agenda, $date);
                    $stmt->execute();
                } else {
                    echo "Error uploading file.";
                }
            }
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

<!DOCTYPE html>
<html lang="ml">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Submit Agenda</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Add Malayalam font from Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Malayalam:wght@400;700&display=swap" rel="stylesheet">

  <style>
    /* Apply Malayalam font to the navbar and headings */
    body {
      font-family: 'Noto Sans Malayalam', sans-serif;
    }

    .navbar {
      background-color: #007bff;
    }

    .navbar-brand {
      font-family: 'Noto Sans Malayalam', sans-serif;
      font-size: 24px;
      font-weight: bold;
    }

    h5 {
      font-family: 'Noto Sans Malayalam', sans-serif;
      font-weight: bold;
      font-size: 20px;
    }

    .container {
      margin-top: 20px;
    }

    .navbar {
      background-color: #007bff;
      color: white;
    }
    .malayalam-subtitle {
      font-family: 'Noto Sans Malayalam', sans-serif;
      font-weight: bold; /* Regular */
    }
  </style>
</head>

<body>
<!-- App Bar with Emblem and Title -->
<<!-- App Bar with Emblem and Title -->
<!-- App Bar with Emblem and Title -->
<nav class="navbar navbar-light bg-primary">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <!-- Left side content: Logo and "Markazu Saqafathi" -->
    <div class="d-flex align-items-center">
      <img src="image/Logo_of_Markazu_Saqafathi_Sunniyya.png" alt="Emblem" class="navbar-brand" style="max-width: 50px;">
      <span class="navbar-brand ms-3">മർകസു സഖാഫത്തി സുന്നിയ്യ</span>
    </div>
  </div>
</nav>



  <!-- Main Content -->
  <div class="container mt-5">
  <h4 class="text-center mb-4 malayalam-font text-primary fw-bold" 
    style="font-size: 1.8rem; letter-spacing: 1px; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2); font-weight: bold;">
  ഡയറക്ടർ ബോർഡ്
</h4>
    <!-- Agenda Submission Page Heading -->
    <h4 class="text-center mb-4 malayalam-subtitle text-secondary">അജണ്ടകൾ സമർപ്പിക്കുന്ന പേജ്</h4>


    <form action="" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="department" class="form-label">Select Department</label>
        <select id="department" name="department[]" class="form-select" required>
          <option value="" disabled selected>Choose a department</option>
          <option value="Education">Education</option>
          <option value="MMI & MGS">MMI & MGS</option>
          <option value="Culture">Culture</option>
          <option value="Media">Media</option>
          <option value="Works">Works</option>
          <option value="Other">Other</option>
        </select>
      </div>

      <div id="agenda-fields">
        <div class="mb-3">
          <label for="agenda" class="form-label">Agenda</label>
          <textarea id="agenda" name="agenda[]" class="form-control" rows="4" placeholder="Enter the agenda details" required></textarea>
        </div>
        <div class="mb-3">
          <label for="documents" class="form-label">Upload Document for this Agenda</label>
          <input type="file" name="documents[]" class="form-control">
        </div>
      </div>

      <button type="button" id="add-agenda" class="btn btn-secondary mb-3">Add Another Agenda</button>

      <div class="mb-3">
        <label for="date" class="form-label">Select Date</label>
        <input type="date" id="date" name="date" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>

  <script>
    document.getElementById('add-agenda').addEventListener('click', function() {
      const agendaField = document.querySelector('#agenda-fields').firstElementChild.cloneNode(true);
      const documentField = document.querySelector('#agenda-fields').children[1].cloneNode(true);
      const agendaFieldsContainer = document.getElementById('agenda-fields');
      
      agendaFieldsContainer.appendChild(agendaField);
      agendaFieldsContainer.appendChild(documentField);
    });
  </script>

</body>
</html>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>

