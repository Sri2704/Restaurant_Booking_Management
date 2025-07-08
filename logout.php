<?php
session_start();

// Destroy all session variables
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="refresh" content="2;url=login.html" /> <!-- Change this to your login page -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <title>Logout</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-50 to-blue-100 h-screen flex items-center justify-center">
  <div class="bg-white p-6 rounded shadow-lg text-center">
    <h2 class="text-2xl font-semibold text-blue-800 mb-4">Logout Successful</h2>
    <p class="text-gray-700">Redirecting to login page...</p>
  </div>

  <script>
    alert("You have been logged out successfully.");
  </script>
  <meta http-equiv="refresh" content="2;url=login.html" />
</body>
</html>