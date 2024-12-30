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

// Get the dates for the current week (from Monday to Sunday)
$dates = [];
for ($i = 0; $i < 7; $i++) {
    $dates[] = date('Y-m-d', strtotime("this week +$i days"));
}

// Fetch agendas for each day of the week
$agendas = [];
foreach ($dates as $date) {
    $sql = "SELECT department, agenda FROM agenda WHERE date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $agendas[$date] = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Weekly Agenda</title>
  <!-- Bootstrap CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >
  <style>
    body {
      background-color: #f9f9f9;
    }
    .weekly-agenda-container {
      max-width: 900px;
      margin: 40px auto;
      padding: 20px;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .weekly-agenda-container h1 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }
    .card {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<div class="weekly-agenda-container">
  <h1>Weekly Agenda</h1>

  <?php foreach ($dates as $date): ?>
    <div class="card">
      <div class="card-header">
        <h5 class="card-title"><?= date('l, F j, Y', strtotime($date)) ?></h5>
      </div>
      <div class="card-body">
        <?php if (!empty($agendas[$date])): ?>
          <?php foreach ($agendas[$date] as $agenda): ?>
            <div class="card mb-2">
              <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($agenda['department']) ?> Department</h6>
                <p class="card-text"><?= htmlspecialchars($agenda['agenda']) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-center">No agendas for this day.</p>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>

</div>

</body>
</html>

<?php $conn->close(); ?>
