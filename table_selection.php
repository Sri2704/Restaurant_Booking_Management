<?php
session_start();
include("config.php");

// Handle Add Table
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_table'])) {
    $new_table = trim($_POST['new_table_name']);
    if ($new_table !== "") {
        $stmt = $conn->prepare("INSERT INTO tables (table_name) VALUES (?)");
        $stmt->bind_param("s", $new_table);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: table_selection copy.php");
    exit;
}

// Handle Delete Table
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_table'])) {
    $delete_id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM tables WHERE table_id=?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: table_selection copy.php");
    exit;
}

// Handle table selection (Start/View Order)
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['select_table']) || isset($_POST['view_order']))) {
    $_SESSION['selected_table'] = $_POST['table_id'];
    header('Location: cashierdashboard.php');
    exit;
}

// Fetch all tables and their status
$tables = [];
$sql = "SELECT table_id, table_name FROM tables";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Check for open order for this table
        $order_status = 'Closed';
        $status_color = 'bg-gray-200 text-gray-700';
        $open_order = $conn->query("SELECT status FROM orders WHERE table_id = {$row['table_id']} AND status = 'open' LIMIT 1");
        $is_open = ($open_order && $open_order->num_rows > 0);
        if ($is_open) {
            $order_status = 'Open';
            $status_color = 'bg-blue-100 text-blue-700';
        }
        $row['order_status'] = $order_status;
        $row['status_color'] = $status_color;
        $row['is_open'] = $is_open;
        $tables[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Table Selection</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
<style>
body { background: linear-gradient(90deg,#e3ebfa 0%,#fafdff 100%); }
.sidebar-link.active, .sidebar-link:hover {
    background: #2563eb;
    color: #fff !important;
}
.sidebar-link {
    transition: background 0.2s, color 0.2s;
}
</style>
</head>
<body>
<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-blue-900 to-blue-600 text-white flex flex-col py-8 px-4">
        <div class="mb-10">
            <div class="text-2xl font-bold mb-2">Table Panel</div>
            <div class="text-blue-200 text-sm">You are logged in as Cashier</div>
        </div>
        <nav class="flex-1">
            <ul class="space-y-1">
                <li>
                    <a href="#" class="sidebar-link block px-4 py-2 rounded <?php if(basename($_SERVER['PHP_SELF'])=='table_selection copy.php') echo 'active'; ?>">
                        View Tables
                    </a>
                </li>
                <li>
                    <a href="#" class="sidebar-link block px-4 py-2 rounded opacity-60 cursor-not-allowed">Other Option</a>
                </li>
            </ul>
        </nav>
        <form method="post" class="mt-8">
            <button type="submit" name="logout" formaction="logout.php"
                class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded font-semibold transition">
                Logout
            </button>
        </form>
    </aside>
    <!-- Main Content -->
    <main class="flex-1 flex flex-col items-center justify-start py-8 px-2 md:px-12">
        <h2 class="text-3xl font-bold text-blue-900 mb-2 w-full text-center">Welcome!</h2>
        <div class="text-blue-600 font-semibold text-center mb-8 w-full">Table Selection Panel</div>
        <!-- Add Table Form -->
        <div class="w-full max-w-lg mb-8">
            <form method="POST" class="bg-white rounded-lg shadow p-4 flex flex-col sm:flex-row gap-2 items-center">
                <input type="text" name="new_table_name" placeholder="Add new table..." required
                    class="flex-1 px-4 py-2 rounded border border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <button type="submit" name="add_table"
                    class="btn-blue px-6 py-2 rounded font-semibold text-lg shadow">Add Table</button>
            </form>
        </div>
        <!-- Table List Card -->
        <div class="w-full max-w-3xl bg-white rounded-lg shadow p-6">
            <h3 class="text-2xl font-bold text-blue-800 mb-6 text-center">Current Table List</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-blue-100 text-blue-900">
                            <th class="px-4 py-2 text-left">Table ID</th>
                            <th class="px-4 py-2 text-left">Table Name</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Action</th>
                            <th class="px-4 py-2 text-left">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tables as $table): ?>
                        <tr class="even:bg-blue-50">
                            <td class="px-4 py-2"><?= htmlspecialchars($table['table_id']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($table['table_name']) ?></td>
                            <td class="px-4 py-2">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $table['status_color'] ?>">
                                    <?= $table['order_status'] ?>
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <form method="POST" class="inline">
                                    <input type="hidden" name="table_id" value="<?= $table['table_id'] ?>">
                                    <?php if ($table['order_status'] == 'Open'): ?>
                                        <button type="submit" name="view_order"
                                            class="bg-blue-100 text-blue-700 border border-blue-400 px-4 py-1 rounded font-semibold hover:bg-blue-200 transition">
                                            View Order
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" name="select_table"
                                            class="bg-blue-600 text-white px-4 py-1 rounded font-semibold hover:bg-blue-700 transition">
                                            Start Order
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </td>
                            <td class="px-4 py-2">
                                <form method="POST" class="inline" onsubmit="return confirm('Delete this table?');">
                                    <input type="hidden" name="delete_id" value="<?= $table['table_id'] ?>">
                                    <button type="submit" name="delete_table"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded font-semibold">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($tables)): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">No tables found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>
