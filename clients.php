<?php
require_once 'config/config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$db = new Database();
$conn = $db->getConnection();

// Handle client deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM clients WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    redirect('clients.php');
}

// Get all clients with search
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? sanitize($_GET['status']) : '';

$sql = "SELECT * FROM clients WHERE 1=1";
$params = [];

if ($search) {
    $sql .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ?)";
    $search_param = "%$search%";
    $params = array_fill(0, 4, $search_param);
}

if ($status_filter) {
    $sql .= " AND status = ?";
    $params[] = $status_filter;
}

$sql .= " ORDER BY last_name, first_name";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$clients = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<div class="page-header">
    <h1>Clients</h1>
    <a href="client_add.php" class="btn btn-primary">Add New Client</a>
</div>

<div class="filters">
    <form method="GET" action="" class="filter-form">
        <input type="text" name="search" placeholder="Search clients..." value="<?php echo htmlspecialchars($search); ?>">
        <select name="status">
            <option value="">All Status</option>
            <option value="Active" <?php echo $status_filter == 'Active' ? 'selected' : ''; ?>>Active</option>
            <option value="Inactive" <?php echo $status_filter == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
            <option value="On Hold" <?php echo $status_filter == 'On Hold' ? 'selected' : ''; ?>>On Hold</option>
        </select>
        <button type="submit" class="btn btn-secondary">Filter</button>
        <?php if ($search || $status_filter): ?>
            <a href="clients.php" class="btn btn-secondary">Clear</a>
        <?php endif; ?>
    </form>
</div>

<div class="stats-row">
    <div class="stat-card">
        <h3><?php echo count($clients); ?></h3>
        <p>Total Clients</p>
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Membership</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($clients)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No clients found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($client['first_name'] . ' ' . $client['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($client['email']); ?></td>
                        <td><?php echo htmlspecialchars($client['phone'] ?: '-'); ?></td>
                        <td><span class="badge badge-<?php echo strtolower($client['membership_type']); ?>"><?php echo $client['membership_type']; ?></span></td>
                        <td><span class="status status-<?php echo strtolower(str_replace(' ', '-', $client['status'])); ?>"><?php echo $client['status']; ?></span></td>
                        <td class="actions">
                            <a href="client_view.php?id=<?php echo $client['id']; ?>" class="btn btn-sm btn-info">View</a>
                            <a href="client_edit.php?id=<?php echo $client['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                            <a href="clients.php?delete=<?php echo $client['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this client?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
