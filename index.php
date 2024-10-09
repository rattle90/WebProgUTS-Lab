<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <!-- Link Tailwind CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
  <!-- Include Navbar -->
  <?php include 'component/navbar.php'; ?>

  <!-- Main Content (Dashboard Container) -->
  <div class="pt-20"> <!-- Adjust padding to give space under the navbar -->
    <?php include 'dashboard.php'; ?>
  </div>
</body>
</html>
