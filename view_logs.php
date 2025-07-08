<?php
session_start();
// include("auth.php");
include("config.php");

$result = $conn->query("SELECT * FROM stock_logs ORDER BY log_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Stock Logs</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen p-10">
  <div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Stock Logs</h2>
    <table class="min-w-full table-auto border border-gray-300">
      <thead>
        <tr class="bg-gray-100 text-gray-800">
          <th class="px-4 py-2 border">Log ID</th>
          <th class="px-4 py-2 border">Stock ID</th>
          <th class="px-4 py-2 border">Product Name</th>
          <th class="px-4 py-2 border">Quantity</th>
          <th class="px-4 py-2 border">Availability</th>
          <th class="px-4 py-2 border">Action</th>
          <th class="px-4 py-2 border">Performed By</th>
          <th class="px-4 py-2 border">Role</th>
          <th class="px-4 py-2 border">Timestamp</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($log = $result->fetch_assoc()): ?>
          <tr class="text-center hover:bg-gray-100">
            <td class="px-4 py-2 border"><?php echo $log['log_id']; ?></td>
            <td class="px-4 py-2 border"><?php echo $log['stock_id']; ?></td>
            <td class="px-4 py-2 border"><?php echo htmlspecialchars($log['product_name']); ?></td>
            <td class="px-4 py-2 border"><?php echo htmlspecialchars($log['quantity']); ?></td>
            <td class="px-4 py-2 border"><?php echo htmlspecialchars($log['availability_flag']); ?></td>
            <td class="px-4 py-2 border"><?php echo htmlspecialchars($log['action']); ?></td>
            <td class="px-4 py-2 border"><?php echo htmlspecialchars($log['performed_by']); ?></td>
            <td class="px-4 py-2 border"><?php echo ucfirst($log['role']); ?></td>
            <td class="px-4 py-2 border"><?php echo $log['timestamp']; ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
        <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded hover:bg-blue-700 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-6 0v4m0 0h4m-4 0H8"/>
          </svg>Print
        </button>
  </div>
</body>
</html>

<?php $conn->close(); ?>