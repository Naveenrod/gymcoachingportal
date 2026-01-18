<?php
require_once 'config/config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('clients.php');
}

$client_id = $_GET['id'];

$db = new Database();
$conn = $db->getConnection();

// Get client data
$stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch();

if (!$client) {
    redirect('clients.php');
}

// Get client appointments
$stmt = $conn->prepare("SELECT * FROM appointments WHERE client_id = ? ORDER BY appointment_date DESC, appointment_time DESC LIMIT 10");
$stmt->execute([$client_id]);
$appointments = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<div class="page-header">
    <h1>Client Details</h1>
    <div>
        <a href="appointment_add.php?client_id=<?php echo $client_id; ?>" class="btn btn-primary">Schedule Appointment</a>
        <a href="client_edit.php?id=<?php echo $client_id; ?>" class="btn btn-secondary">Edit</a>
        <a href="clients.php" class="btn btn-secondary">Back to Clients</a>
    </div>
</div>

<div class="client-details">
    <div class="details-section">
        <h2>Personal Information</h2>
        <div class="details-grid">
            <div class="detail-item">
                <label>Name:</label>
                <span><?php echo htmlspecialchars($client['first_name'] . ' ' . $client['last_name']); ?></span>
            </div>
            <div class="detail-item">
                <label>Email:</label>
                <span><?php echo htmlspecialchars($client['email']); ?></span>
            </div>
            <div class="detail-item">
                <label>Phone:</label>
                <span><?php echo htmlspecialchars($client['phone'] ?: 'N/A'); ?></span>
            </div>
            <div class="detail-item">
                <label>Date of Birth:</label>
                <span><?php echo $client['date_of_birth'] ? date('F d, Y', strtotime($client['date_of_birth'])) : 'N/A'; ?></span>
            </div>
            <div class="detail-item">
                <label>Gender:</label>
                <span><?php echo htmlspecialchars($client['gender'] ?: 'N/A'); ?></span>
            </div>
            <div class="detail-item">
                <label>Address:</label>
                <span><?php echo htmlspecialchars($client['address'] ?: 'N/A'); ?></span>
            </div>
        </div>
    </div>

    <div class="details-section">
        <h2>Emergency Contact</h2>
        <div class="details-grid">
            <div class="detail-item">
                <label>Name:</label>
                <span><?php echo htmlspecialchars($client['emergency_contact_name'] ?: 'N/A'); ?></span>
            </div>
            <div class="detail-item">
                <label>Phone:</label>
                <span><?php echo htmlspecialchars($client['emergency_contact_phone'] ?: 'N/A'); ?></span>
            </div>
        </div>
    </div>

    <div class="details-section">
        <h2>Membership Information</h2>
        <div class="details-grid">
            <div class="detail-item">
                <label>Membership Type:</label>
                <span><span class="badge badge-<?php echo strtolower($client['membership_type']); ?>"><?php echo $client['membership_type']; ?></span></span>
            </div>
            <div class="detail-item">
                <label>Status:</label>
                <span><span class="status status-<?php echo strtolower(str_replace(' ', '-', $client['status'])); ?>"><?php echo $client['status']; ?></span></span>
            </div>
            <div class="detail-item">
                <label>Start Date:</label>
                <span><?php echo $client['membership_start_date'] ? date('F d, Y', strtotime($client['membership_start_date'])) : 'N/A'; ?></span>
            </div>
            <div class="detail-item">
                <label>End Date:</label>
                <span><?php echo $client['membership_end_date'] ? date('F d, Y', strtotime($client['membership_end_date'])) : 'N/A'; ?></span>
            </div>
        </div>
    </div>

    <?php if ($client['notes']): ?>
    <div class="details-section">
        <h2>Notes</h2>
        <p><?php echo nl2br(htmlspecialchars($client['notes'])); ?></p>
    </div>
    <?php endif; ?>

    <div class="details-section">
        <h2>Recent Appointments</h2>
        <?php if (empty($appointments)): ?>
            <p>No appointments scheduled yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Duration</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?></td>
                            <td><?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?></td>
                            <td><?php echo $appointment['duration_minutes']; ?> min</td>
                            <td><?php echo $appointment['session_type']; ?></td>
                            <td><span class="status status-<?php echo strtolower($appointment['status']); ?>"><?php echo $appointment['status']; ?></span></td>
                            <td class="actions">
                                <a href="appointment_edit.php?id=<?php echo $appointment['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
