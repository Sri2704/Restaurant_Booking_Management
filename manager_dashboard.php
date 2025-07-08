<!-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manager Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-50 via-white to-blue-100 min-h-screen">
  <div class="flex">
    Sidebar -->
    <!-- <div class="w-64 min-h-screen bg-gradient-to-b from-blue-900 to-blue-700 text-white p-6 space-y-6 shadow-xl">
      <h2 class="text-3xl font-bold text-center">Manager Panel</h2>
      <div class="border-b border-blue-500 my-4"></div>
      <a href="add_stock.php" class="block px-4 py-2 rounded hover:bg-blue-600 transition">Add Stock</a>
      <a href="update_stock.php" class="block px-4 py-2 rounded hover:bg-blue-600 transition">Update Stock</a>
      <a href="delete_stock.php" class="block px-4 py-2 rounded hover:bg-blue-600 transition">Delete Stock</a>
      <a href="view_stock.php" class="block px-4 py-2 rounded hover:bg-blue-600 transition">View Stock</a>
      <a href="view_logs.php" class="block px-4 py-2 rounded hover:bg-blue-600 transition">View Logs</a>
      <a href="logout.php" class="block mt-10 px-4 py-2 bg-red-500 hover:bg-red-600 rounded text-center">Logout</a>
    </div> -->

    <!-- Main Content -->
    <!-- <div class="flex-1 p-10">
      <h1 class="text-4xl font-extrabold text-blue-900 mb-4">Welcome</h1>
      <p class="text-lg text-gray-700">You are logged in as a <strong>Manager</strong>
    </div>
  </div>
</body>
</html> -->

<?php
session_start();
include("config.php");

// Fetch stock data
$result = $conn->query("SELECT * FROM stock ORDER BY stock_id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manager Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-50 via-white to-blue-100 min-h-screen">
  <div class="flex">
    <!-- Sidebar -->
    <div class="w-64 min-h-screen bg-gradient-to-b from-blue-900 to-blue-700 text-white p-6 space-y-6 shadow-xl">
      <h2 class="text-3xl font-bold text-center">Manager Panel</h2>
      <div class="border-b border-blue-500 my-4"></div>
      <a href="member.php" class="block px-4 py-2 rounded hover:bg-blue-600 transition">Member Dashboard</a>
      <a href="table_selection copy.php" class="block px-4 py-2 rounded hover:bg-blue-600 transition">Managing Tables and Booking Orders</a>
      <a href="add_stock.php" class="block px-4 py-2 rounded hover:bg-blue-600 transition">Add Stock</a>
      <a href="update_stock.php" class="block px-4 py-2 rounded hover:bg-blue-600 transition">Update Stock</a>
      <a href="delete_stock.php" class="block px-4 py-2 rounded hover:bg-blue-600 transition">Delete Stock</a>
      <a href="view_stock.php" class="block px-4 py-2 rounded bg-blue-600 transition">View Stock</a>
      <a href="view_logs.php" class="block px-4 py-2 rounded hover:bg-blue-600 transition">View Logs</a>
      <a href="logout.php" class="block mt-10 px-4 py-2 bg-red-500 hover:bg-red-600 rounded text-center">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-10">
      <h1 class="text-4xl font-extrabold text-blue-900 mb-6">Welcome!</h1>
      <p class="text-1xl font-extrabold text-blue-900 mb-6">You are login as Manager </p>

      <!-- Stock Table -->
      <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-center text-blue-800 mb-6">Current Stock List</h2>
        <table class="min-w-full table-auto border border-blue-300">
          <thead>
            <tr class="bg-blue-100 text-blue-900">
              <th class="px-4 py-2 border">Stock ID</th>
              <th class="px-4 py-2 border">Product Name</th>
              <th class="px-4 py-2 border">Quantity</th>
              <th class="px-4 py-2 border">Availability</th>
              <th class="px-4 py-2 border">Added On</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr class="text-center hover:bg-gray-100">
                <td class="px-4 py-2 border"><?php echo $row['stock_id']; ?></td>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td class="px-4 py-2 border"><?php echo $row['quantity']; ?></td>
                <td class="px-4 py-2 border"><?php echo ucfirst($row['availability_flag']); ?></td>
                <td class="px-4 py-2 border"><?php echo $row['created_at']; ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>

<?php $conn->close(); ?>

