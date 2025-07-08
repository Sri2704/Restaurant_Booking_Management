<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = trim($_POST['product_name']);
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);
    $availability_flag = $_POST['availability_flag'] === 'yes' ? 'yes' : 'no';

    // Insert into stock table
    // $stmt = $conn->prepare("INSERT INTO stock (product_name, quantity, availability_flag) VALUES (?, ?, ?)");
    // $stmt->bind_param("sis", $product_name, $quantity, $availability_flag);


    $stmt = $conn->prepare("INSERT INTO stock (product_name, quantity, price, availability_flag) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdis", $product_name, $quantity, $price, $availability_flag);
    if ($stmt->execute()) {
        $stock_id = $stmt->insert_id; // newly inserted stock ID

        // Prepare log insertion with all relevant values
        // $log_stmt = $conn->prepare("INSERT INTO stock_logs (stock_id, product_name, quantity, availability_flag, action, performed_by, role) VALUES (?, ?, ?, ?, 'add', ?, ?)");
        // $log_stmt->bind_param("isisss", $stock_id, $product_name, $quantity, $availability_flag, $_SESSION['login_user'], $_SESSION['role']);

        $log_stmt = $conn->prepare("INSERT INTO stock_logs (stock_id, product_name, quantity, availability_flag, action, performed_by, role) VALUES (?, ?, ?, ?, 'add', ?, ?)");
        $log_stmt->bind_param("isisss", $stock_id, $product_name, $quantity, $availability_flag, $_SESSION['login_user'], $_SESSION['role']);
        $log_stmt->execute();
        $log_stmt->close();

        echo "<script>alert('Stock added successfully.'); window.location.href='manager_dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to add stock.'); window.location.href='add_stock.php';</script>";
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
  <title>Add Stock</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-green-50 to-blue-100 min-h-screen flex items-center justify-center">
  <form action="add_stock.php" method="POST" class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center text-blue-700">Add Stock Item</h2>

    <label class="block mb-2 font-semibold text-gray-700">Product Name:</label>
    <input type="text" name="product_name" required class="w-full px-4 py-2 mb-4 border rounded" />

    <label class="block mb-2 font-semibold text-gray-700">Quantity:</label>
    <input type="number" name="quantity" required class="w-full px-4 py-2 mb-4 border rounded" />

    <label class="block mb-2 font-semibold text-gray-700">Price (₹):</label>
    <input type="number" name="price" step="0.01" min="0" required class="w-full px-4 py-2 mb-4 border rounded" />

    <label class="block mb-2 font-semibold text-gray-700">Availability:</label>
    <select name="availability_flag" required class="w-full px-4 py-2 mb-6 border rounded">
      <option value="yes">Yes</option>
      <option value="no">No</option>
    </select>

    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Add Stock</button>
  </form>
</body>
</html>