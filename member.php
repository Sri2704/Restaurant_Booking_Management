<?php
session_start();
include("config.php");

// Handle Add Member
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_member'])) {
    $name = trim($_POST['name']);
    $membership_number = trim($_POST['membership_number']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $membership_type = trim($_POST['membership_type']);
    $notes = trim($_POST['notes']);

    if ($name && $membership_number && $email) {
        $stmt = $conn->prepare("INSERT INTO users (name, membership_number, email, phone, address, membership_type, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $membership_number, $email, $phone, $address, $membership_type, $notes);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF'] . "?section=add_member");
        exit;
    }
}

// Handle Delete Member (SAFE: deletes all related order_items, orders, bills first)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_member']) && isset($_POST['delete_user_id'])) {
    $delete_user_id = intval($_POST['delete_user_id']);

    // 1. Find all order_ids for this user
    $order_ids = [];
    $result = $conn->query("SELECT order_id FROM orders WHERE user_id = $delete_user_id");
    while ($row = $result->fetch_assoc()) {
        $order_ids[] = $row['order_id'];
    }

    // 2. Delete order_items for these orders
    if (!empty($order_ids)) {
        $order_ids_str = implode(',', $order_ids);
        $conn->query("DELETE FROM order_items WHERE order_id IN ($order_ids_str)");
    }

    // 3. Delete orders for this user
    $conn->query("DELETE FROM orders WHERE user_id = $delete_user_id");

    // 4. Delete bills for this user (if needed)
    $conn->query("DELETE FROM bills WHERE user_id = $delete_user_id");

    // 5. Finally, delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $delete_user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF'] . "?section=select_members");
    exit;
}

// Fetch all members
$members = [];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = $search ? "WHERE name LIKE '%$search%' OR membership_number LIKE '%$search%'" : "";
$result = $conn->query("SELECT * FROM users $where ORDER BY created_at DESC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
}

// Handle section switching
$section = isset($_GET['section']) ? $_GET['section'] : 'select_members';

// Revenue report
$revenue = [];
$revenue_search = isset($_GET['revenue_search']) ? trim($_GET['revenue_search']) : '';
$revenue_where = '';
if ($revenue_search) {
    $like = "%".$conn->real_escape_string($revenue_search)."%";
    $revenue_where = "WHERE u.name LIKE '$like' OR u.membership_number LIKE '$like'";
}
if ($section === 'generate_revenue') {
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Premium Member Dashboard</title>
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
            .sidebar-logout { position: static; margin-top: 2rem; }
        }
        .member-card {
            border: 2px solid #e0e7ef;
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        .member-card:hover {
            border-color: #2563eb;
            box-shadow: 0 6px 24px 0 rgba(37,99,235,0.10);
        }
        .btn-blue { background: #2563eb; color: #fff; }
        .btn-blue:hover { background: #1d4ed8; }
        .btn-delete { background: #e11d48; color: #fff; }
        .btn-delete:hover { background: #be123c; }
    </style>
</head>
<body>
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="sidebar w-64 flex flex-col relative py-10 px-6">
        <div class="sidebar-title text-center mb-10">
            <svg class="mx-auto mb-3 w-10 h-10 text-blue-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m13-7a4 4 0 11-8 0 4 4 0 018 0zM3 7a4 4 0 108 0 4 4 0 00-8 0z" /></svg>
            Member Dashboard
        </div>
        <nav class="mb-10">
            <a href="?section=select_members" class="sidebar-link<?= $section == 'select_members' ? ' active' : '' ?>">All Members</a>
            <a href="?section=add_member" class="sidebar-link<?= $section == 'add_member' ? ' active' : '' ?>">Add Exclusive Members</a>
            <a href="?section=generate_revenue" class="sidebar-link<?= $section == 'generate_revenue' ? ' active' : '' ?>"> Revenue</a>
        </nav>
    </aside>
    <!-- Main Content -->
    <main class="flex-1 py-10 px-4 md:px-12">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="manager_dashboard.php" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold shadow transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                Back to Manager Dashboard
            </a>
        </div>
        <!-- Select Members Section -->
        <?php if ($section == 'select_members'): ?>
        <!-- All Members List -->
        <div class="bg-white rounded-lg shadow p-6 max-w-5xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-4">
                <h2 class="text-xl font-bold text-blue-800">All Members</h2>
                <form method="GET" class="flex gap-2">
                    <input type="hidden" name="section" value="select_members">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by name or membership number" class="px-4 py-2 border border-blue-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <button type="submit" class="btn-blue px-4 py-2 rounded font-semibold">Search</button>
                </form>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($members as $member): ?>
                    <div class="member-card bg-white rounded-xl p-5 flex flex-col shadow group">
                        <div class="flex justify-between items-start">
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
                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this member?');" style="margin-left:10px;">
                                <input type="hidden" name="delete_user_id" value="<?= $member['user_id'] ?>">
                                <button type="submit" name="delete_member" class="btn-delete px-3 py-1 rounded text-sm font-semibold shadow">Delete</button>
                            </form>
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
        <?php endif; ?>

        <!-- Add Member Section -->
        <?php if ($section == 'add_member'): ?>
        <div class="bg-white rounded-lg shadow p-6 mb-10 max-w-2xl mx-auto">
            <h2 class="text-xl font-bold text-blue-800 mb-4">Add New Member</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-1">Full Name *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border border-blue-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-1">Membership Number *</label>
                    <input type="text" name="membership_number" required class="w-full px-4 py-2 border border-blue-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-1">Email *</label>
                    <input type="email" name="email" required class="w-full px-4 py-2 border border-blue-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-1">Phone</label>
                    <input type="text" name="phone" class="w-full px-4 py-2 border border-blue-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-1">Address</label>
                    <input type="text" name="address" class="w-full px-4 py-2 border border-blue-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-1">Membership Type</label>
                    <input type="text" name="membership_type" class="w-full px-4 py-2 border border-blue-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-1">Notes</label>
                    <textarea name="notes" class="w-full px-4 py-2 border border-blue-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>
                <div class="md:col-span-2 text-right">
                    <button type="submit" name="add_member" class="btn-blue px-6 py-2 rounded font-semibold">Add Member</button>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- Generate Revenue Section -->
        <?php if ($section == 'generate_revenue'): ?>
        <div class="bg-white rounded-lg shadow p-6 mb-10 max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold text-blue-800 mb-4">Revenue by Member</h2>
            <form method="GET" class="mb-4 flex gap-2">
                <input type="hidden" name="section" value="generate_revenue">
                <input type="text" name="revenue_search" value="<?= htmlspecialchars($revenue_search) ?>" placeholder="Search by name or membership number" class="px-4 py-2 border border-blue-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                <button type="submit" class="btn-blue px-4 py-2 rounded font-semibold">Search</button>
            </form>
            <table class="w-full border-collapse rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="p-3 text-left">Member Name</th>
                        <th class="p-3 text-left">Membership ID</th>
                        <th class="p-3 text-left">Bills</th>
                        <th class="p-3 text-left">Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($revenue as $row): ?>
                        <tr class="border-b hover:bg-blue-50">
                            <td class="p-2">
                                <a href="?section=generate_revenue&member_id=<?= $row['user_id'] ?>"
                                   class="text-blue-700 font-semibold hover:underline">
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
        </div>
        <?php
        // Show bills for selected member
        if (isset($_GET['member_id']) && is_numeric($_GET['member_id'])):
            $member_id = intval($_GET['member_id']);
            // Fetch member info
            $stmt = $conn->prepare("SELECT name, membership_number FROM users WHERE user_id = ?");
            $stmt->bind_param("i", $member_id);
            $stmt->execute();
            $stmt->bind_result($mem_name, $mem_number);
            $stmt->fetch();
            $stmt->close();

            // Fetch bills for this member
            $bills = [];
            $grand_total = 0;
            $bill_result = $conn->query("SELECT bill_number, bill_date, total_amount FROM bills WHERE user_id = $member_id ORDER BY bill_date DESC");
            if ($bill_result && $bill_result->num_rows > 0) {
                while ($row = $bill_result->fetch_assoc()) {
                    $bills[] = $row;
                    $grand_total += $row['total_amount'];
                }
            }
        ?>
        <div class="bg-white rounded-lg shadow p-6 mt-10 max-w-3xl mx-auto">
            <h3 class="text-xl font-bold text-blue-800 mb-4">
                Bills for <?= htmlspecialchars($mem_name) ?> (<?= htmlspecialchars($mem_number) ?>)
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
        <?php endif; ?>
    </main>
</div>
</body>
</html>
