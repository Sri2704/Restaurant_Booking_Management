<?php
session_start();
include("config.php");

// Redirect if no table selected
if (!isset($_SESSION['selected_table'])) {
    header('Location: table_selection copy.php');
    exit;
}

$selected_table_id = $_SESSION['selected_table'];

// 1. Handle scenario selection for this table if not set
if (!isset($_SESSION['table_scenarios'][$selected_table_id])) {
    // Fetch members for dropdown
    $members = [];
    $result = $conn->query("SELECT user_id, name, membership_number FROM users ORDER BY name");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $members[] = $row;
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['select_scenario'])) {
        $_SESSION['table_scenarios'][$selected_table_id] = [
            'scenario' => $_POST['scenario'],
            'user_id' => isset($_POST['user_id']) && $_POST['user_id'] !== "" ? intval($_POST['user_id']) : null
        ];
        header("Location: cashierdashboard.php");
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Select Member Scenario</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="flex items-center justify-center min-h-screen bg-blue-50">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
            <h1 class="text-2xl font-bold text-blue-800 mb-6 text-center">Select Member Scenario for Table</h1>
            <form method="POST" class="flex flex-col gap-4">
                <label class="block text-gray-700 text-sm font-bold">Select Scenario</label>
                <select id="scenario-select" name="scenario" required
                    class="px-4 py-2 border border-blue-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">-- Select Scenario --</option>
                    <option value="member">Member</option>
                    <option value="guest">Guest</option>
                    <option value="guest_on_behalf">Guest on behalf of Member</option>
                </select>
                <div id="member-select-container" class="hidden">
                    <label class="block text-gray-700 text-sm font-bold mb-1">Search Member</label>
                    <input type="text" id="member-search" placeholder="Type member name or ID..." class="w-full px-4 py-2 border border-blue-300 rounded mb-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <label class="block text-gray-700 text-sm font-bold mb-1">Select Member</label>
                    <select name="user_id" id="user_id"
                        class="w-full px-4 py-2 border border-blue-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">-- Select Member --</option>
                        <?php foreach ($members as $member): ?>
                            <option value="<?= $member['user_id'] ?>" data-member="<?= strtolower($member['name']) ?> <?= strtolower($member['membership_number']) ?> <?= $member['user_id'] ?>">
                                <?= htmlspecialchars($member['name']) ?> (<?= htmlspecialchars($member['membership_number']) ?>) [ID: <?= $member['user_id'] ?>]
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="select_scenario"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">Continue</button>
            </form>
        </div>
        <script>
        document.getElementById('scenario-select').addEventListener('change', function() {
            const scenario = this.value;
            const memberContainer = document.getElementById('member-select-container');
            if (scenario === 'member' || scenario === 'guest_on_behalf') {
                memberContainer.classList.remove('hidden');
            } else {
                memberContainer.classList.add('hidden');
                document.getElementById('user_id').value = '';
            }
        });

        // Member dropdown search filter by name or ID
        document.addEventListener('DOMContentLoaded', function() {
            var searchInput = document.getElementById('member-search');
            var memberSelect = document.getElementById('user_id');
            if (searchInput && memberSelect) {
                searchInput.addEventListener('keyup', function() {
                    var filter = this.value.toLowerCase();
                    for (var i = 0; i < memberSelect.options.length; i++) {
                        var option = memberSelect.options[i];
                        if (i === 0) continue; // skip placeholder
                        var data = option.getAttribute('data-member');
                        if (data && data.indexOf(filter) !== -1) {
                            option.style.display = '';
                        } else {
                            option.style.display = 'none';
                        }
                    }
                });
            }
        });
        </script>
    </body>
    </html>
    <?php
    exit;
}

// 2. Fetch scenario for this table
$scenario = $_SESSION['table_scenarios'][$selected_table_id];

// Fetch table name
$table_name = '';
$stmt = $conn->prepare("SELECT table_name FROM tables WHERE table_id = ?");
$stmt->bind_param("i", $selected_table_id);
$stmt->execute();
$stmt->bind_result($table_name);
$stmt->fetch();
$stmt->close();

// Prepare scenario info for banner
$scenario_label = ucfirst(str_replace('_', ' ', $scenario['scenario']));
$member_name = "";
if (!empty($scenario['user_id'])) {
    $stmt = $conn->prepare("SELECT name, membership_number FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $scenario['user_id']);
    $stmt->execute();
    $stmt->bind_result($name, $membership_number);
    if ($stmt->fetch()) {
        $member_name = htmlspecialchars($name) . " (" . htmlspecialchars($membership_number) . ")";
    }
    $stmt->close();
}

// Handle manual close table button (this is where scenario is cleared)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['close_table'])) {
    $order_row = $conn->query("SELECT order_id FROM orders WHERE table_id = $selected_table_id AND status = 'open' LIMIT 1")->fetch_assoc();
    if ($order_row) {
        $order_id = $order_row['order_id'];
        $conn->query("UPDATE orders SET status='closed' WHERE order_id = $order_id");
        unset($_SESSION['order_id']);
        $_SESSION['cart'] = array();
        // Remove scenario for this table so next order will prompt again
        unset($_SESSION['table_scenarios'][$selected_table_id]);
        header("Location: table_selection copy.php");
        exit;
    } else {
        $error = "No open order found for this table.";
    }
}

// Fetch available stocks
$stocks = [];
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

// Track or create open order for this table
$order_id = null;
$order_check = $conn->query("SELECT order_id FROM orders WHERE table_id = $selected_table_id AND status = 'open'")->fetch_assoc();
if ($order_check) {
    $order_id = $order_check['order_id'];
} else {
    $conn->query("INSERT INTO orders (table_id) VALUES ($selected_table_id)");
    $order_id = $conn->insert_id;
}
$_SESSION['order_id'] = $order_id;

// Add to cart (order_items)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $stock_id = $_POST['stock_id'];
    $quantity = intval($_POST['quantity']);
    $stock = get_stock_by_id($stocks, $stock_id);
    if ($stock) {
        if ($stock['quantity'] >= $quantity) {
            // Check if already in order_items
            $stmt = $conn->prepare("SELECT item_id, quantity FROM order_items WHERE order_id = ? AND product_name = ?");
            $stmt->bind_param("is", $order_id, $stock['product_name']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $new_qty = $row['quantity'] + $quantity;
                $update = $conn->prepare("UPDATE order_items SET quantity = ? WHERE item_id = ?");
                $update->bind_param("ii", $new_qty, $row['item_id']);
                $update->execute();
                $update->close();
            } else {
                $insert = $conn->prepare("INSERT INTO order_items (order_id, product_name, quantity, price) VALUES (?, ?, ?, ?)");
                $insert->bind_param("isid", $order_id, $stock['product_name'], $quantity, $stock['price']);
                $insert->execute();
                $insert->close();
            }
            $stmt->close();
        } else {
            $error = "Not enough stock available.";
        }
    } else {
        $error = "Stock not available.";
    }
}

// Remove from cart (order_items)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_cart'])) {
    $item_id = intval($_POST['item_id']);
    $conn->query("DELETE FROM order_items WHERE item_id = $item_id AND order_id = $order_id");
}

// Fetch cart/order items for display
$order_items = [];
$res = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
while ($row = $res->fetch_assoc()) {
    $order_items[] = $row;
}

// Generate bill: close order and create bill
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_bill'])) {
    // Always fetch the current open order for the table
    $order_row = $conn->query("SELECT order_id FROM orders WHERE table_id = $selected_table_id AND status = 'open' LIMIT 1")->fetch_assoc();
    if ($order_row) {
        $order_id = $order_row['order_id'];
        $order_items = [];
        $res = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
        while ($row = $res->fetch_assoc()) {
            $order_items[] = $row;
        }

        $bill_total = 0;
        foreach ($order_items as $item) {
            $bill_total += $item['price'] * $item['quantity'];
        }
        $bill_number = uniqid("BILL");
        $cashier = isset($_SESSION['login_user']) ? $_SESSION['login_user'] : "cashier";

        // Get user_id and is_guest from scenario for this table
        $user_id = !empty($scenario['user_id']) ? $scenario['user_id'] : null;
        $is_guest = ($scenario['scenario'] === 'guest' || $scenario['scenario'] === 'guest_on_behalf') ? 1 : 0;
        $scenario_label = ucfirst(str_replace('_', ' ', $scenario['scenario']));
        $member_name = "";
        if (!empty($scenario['user_id'])) {
            $stmt = $conn->prepare("SELECT name, membership_number FROM users WHERE user_id = ?");
            $stmt->bind_param("i", $scenario['user_id']);
            $stmt->execute();
            $stmt->bind_result($name, $membership_number);
            if ($stmt->fetch()) {
                $member_name = htmlspecialchars($name) . " (" . htmlspecialchars($membership_number) . ")";
            }
            $stmt->close();
        }

        // Insert bill with user_id and is_guest
        $stmt = $conn->prepare("INSERT INTO bills (bill_number, cashier, total_amount, bill_date, table_id, order_id, user_id, is_guest) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?)");
        $stmt->bind_param("ssdiiii", $bill_number, $cashier, $bill_total, $selected_table_id, $order_id, $user_id, $is_guest);
        $stmt->execute();
        $bill_id = $stmt->insert_id;
        $stmt->close();

        // Copy order_items to bill_items and update stock quantity
        foreach ($order_items as $item) {
            $stmt = $conn->prepare("INSERT INTO bill_items (bill_id, product_name, quantity, price, total) VALUES (?, ?, ?, ?, ?)");
            $total = $item['price'] * $item['quantity'];
            $stmt->bind_param("isidd", $bill_id, $item['product_name'], $item['quantity'], $item['price'], $total);
            $stmt->execute();
            $stmt->close();

            // Update stock quantity
            $stmt = $conn->prepare("UPDATE stock SET quantity = quantity - ? WHERE product_name = ?");
            $stmt->bind_param("is", $item['quantity'], $item['product_name']);
            $stmt->execute();
            $stmt->close();
        }

        // Mark order as closed
        $conn->query("UPDATE orders SET status='closed' WHERE order_id = $order_id");

        // Clear order session and cart
        unset($_SESSION['order_id']);
        $_SESSION['cart'] = array();

        // DO NOT unset the scenario here!

        // Store bill info for preview
        $_SESSION['last_bill'] = [
            'bill_number' => $bill_number,
            'cashier' => $cashier,
            'bill_items' => $order_items,
            'bill_total' => $bill_total,
            'date' => date('Y-m-d H:i:s'),
            'table_name' => $table_name,
            'scenario_label' => $scenario_label,
            'member_name' => $member_name
        ];
        header("Location: cashierdashboard.php?preview=1");
        exit;
    } else {
        $error = "No open order found for this table.";
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
    <body class="bg-gradient-to-tr from-blue-100 to-blue-300 flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg" id="bill-preview">
            <h2 class="text-3xl font-bold mb-4 text-center text-blue-700">Bill Preview</h2>
            <div class="mb-2 text-blue-900">Bill Number: <b><?= htmlspecialchars($bill['bill_number']) ?></b></div>
            <div class="mb-2 text-blue-900">Table: <b><?= htmlspecialchars($bill['table_name']) ?></b></div>
            <div class="mb-2 text-blue-900">Cashier: <b><?= htmlspecialchars($bill['cashier']) ?></b></div>
            <div class="mb-2 text-blue-900">Date: <b><?= htmlspecialchars($bill['date']) ?></b></div>
            <div class="mb-2 text-blue-900">
                Scenario: <b><?= htmlspecialchars($bill['scenario_label']) ?></b>
                <?php if (!empty($bill['member_name'])): ?>
                    &nbsp;|&nbsp; Member: <b><?= $bill['member_name'] ?></b>
                <?php endif; ?>
            </div>
            <table class="w-full mb-4 border rounded-lg overflow-hidden">
                <thead>
                    <tr>
                        <th class="bg-blue-600 text-white p-2">Product</th>
                        <th class="bg-blue-600 text-white p-2">Qty</th>
                        <th class="bg-blue-600 text-white p-2">Price</th>
                        <th class="bg-blue-600 text-white p-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bill['bill_items'] as $item): ?>
                        <tr class="hover:bg-blue-50">
                            <td class="border-b p-2"><?= htmlspecialchars($item['product_name']) ?></td>
                            <td class="border-b p-2"><?= $item['quantity'] ?></td>
                            <td class="border-b p-2">₹<?= number_format($item['price'], 2) ?></td>
                            <td class="border-b p-2">₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="font-bold text-right mb-4 text-blue-800 text-lg">Grand Total: ₹<?= number_format($bill['bill_total'], 2) ?></div>
            <div class="flex justify-between">
                <a href="cashierdashboard.php" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-600">Back to Dashboard</a>
                <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
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
    <meta charset="UTF-8" />
    <title>Cashier Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-tr from-blue-100 to-blue-300 min-h-screen">
    <div class="container mx-auto py-8 px-4">
        <div class="header bg-blue-600 text-white rounded-lg p-6 mb-8 shadow-lg flex justify-between items-center">
            <a href="table_selection copy.php" class=" hover:underline">Back</a>
            <h1 class="text-3xl font-bold">Cashier Dashboard</h1>
            <div class="text-xl font-semibold">Table: <span class="font-bold"><?= htmlspecialchars($table_name) ?></span></div>
        </div>
        <!-- Scenario Info Banner -->
        <div class="bg-blue-50 text-blue-900 p-4 rounded mb-6 text-lg font-semibold flex items-center gap-3 shadow">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m13-7a4 4 0 11-8 0 4 4 0 018 0zM3 7a4 4 0 108 0 4 4 0 00-8 0z" /></svg>
            <span>
                Scenario: <b><?= $scenario_label ?></b>
                <?php if ($member_name): ?>
                    &nbsp;|&nbsp; Member: <b><?= $member_name ?></b>
                <?php endif; ?>
            </span>
        </div>
        <?php if (isset($error)): ?>
            <div class="bg-red-200 text-red-800 p-3 mb-4 rounded"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Stock List -->
            <div class="md:col-span-2 bg-white rounded-lg p-6 shadow-lg overflow-x-auto">
                <h2 class="text-2xl font-semibold mb-4 text-blue-700">Available Stocks</h2>
                <input
                    type="text"
                    id="stockSearch"
                    placeholder="Search for products..."
                    class="w-full px-4 py-2 mb-4 border border-blue-200 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                    onkeyup="filterStockTable()"
                />
                <table id="stockTable" class="min-w-full border-collapse rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-blue-600 text-white">
                            <th class="p-3 text-left">Product Name</th>
                            <th class="p-3 text-left">Quantity</th>
                            <th class="p-3 text-left">Price (₹)</th>
                            <th class="p-3 text-left">Add to Cart</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stocks as $stock): ?>
                        <tr class="border-b hover:bg-blue-50">
                            <td class="p-2"><?= htmlspecialchars($stock['product_name']) ?></td>
                            <td class="p-2"><?= $stock['quantity'] ?></td>
                            <td class="p-2">₹<?= number_format($stock['price'], 2) ?></td>
                            <td class="p-2">
                                <form method="POST" class="flex items-center gap-2">
                                    <input type="hidden" name="stock_id" value="<?= $stock['stock_id'] ?>">
                                    <input type="number" name="quantity" value="1" min="1" max="<?= $stock['quantity'] ?>" class="w-16 p-1 border rounded">
                                    <button type="submit" name="add_to_cart" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Add</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="flex justify-end mt-6 gap-4">
                    <form method="POST">
                        <button type="submit" name="close_table"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">
                            Close Table
                        </button>
                    </form>
                </div>
            </div>
            <!-- Cart and Generate Bill -->
            <div class="bg-white rounded-lg p-3 shadow-lg flex flex-col ">
                <h2 class="text-2xl font-semibold mb-4 text-blue-700">Cart</h2>
                <?php if (!empty($order_items)): ?>
                    <form method="POST">
                        <table class="w-full text-left border-collapse mb-4">
                            <thead>
                                <tr class="bg-blue-600 text-white">
                                    <th class="p-2">Product</th>
                                    <th class="p-2">Price</th>
                                    <th class="p-2">Quantity</th>
                                    <th class="p-2">Total</th>
                                    <th class="p-2">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $grand_total = 0;
                                foreach ($order_items as $item):
                                    $total = $item['price'] * $item['quantity'];
                                    $grand_total += $total;
                                ?>
                                <tr class="border-b hover:bg-blue-50">
                                    <td class="p-2"><?= htmlspecialchars($item['product_name']) ?></td>
                                    <td class="p-2">₹<?= number_format($item['price'], 2) ?></td>
                                    <td class="p-2"><?= $item['quantity'] ?></td>
                                    <td class="p-2">₹<?= number_format($total, 2) ?></td>
                                    <td class="p-2">
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
                                            <button type="submit" name="remove_from_cart" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" onclick="return confirm('Remove this item?');">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="font-bold mb-4 text-blue-800 text-lg">Grand Total: ₹<?= number_format($grand_total, 2) ?></div>
                        <button type="submit" name="generate_bill" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Generate Bill</button>
                    </form>
                <?php else: ?>
                    <p class="text-blue-700">Your cart is empty.</p>
                <?php endif; ?>
            </div>
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
