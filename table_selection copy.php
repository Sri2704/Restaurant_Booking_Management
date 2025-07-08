<?php
session_start();
include("config.php");

// Section switching
$section = isset($_GET['section']) ? $_GET['section'] : 'tables';

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

// Handle table selection (Start Order or View Order)
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['select_table']) || isset($_POST['view_order']))) {
    $_SESSION['selected_table'] = $_POST['table_id'];
    header('Location: cashierdashboard.php');
    exit;
}

// All Members
$members = [];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = $search ? "WHERE name LIKE '%$search%' OR membership_number LIKE '%$search%'" : "";
if ($section === 'all_members') {
    $result = $conn->query("SELECT * FROM users $where ORDER BY created_at DESC");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $members[] = $row;
        }
    }
}

// Revenue report
$revenue = [];
$revenue_search = isset($_GET['revenue_search']) ? trim($_GET['revenue_search']) : '';
$revenue_where = '';
if ($revenue_search) {
    $like = "%".$conn->real_escape_string($revenue_search)."%";
    $revenue_where = "WHERE u.name LIKE '$like' OR u.membership_number LIKE '$like'";
}
if ($section === 'revenue') {
    $rev_result = $conn->query("
        SELECT u.user_id, u.name, u.membership_number, SUM(b.total_amount) AS total_revenue, COUNT(b.bill_id) AS bill_count
        FROM users u
        LEFT JOIN bills b ON b.user_id = u.user_id
        $revenue_where
        GROUP BY u.user_id
        ORDER BY total_revenue DESC
    ");
    if ($rev_result && $rev_result->num_rows > 0) {
        while ($row = $rev_result->fetch_assoc()) {
            $revenue[] = $row;
        }
    }
    // Get bill history for a selected member
    $bills = [];
    $bill_member_name = '';
    $bill_member_number = '';
    $grand_total = 0;
    if (isset($_GET['member_id']) && is_numeric($_GET['member_id'])) {
        $member_id = intval($_GET['member_id']);
        $stmt = $conn->prepare("SELECT name, membership_number FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $stmt->bind_result($bill_member_name, $bill_member_number);
        $stmt->fetch();
        $stmt->close();

        $bill_result = $conn->query("SELECT bill_number, bill_date, total_amount FROM bills WHERE user_id = $member_id ORDER BY bill_date DESC");
        if ($bill_result && $bill_result->num_rows > 0) {
            while ($row = $bill_result->fetch_assoc()) {
                $bills[] = $row;
                $grand_total += $row['total_amount'];
            }
        }
    }
}

// Fetch all tables and their status (only if not in all_members or revenue)
$tables = [];
if ($section === 'tables') {
    $sql = "SELECT table_id, table_name FROM tables";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $status_label = 'Unoccupied';
            $status_color = 'bg-gray-200 text-gray-700';
            $order = $conn->query("SELECT status FROM orders WHERE table_id = {$row['table_id']} AND status = 'open' LIMIT 1");
            $is_occupied = ($order && $order->num_rows > 0);
            if ($is_occupied) {
                $status_label = 'Occupied';
                $status_color = 'bg-blue-100 text-blue-700';
            }
            $row['status_label'] = $status_label;
            $row['status_color'] = $status_color;
            $row['is_occupied'] = $is_occupied;
            $tables[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Select Table</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
<style>
body { background: #f5f7ff; }
.sidebar {
    min-height: 100vh;
    background: linear-gradient(to bottom, #2563eb 0%, #1e3a8a 100%);
}
.sidebar-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 2rem;
    letter-spacing: 0.02em;
}
.sidebar-link {
    color: #c7d2fe;
    padding: 0.75rem 1.25rem;
    border-radius: 0.5rem;
    display: block;
    font-weight: 500;
    margin-bottom: 0.5rem;
    transition: background 0.2s, color 0.2s;
}
.sidebar-link.active, .sidebar-link:hover {
    background: #1d4ed8;
    color: #fff !important;
}
.sidebar-logout {
    position: absolute;
    bottom: 2rem;
    left: 0;
    width: 100%;
}
@media (max-width: 768px) {
    .sidebar-logout {
        position: static;
        margin-top: 2rem;
    }
}
.table-card {
    border: 2px solid #e0e7ef;
    transition: box-shadow 0.2s, border-color 0.2s;
}
.table-card:hover {
    border-color: #2563eb;
    box-shadow: 0 6px 24px 0 rgba(37,99,235,0.10);
}
.btn-blue {
    background: #2563eb;
    color: #fff;
}
.btn-blue:hover {
    background: #1d4ed8;
}
.btn-view {
    background: #e0e7ff;
    color: #2563eb;
    border: 1px solid #2563eb;
}
.btn-view:hover {
    background: #2563eb;
    color: #fff;
}
.btn-delete {
    background: #e11d48;
    color: #fff;
}
.btn-delete:hover {
    background: #be123c;
}
.member-card {
    border: 2px solid #e0e7ef;
    transition: box-shadow 0.2s, border-color 0.2s;
}
.member-card:hover {
    border-color: #2563eb;
    box-shadow: 0 6px 24px 0 rgba(37,99,235,0.10);
}
</style>
</head>
<body>
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="sidebar w-64 flex flex-col relative py-10 px-6">
        <div class="sidebar-title text-center mb-10">
            <svg class="mx-auto mb-3 w-10 h-10 text-blue-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m13-7a4 4 0 11-8 0 4 4 0 018 0zM3 7a4 4 0 108 0 4 4 0 00-8 0z" /></svg>
            Restaurant Orders
        </div>
        <nav class="mb-10">
            <a href="table_selection copy.php" class="sidebar-link<?= $section == 'tables' ? ' active' : '' ?>">Tables</a>
            <a href="?section=all_members" class="sidebar-link<?= $section == 'all_members' ? ' active' : '' ?>">All Members</a>
            <a href="?section=revenue" class="sidebar-link<?= $section == 'revenue' ? ' active' : '' ?>">Revenue</a>
        </nav>
        <div class="flex-1"></div>
        <form action="logout.php" method="post" class="sidebar-logout">
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded font-semibold shadow transition">
                Logout
            </button>
        </form>
    </aside>
    <!-- Main Content -->
    <main class="flex-1 py-10 px-4 md:px-12">
        <?php if ($section === 'tables'): ?>
            <!-- Add Table Form -->
            <div class="max-w-xl mx-auto mb-8">
                <form method="POST" class="bg-white rounded-lg shadow p-4 flex flex-col sm:flex-row gap-2 items-center">
                    <input type="text" name="new_table_name" placeholder="Add new table..." required
                        class="flex-1 px-4 py-2 rounded border border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <button type="submit" name="add_table"
                        class="btn-blue px-6 py-2 rounded font-semibold text-lg shadow">Add Table</button>
                </form>
            </div>
            <!-- Table Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <?php foreach ($tables as $table): ?>
                    <div class="table-card bg-white rounded-xl p-6 flex flex-col justify-between shadow group">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xl font-semibold flex items-center text-blue-900">
                                    <svg class="w-6 h-6 mr-2 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m13-7a4 4 0 11-8 0 4 4 0 018 0zM3 7a4 4 0 108 0 4 4 0 00-8 0z" /></svg>
                                    <?= htmlspecialchars($table['table_name']) ?>
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $table['status_color'] ?>">
                                    <?= $table['status_label'] ?>
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 mt-4">
                            <?php if ($table['is_occupied']): ?>
                                <form method="POST">
                                    <input type="hidden" name="table_id" value="<?= $table['table_id'] ?>">
                                    <button type="submit" name="view_order"
                                        class="w-full btn-view py-2 rounded-lg text-lg font-semibold flex items-center justify-center gap-2 shadow-sm transition">
                                        View Order
                                    </button>
                                </form>
                            <?php else: ?>
                                <form method="POST">
                                    <input type="hidden" name="table_id" value="<?= $table['table_id'] ?>">
                                    <button type="submit" name="select_table"
                                        class="w-full btn-blue py-2 rounded-lg text-lg font-semibold flex items-center justify-center gap-2 shadow-sm transition">
                                        <span class="text-xl">+</span> Start Order
                                    </button>
                                </form>
                            <?php endif; ?>
                            <form method="POST" onsubmit="return confirm('Delete this table?');">
                                <input type="hidden" name="delete_id" value="<?= $table['table_id'] ?>">
                                <button type="submit" name="delete_table"
                                    class="w-40 btn-delete py-2 rounded-lg text-lg font-semibold flex items-center justify-center gap-2 shadow-sm transition">
                                    <svg class="w-4 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif ($section === 'all_members'): ?>
            <!-- All Members List -->
            <div class="bg-white rounded-lg shadow p-6 max-w-5xl mx-auto">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-4">
                    <h2 class="text-xl font-bold text-blue-800">All Members</h2>
                    <form method="GET" class="flex gap-2">
                        <input type="hidden" name="section" value="all_members">
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by name or membership number" class="px-4 py-2 border border-blue-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <button type="submit" class="btn-blue px-4 py-2 rounded font-semibold">Search</button>
                    </form>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($members as $member): ?>
                        <div class="member-card bg-white rounded-xl p-5 flex flex-col shadow group">
                            <div>
                                <span class="text-lg font-semibold text-blue-900"><?= htmlspecialchars($member['name']) ?></span>
                                <div class="mb-1 text-blue-700 text-sm">Membership #: <b><?= htmlspecialchars($member['membership_number']) ?></b></div>
                                <div class="mb-1 text-gray-500 text-sm">Email: <?= htmlspecialchars($member['email']) ?></div>
                                <?php if ($member['phone']): ?>
                                    <div class="mb-1 text-gray-500 text-sm">Phone: <?= htmlspecialchars($member['phone']) ?></div>
                                <?php endif; ?>
                                <?php if ($member['address']): ?>
                                    <div class="mb-1 text-gray-500 text-sm">Address: <?= htmlspecialchars($member['address']) ?></div>
                                <?php endif; ?>
                                <?php if ($member['membership_type']): ?>
                                    <div class="mb-1 text-gray-500 text-sm">Type: <?= htmlspecialchars($member['membership_type']) ?></div>
                                <?php endif; ?>
                                <?php if ($member['notes']): ?>
                                    <div class="mb-1 text-gray-400 text-xs">Notes: <?= htmlspecialchars($member['notes']) ?></div>
                                <?php endif; ?>
                                <div class="mt-2 text-xs text-gray-400">Joined: <?= date('d M Y', strtotime($member['created_at'])) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($members)): ?>
                        <div class="col-span-full text-center py-8 text-gray-500">
                            No members found.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php elseif ($section === 'revenue'): ?>
            <!-- Revenue Report Section -->
            <div class="bg-white rounded-lg shadow p-6 max-w-4xl mx-auto">
                <h2 class="text-2xl font-bold text-blue-800 mb-4">Revenue by Member</h2>
                <form method="GET" class="mb-4 flex gap-2">
                    <input type="hidden" name="section" value="revenue">
                    <input type="text" name="revenue_search" value="<?= htmlspecialchars($revenue_search) ?>" placeholder="Search by name or membership number" class="px-4 py-2 border border-blue-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <button type="submit" class="btn-blue px-4 py-2 rounded font-semibold">Search</button>
                </form>
                <table class="w-full border-collapse rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-blue-600 text-white">
                            <th class="p-3 text-left">Member Name</th>
                            <th class="p-3 text-left">Membership #</th>
                            <th class="p-3 text-left">Bills</th>
                            <th class="p-3 text-left">Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($revenue as $row): ?>
                            <tr class="border-b hover:bg-blue-50">
                                <td class="p-2">
                                    <a href="?section=revenue&member_id=<?= $row['user_id'] ?>" class="text-blue-700 font-semibold hover:underline">
                                        <?= htmlspecialchars($row['name']) ?>
                                    </a>
                                </td>
                                <td class="p-2"><?= htmlspecialchars($row['membership_number']) ?></td>
                                <td class="p-2"><?= $row['bill_count'] ?></td>
                                <td class="p-2 font-bold text-blue-700">₹<?= number_format($row['total_revenue'] ?? 0, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($revenue)): ?>
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">No revenue data available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if (isset($_GET['member_id']) && $bill_member_name): ?>
                <div class="bg-blue-50 rounded-lg shadow p-6 mt-8">
                    <h3 class="text-xl font-bold text-blue-800 mb-4">
                        Bill History for <?= htmlspecialchars($bill_member_name) ?> (<?= htmlspecialchars($bill_member_number) ?>)
                    </h3>
                    <?php if (!empty($bills)): ?>
                        <table class="w-full border-collapse rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-blue-100 text-blue-900">
                                    <th class="p-3 text-left">Bill Number</th>
                                    <th class="p-3 text-left">Bill Date</th>
                                    <th class="p-3 text-left">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bills as $bill): ?>
                                    <tr class="border-b hover:bg-blue-50">
                                        <td class="p-2"><?= htmlspecialchars($bill['bill_number']) ?></td>
                                        <td class="p-2"><?= htmlspecialchars($bill['bill_date']) ?></td>
                                        <td class="p-2 font-bold text-blue-700">₹<?= number_format($bill['total_amount'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="bg-blue-50 font-bold">
                                    <td class="p-2" colspan="2" style="text-align:right;">Grand Total:</td>
                                    <td class="p-2 text-blue-900">₹<?= number_format($grand_total, 2) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="text-gray-500">No bills found for this member.</div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>
</div>
</body>
</html>
