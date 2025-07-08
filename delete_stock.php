<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = trim($_POST['product_name']);

    // Step 1: Get stock info by product name
    $info_stmt = $conn->prepare("SELECT stock_id, quantity, availability_flag FROM stock WHERE product_name = ?");
    $info_stmt->bind_param("s", $product_name);
    $info_stmt->execute();
    $info_stmt->store_result();

    if ($info_stmt->num_rows === 1) {
        $info_stmt->bind_result($stock_id, $quantity, $availability_flag);
        $info_stmt->fetch();
        $info_stmt->close();

        // Step 2: Log before deletion
        

        $performed_by = $_SESSION['login_user'];
        $role = $_SESSION['role'];
        $action = 'delete';

        $log_sql = "INSERT INTO stock_logs (stock_id, product_name, quantity, availability_flag, action, performed_by, role) VALUES ($stock_id, '$product_name', $quantity, '$availability_flag', '$action', '$performed_by', '$role')";

        mysqli_query($conn, $log_sql);




        // Step 3: Delete using product_name
        $del_stmt = $conn->prepare("DELETE FROM stock WHERE product_name = ?");
        $del_stmt->bind_param("s", $product_name);
        if ($del_stmt->execute()) {
            echo "<script>alert('Stock deleted successfully.'); window.location.href='manager_dashboard.php';</script>";
        } else {
            echo "<script>alert('Failed to delete stock.'); window.location.href='delete_stock.php';</script>";
        }
        $del_stmt->close();
    } else {
        echo "<script>alert('Product not found.'); window.location.href='delete_stock.php';</script>";
        $info_stmt->close();
    }

    $conn->close();
}
?>

<!-- HTML Form Below -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Stock</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-red-50 to-red-100 min-h-screen flex items-center justify-center">
  <form action="delete_stock.php" method="POST" class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center text-red-700">Delete Stock Item</h2>

    <label class="block mb-2 font-semibold text-gray-700">Product Name to Delete:</label>
    <input type="text" name="product_name" required class="w-full px-4 py-2 mb-6 border rounded" />

    <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">Delete Stock</button>
  </form>
</body>
</html>