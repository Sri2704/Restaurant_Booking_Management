<?php
session_start();
// include("auth.php");
include("config.php");

$result = $conn->query("SELECT * FROM stock ORDER BY stock_id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Stock</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-green-100 min-h-screen p-10">
  <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow-lg">
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
</body>
</html>

<?php $conn->close(); ?>