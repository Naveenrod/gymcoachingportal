<?php
require_once 'config/config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$db = new Database();
$conn = $db->getConnection();

// Get statistics
$total_clients = $conn->query("SELECT COUNT(*) FROM clients")->fetchColumn();
$active_clients = $conn->query("SELECT COUNT(*) FROM clients WHERE status = 'Active'")->fetchColumn();
$total_appointments = $conn->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
$upcoming_appointments = $conn->query("SELECT COUNT(*) FROM appointments WHERE appointment_date >= CURDATE() AND status = 'Scheduled'")->fetchColumn();

// Get today's appointments
$today = date('Y-m-d');
$stmt = $conn->prepare("
    SELECT a.*, CONCAT(c.first_name, ' ', c.last_name) as client_name, c.phone
    FROM appointments a
    JOIN clients c ON a.client_id = c.id
    WHERE a.appointment_date = ?
    ORDER BY a.appointment_time ASC
");
$stmt->execute([$today]);
$todays_appointments = $stmt->fetchAll();

// Get upcoming appointments (next 7 days)
$stmt = $conn->prepare("
    SELECT a.*, CONCAT(c.first_name, ' ', c.last_name) as client_name
    FROM appointments a
    JOIN clients c ON a.client_id = c.id
    WHERE a.appointment_date > ? AND a.appointment_date <= DATE_ADD(?, INTERVAL 7 DAY)
    AND a.status = 'Scheduled'
    ORDER BY a.appointment_date ASC, a.appointment_time ASC
    LIMIT 10
");
$stmt->execute([$today, $today]);
$upcoming = $stmt->fetchAll();

// Get recent clients
$recent_clients = $conn->query("SELECT * FROM clients ORDER BY created_at DESC LIMIT 5")->fetchAll();

require_once 'includes/header.php';
?>

<div class="page-header">
    <h1>Dashboard</h1>
    <p>Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</p>
</div>

<div class="stats-row">
    <div class="stat-card">
        <h3><?php echo $total_clients; ?></h3>
        <p>Total Clients</p>
        <small><?php echo $active_clients; ?> Active</small>
    </div>
    <div class="stat-card">
        <h3><?php echo $total_appointments; ?></h3>
        <p>Total Appointments</p>
        <small><?php echo $upcoming_appointments; ?> Upcoming</small>
    </div>
    <div class="stat-card">
        <h3><?php echo count($todays_appointments); ?></h3>
        <p>Today's Sessions</p>
        <small><?php echo date('l, F j'); ?></small>
    </div>
</div>

<div class="dashboard-grid">
    <div class="dashboard-section">
        <h2>Today's Appointments - <?php echo date('F j, Y'); ?></h2>
        <?php if (empty($todays_appointments)): ?>
            <p class="empty-message">No appointments scheduled for today.</p>
        <?php else: ?>
            <div class="appointment-list">
                <?php foreach ($todays_appointments as $appointment): ?>
                    <div class="appointment-card">
                        <div class="appointment-time">
                            <strong><?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?></strong>
                            <span><?php echo $appointment['duration_minutes']; ?> min</span>
                        </div>
                        <div class="appointment-details">
                            <h4>
                                <a href="client_view.php?id=<?php echo $appointment['client_id']; ?>">
                                    <?php echo htmlspecialchars($appointment['client_name']); ?>
                                </a>
                            </h4>
                            <p><?php echo $appointment['session_type']; ?></p>
                            <?php if ($appointment['phone']): ?>
                                <p class="phone"><?php echo htmlspecialchars($appointment['phone']); ?></p>
                            <?php endif; ?>
                            <?php if ($appointment['notes']): ?>
                                <p class="notes"><?php echo htmlspecialchars($appointment['notes']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="appointment-status">
                            <span class="status status-<?php echo strtolower($appointment['status']); ?>"><?php echo $appointment['status']; ?></span>
                            <a href="appointment_edit.php?id=<?php echo $appointment['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="dashboard-section">
        <h2>Upcoming Appointments</h2>
        <?php if (empty($upcoming)): ?>
            <p class="empty-message">No upcoming appointments in the next 7 days.</p>
        <?php else: ?>
            <table class="compact-table">
                <tbody>
                    <?php foreach ($upcoming as $appointment): ?>
                        <tr>
                            <td>
                                <strong><?php echo date('M j', strtotime($appointment['appointment_date'])); ?></strong><br>
                                <small><?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?></small>
                            </td>
                            <td>
                                <a href="client_view.php?id=<?php echo $appointment['client_id']; ?>">
                                    <?php echo htmlspecialchars($appointment['client_name']); ?>
                                </a><br>
                                <small><?php echo $appointment['session_type']; ?></small>
                            </td>
                            <td>
                                <a href="appointment_edit.php?id=<?php echo $appointment['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="appointments.php" class="btn btn-link">View All Appointments</a>
        <?php endif; ?>
    </div>

    <div class="dashboard-section">
        <h2>Recent Clients</h2>
        <?php if (empty($recent_clients)): ?>
            <p class="empty-message">No clients yet.</p>
        <?php else: ?>
            <table class="compact-table">
                <tbody>
                    <?php foreach ($recent_clients as $client): ?>
                        <tr>
                            <td>
                                <a href="client_view.php?id=<?php echo $client['id']; ?>">
                                    <?php echo htmlspecialchars($client['first_name'] . ' ' . $client['last_name']); ?>
                                </a><br>
                                <small><?php echo htmlspecialchars($client['email']); ?></small>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo strtolower($client['membership_type']); ?>">
                                    <?php echo $client['membership_type']; ?>
                                </span>
                            </td>
                            <td>
                                <span class="status status-<?php echo strtolower(str_replace(' ', '-', $client['status'])); ?>">
                                    <?php echo $client['status']; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="clients.php" class="btn btn-link">View All Clients</a>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
