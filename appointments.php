<?php
require_once 'config/config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$db = new Database();
$conn = $db->getConnection();

// Handle appointment deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    redirect('appointments.php');
}

// Get filter parameters
$date_filter = isset($_GET['date']) ? sanitize($_GET['date']) : '';
$status_filter = isset($_GET['status']) ? sanitize($_GET['status']) : '';
$client_filter = isset($_GET['client']) ? sanitize($_GET['client']) : '';

// Build query
$sql = "SELECT a.*, CONCAT(c.first_name, ' ', c.last_name) as client_name
        FROM appointments a
        JOIN clients c ON a.client_id = c.id
        WHERE 1=1";
$params = [];

if ($date_filter) {
    $sql .= " AND a.appointment_date = ?";
    $params[] = $date_filter;
}

if ($status_filter) {
    $sql .= " AND a.status = ?";
    $params[] = $status_filter;
}

if ($client_filter) {
    $sql .= " AND (c.first_name LIKE ? OR c.last_name LIKE ?)";
    $search_param = "%$client_filter%";
    $params[] = $search_param;
    $params[] = $search_param;
}

$sql .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$appointments = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<div class="page-header">
    <h1>Appointments</h1>
    <a href="appointment_add.php" class="btn btn-primary">Schedule Appointment</a>
</div>

<div class="filters">
    <form method="GET" action="" class="filter-form">
        <input type="date" name="date" value="<?php echo htmlspecialchars($date_filter); ?>">
        <select name="status">
            <option value="">All Status</option>
            <option value="Scheduled" <?php echo $status_filter == 'Scheduled' ? 'selected' : ''; ?>>Scheduled</option>
            <option value="Completed" <?php echo $status_filter == 'Completed' ? 'selected' : ''; ?>>Completed</option>
            <option value="Cancelled" <?php echo $status_filter == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
            <option value="No-Show" <?php echo $status_filter == 'No-Show' ? 'selected' : ''; ?>>No-Show</option>
        </select>
        <input type="text" name="client" placeholder="Search client..." value="<?php echo htmlspecialchars($client_filter); ?>">
        <button type="submit" class="btn btn-secondary">Filter</button>
        <?php if ($date_filter || $status_filter || $client_filter): ?>
            <a href="appointments.php" class="btn btn-secondary">Clear</a>
        <?php endif; ?>
    </form>
</div>

<div class="stats-row">
    <div class="stat-card">
        <h3><?php echo count($appointments); ?></h3>
        <p>Total Appointments</p>
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Client</th>
                <th>Duration</th>
                <th>Type</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($appointments)): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No appointments found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?></td>
                        <td><?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?></td>
                        <td>
                            <a href="client_view.php?id=<?php echo $appointment['client_id']; ?>">
                                <?php echo htmlspecialchars($appointment['client_name']); ?>
                            </a>
                        </td>
                        <td><?php echo $appointment['duration_minutes']; ?> min</td>
                        <td><?php echo $appointment['session_type']; ?></td>
                        <td><span class="status status-<?php echo strtolower($appointment['status']); ?>"><?php echo $appointment['status']; ?></span></td>
                        <td class="actions">
                            <a href="appointment_edit.php?id=<?php echo $appointment['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                            <a href="appointments.php?delete=<?php echo $appointment['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>
