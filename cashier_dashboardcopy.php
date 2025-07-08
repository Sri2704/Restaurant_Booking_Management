<?php
session_start();
include("config.php");

// Redirect if no table selected
if (!isset($_SESSION['selected_table'])) {
    header('Location:table_selection.php');
    exit;
}



// Fetch selected table name
$selected_table_id = $_SESSION['selected_table'];
$table_name = '';
$table_sql = "SELECT table_name FROM tables WHERE table_id = ?";
$table_stmt = $conn->prepare($table_sql);
$table_stmt->bind_param("i", $selected_table_id);
$table_stmt->execute();
$table_stmt->bind_result($table_name);
$table_stmt->fetch();
$table_stmt->close();

// Fetch available stocks
$stocks = array();
$sql = "SELECT stock_id, product_name, quantity, price FROM stock WHERE availability_flag = 'yes'";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $stocks[] = $row;
    }
}

// Helper to get stock by ID
function get_stock_by_id($stocks, $stock_id) {
    foreach ($stocks as $stock) {
        if ($stock['stock_id'] == $stock_id) {
            return $stock;
        }
    }
    return null;
}

// Initialize cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $stock_id = $_POST['stock_id'];
    $quantity = intval($_POST['quantity']);
    $stock = get_stock_by_id($stocks, $stock_id);
    if ($stock) {
        if ($stock['quantity'] >= $quantity) {
            if (isset($_SESSION['cart'][$stock_id])) {
                $_SESSION['cart'][$stock_id] += $quantity;
            } else {
                $_SESSION['cart'][$stock_id] = $quantity;
            }
        } else {
            $error = "Not enough stock available.";
        }
    } else {
        $error = "Stock not available.";
    }
}

// Remove from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_cart'])) {
    $stock_id = $_POST['stock_id'];
    unset($_SESSION['cart'][$stock_id]);
}

// Generate bill: store in DB and redirect to preview
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_bill'])) {
    $bill_items = [];
    $bill_total = 0;
    $all_items_ok = true;
    foreach ($_SESSION['cart'] as $stock_id => $qty) {
        $stock = get_stock_by_id($stocks, $stock_id);
        if ($stock && $stock['quantity'] >= $qty) {
            $item_total = $stock['price'] * $qty;
            $bill_items[] = [
                'product_name' => $stock['product_name'],
                'quantity' => $qty,
                'price' => $stock['price'],
                'total' => $item_total
            ];
            $bill_total += $item_total;
        } else {
            $error = "Insufficient stock for product " . ($stock ? $stock['product_name'] : $stock_id);
            $all_items_ok = false;
            break;
        }
    }
    if ($all_items_ok && count($bill_items) > 0) {
        // Insert bill (with table_id)
        $bill_number = uniqid("BILL");
        $cashier = isset($_SESSION['login_user']) ? $_SESSION['login_user'] : "cashier";
        $stmt = $conn->prepare("INSERT INTO bills (bill_number, cashier, total_amount, table_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdi", $bill_number, $cashier, $bill_total, $selected_table_id);
        $stmt->execute();
        $bill_id = $stmt->insert_id;
        $stmt->close();

        // Insert bill items and update stock
        foreach ($bill_items as $item) {
            $stmt = $conn->prepare("INSERT INTO bill_items (bill_id, product_name, quantity, price, total) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isidd", $bill_id, $item['product_name'], $item['quantity'], $item['price'], $item['total']);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("UPDATE stock SET quantity = quantity - ? WHERE product_name = ?");
            $stmt->bind_param("is", $item['quantity'], $item['product_name']);
            $stmt->execute();
            $stmt->close();
        }
        // Store bill info for preview, then redirect to preview mode
        $_SESSION['last_bill'] = [
            'bill_number' => $bill_number,
            'cashier' => $cashier,
            'bill_items' => $bill_items,
            'bill_total' => $bill_total,
            'date' => date('Y-m-d H:i:s'),
            'table_name' => $table_name
        ];
        $_SESSION['cart'] = array();
        header("Location: cashier_dashboardcopy.php?preview=1");
        exit;
    }
}

// Bill preview mode
if (isset($_GET['preview']) && $_GET['preview'] == 1 && isset($_SESSION['last_bill'])) {
    $bill = $_SESSION['last_bill'];
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Bill Preview</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100 flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-lg" id="bill-preview">
            <h2 class="text-2xl font-bold mb-4 text-center text-blue-700">Bill Preview</h2>
            <div class="mb-2">Bill Number: <b><?= htmlspecialchars($bill['bill_number']) ?></b></div>
            <div class="mb-2">Table: <b><?= htmlspecialchars($bill['table_name']) ?></b></div>
            <div class="mb-2">Cashier: <b><?= htmlspecialchars($bill['cashier']) ?></b></div>
            <div class="mb-2">Date: <b><?= htmlspecialchars($bill['date']) ?></b></div>
            <table class="w-full mb-4 border">
                <thead>
                    <tr>
                        <th class="border p-2">Product</th>
                        <th class="border p-2">Qty</th>
                        <th class="border p-2">Price</th>
                        <th class="border p-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bill['bill_items'] as $item): ?>
                        <tr>
                            <td class="border p-2"><?= htmlspecialchars($item['product_name']) ?></td>
                            <td class="border p-2"><?= $item['quantity'] ?></td>
                            <td class="border p-2">₹<?= number_format($item['price'], 2) ?></td>
                            <td class="border p-2">₹<?= number_format($item['total'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="font-bold text-right mb-4">Grand Total: ₹<?= number_format($bill['bill_total'], 2) ?></div>
            <div class="flex justify-between">
                <a href="cashier_dashboardcopy.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">Back to Dashboard</a>
                <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded hover:bg-blue-700 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-6 0v4m0 0h4m-4 0H8"/>
                    </svg>Print
                </button>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cashier Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Cashier Dashboard</h1>
        <div class="text-xl font-semibold text-blue-800">
             <?= htmlspecialchars($table_name) ?>
        </div>
    </div>
    <?php if (isset($error)): ?>
        <div class="bg-red-200 text-red-800 p-3 mb-4 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <div class="flex gap-6">
        <!-- Stock List -->
        <div class="w-2/3 bg-white p-4 rounded shadow">
            <h2 class="text-xl font-semibold mb-4">Available Stocks</h2>
            <input
                type="text"
                id="stockSearch"
                placeholder="Search for products..."
                class="w-full px-4 py-2 mb-4 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                onkeyup="filterStockTable()"
            />
            <table id="stockTable" class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="border-b p-2">Product Name</th>
                        <th class="border-b p-2">Quantity</th>
                        <th class="border-b p-2">Price (₹)</th>
                        <th class="border-b p-2">Add to Cart</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stocks as $stock): ?>
                    <tr>
                        <td class="border-b p-2"><?= htmlspecialchars($stock['product_name']) ?></td>
                        <td class="border-b p-2"><?= $stock['quantity'] ?></td>
                        <td class="border-b p-2">₹<?= number_format($stock['price'], 2) ?></td>
                        <td class="border-b p-2">
                            <form method="POST" class="flex items-center gap-2">
                                <input type="hidden" name="stock_id" value="<?= $stock['stock_id'] ?>">
                                <input type="number" name="quantity" value="1" min="1" max="<?= $stock['quantity'] ?>" class="w-16 p-1 border rounded">
                                <button type="submit" name="add_to_cart" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Add</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="flex justify-end mb-4">
                <a href="logout.php" class="block mt-10 px-4 py-2 bg-red-500 hover:bg-red-600 rounded text-center">Logout</a>
            </div>
        </div>
        <!-- Cart and Generate Bill -->
        <div class="w-1/3 bg-white p-4 rounded shadow flex flex-col">
            <h2 class="text-xl font-semibold mb-4">Cart</h2>
            <?php if (!empty($_SESSION['cart'])): ?>
                <form method="POST">
                    <table class="w-full text-left border-collapse mb-4">
                        <thead>
                            <tr>
                                <th class="border-b p-2">Product</th>
                                <th class="border-b p-2">Price</th>
                                <th class="border-b p-2">Quantity</th>
                                <th class="border-b p-2">Total</th>
                                <th class="border-b p-2">Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grand_total = 0;
                            foreach ($_SESSION['cart'] as $stock_id => $qty):
                                $stock = get_stock_by_id($stocks, $stock_id);
                                if (!$stock) continue;
                                $product_name = $stock['product_name'];
                                $price = $stock['price'];
                                $total = $price * $qty;
                                $grand_total += $total;
                            ?>
                            <tr>
                                <td class="border-b p-2"><?= htmlspecialchars($product_name) ?></td>
                                <td class="border-b p-2">₹<?= number_format($price, 2) ?></td>
                                <td class="border-b p-2"><?= $qty ?></td>
                                <td class="border-b p-2">₹<?= number_format($total, 2) ?></td>
                                <td class="border-b p-2">
                                    <button type="submit" name="remove_from_cart" value="<?= $stock_id ?>" formaction="?remove_from_cart=<?= $stock_id ?>" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" onclick="return confirm('Remove this item?');">Remove</button>
                                    <input type="hidden" name="stock_id" value="<?= $stock_id ?>">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="font-bold mb-4">Grand Total: ₹<?= number_format($grand_total, 2) ?></div>
                    <button type="submit" name="generate_bill" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Generate Bill</button>
                </form>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<script>
function filterStockTable() {
    const input = document.getElementById('stockSearch');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('stockTable');
    const trs = table.getElementsByTagName('tr');
    for (let i = 1; i < trs.length; i++) { // skip header row
        const rowText = trs[i].textContent.toLowerCase();
        trs[i].style.display = rowText.includes(filter) ? '' : 'none';
    }
}
</script>
