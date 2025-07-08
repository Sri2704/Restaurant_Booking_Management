<?php
session_start();
// include("auth.php");
include("config.php");

// If the form is submitted for update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stock_id = intval($_POST['stock_id']);
    $product_name = trim($_POST['product_name']);
    $quantity = intval($_POST['quantity']);
    $availability_flag = $_POST['availability_flag'] === 'yes' ? 'yes' : 'no';

    $stmt = $conn->prepare("UPDATE stock SET product_name = ?, quantity = ?, availability_flag = ? WHERE stock_id = ?");
    $stmt->bind_param("sisi", $product_name, $quantity, $availability_flag, $stock_id);

    if ($stmt->execute()) {
        // $log_stmt = $conn->prepare("INSERT INTO stock_logs (stock_id, action, performed_by, role) VALUES (?, 'update', ?, ?)");
        $log_stmt = $conn->prepare("INSERT INTO stock_logs (stock_id, product_name, quantity, availability_flag, action, performed_by, role) VALUES (?, ?, ?, ?, 'update', ?, ?)");
        // $log_stmt->bind_param("iss", $stock_id, $_SESSION['login_user'], $_SESSION['role']);
        $log_stmt->bind_param("isisss", $stock_id, $product_name, $quantity, $availability_flag, $_SESSION['login_user'], $_SESSION['role']);
        $log_stmt->execute();
        $log_stmt->close();
        echo "<script>alert('Stock updated successfully.'); window.location.href='manager_dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to update stock.'); window.location.href='update_stock.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Stock</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-yellow-50 to-yellow-100 min-h-screen flex items-center justify-center">
  <form action="update_stock.php" method="POST" class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center text-yellow-700">Update Stock Item</h2>

    <label class="block mb-2 font-semibold text-gray-700">Stock ID:</label>
    <input type="number" name="stock_id" required class="w-full px-4 py-2 mb-4 border rounded" />

    <label class="block mb-2 font-semibold text-gray-700">Product Name:</label>
    <input type="text" name="product_name" required class="w-full px-4 py-2 mb-4 border rounded" />

    <label class="block mb-2 font-semibold text-gray-700">Quantity:</label>
    <input type="number" name="quantity" required class="w-full px-4 py-2 mb-4 border rounded" />

    <label class="block mb-2 font-semibold text-gray-700">Availability:</label>
    <select name="availability_flag" required class="w-full px-4 py-2 mb-6 border rounded">
      <option value="yes">Yes</option>
      <option value="no">No</option>
    </select>

    <button type="submit" class="w-full bg-yellow-600 text-white py-2 rounded hover:bg-yellow-700">Update Stock</button>
  </form>
</body>
</html>