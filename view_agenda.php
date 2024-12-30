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

// Fetch agendas for the selected date
$date = isset($_POST['view_date']) ? $_POST['view_date'] : date('Y-m-d'); // Default to today if no date is provided
$sql = "SELECT id, department, agenda, document FROM agenda WHERE date = '$date'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ml">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Agenda</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Font: ml-tt-revathy -->
  <style>
    /* Include ml-tt-revathy font */
    @font-face {
      font-family: 'ml-tt-revathy';
      src: url('path/to/ml-tt-revathy.ttf') format('truetype'); /* Adjust path to the font file */
    }

    body {
      font-family: 'ml-tt-revathi', Arial, sans-serif;
      font-size: 14px;
      background-color: #f9f9f9;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .container {
      width: 90%;
      max-width: 1000px;
      padding: 20px;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
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
      font-family: 'ml-tt-revathi', sans-serif; /* Apply the custom font */
    }

    .date {
      text-align: center;
      margin-bottom: 20px;
      font-size: 16px;
      color: #555;
    }

    .table {
      margin-top: 20px;
    }

    .table th, .table td {
      text-align: left;
      vertical-align: middle;
    }

    .action-buttons {
      text-align: center;
    }

    .action-buttons .btn {
      font-size: 18px;
      padding: 5px;
    }

    .document-link {
      text-decoration: none;
      color: #007bff;
    }
  </style>
</head>
<body>
<div class="container">
  <!-- Emblem -->
  <img src="image/Logo_of_Markazu_Saqafathi_Sunniyya.png" alt="Emblem" class="emblem">

  <!-- Heading -->
  <h4 class="heading">മർകസു സഖാഫത്തി സുന്നിയ്യ</h4>
  <h4 class="heading">ഡയറക്ടർ ബോർഡ്</h4>
  <h5 class="heading">അജണ്ട</h5>

  <!-- Date -->
  <div class="date">തീയതി: <?php echo htmlspecialchars($date); ?></div>

  <!-- Agenda Table -->
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th style="width: 20%;">വിഭാഗം</th>
        <th style="width: 50%;">അജണ്ട</th>
        <th style="width: 20%;">ഡോക്യുമെന്റ്</th>
        <th style="width: 10%; text-align: center;">പ്രവർത്തനങ്ങൾ</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['department']); ?></td>
            <td><?php echo nl2br(htmlspecialchars($row['agenda'])); ?></td>
            <td>
              <?php if ($row['document']): ?>
                <a href="<?php echo htmlspecialchars($row['document']); ?>" class="document-link" target="_blank">View Document</a>
              <?php else: ?>
                No document available
              <?php endif; ?>
            </td>
            <td class="action-buttons">
              <!-- Existing Edit and Delete buttons -->
              <a href="edit_agenda.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-warning btn-sm" title="Edit">
                <i class="bi bi-pencil-square"></i>
              </a>
              <a href="delete_agenda.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" title="Delete" onclick="return confirm('ഈ അജണ്ട ഡിലീറ്റ് ചെയ്യുമോ?');">
                <i class="bi bi-trash"></i>
              </a>

              <!-- New Add Agenda button -->
              <a href="add_agenda.php" class="btn btn-outline-success btn-sm" title="Add Agenda">
                <i class="bi bi-plus-circle"></i>
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="4" style="text-align: center;">ഈ തീയതിയിൽ ഏജൻഡകൾ ഇല്ല.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>

<?php $conn->close(); ?>
