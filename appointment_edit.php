<?php
require_once 'config/config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('appointments.php');
}

$appointment_id = $_GET['id'];

$db = new Database();
$conn = $db->getConnection();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_id = sanitize($_POST['client_id']);
    $appointment_date = sanitize($_POST['appointment_date']);
    $appointment_time = sanitize($_POST['appointment_time']);
    $duration_minutes = sanitize($_POST['duration_minutes']);
    $session_type = sanitize($_POST['session_type']);
    $status = sanitize($_POST['status']);
    $notes = sanitize($_POST['notes']);

    if (empty($client_id) || empty($appointment_date) || empty($appointment_time)) {
        $error = 'Client, date, and time are required';
    } else {
        try {
            $stmt = $conn->prepare("UPDATE appointments SET client_id = ?, appointment_date = ?, appointment_time = ?, duration_minutes = ?, session_type = ?, status = ?, notes = ? WHERE id = ?");

            $stmt->execute([
                $client_id, $appointment_date, $appointment_time,
                $duration_minutes, $session_type, $status, $notes,
                $appointment_id
            ]);

            $success = 'Appointment updated successfully';
        } catch (PDOException $e) {
            $error = 'Error updating appointment: ' . $e->getMessage();
        }
    }
}

// Get appointment data
$stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ?");
$stmt->execute([$appointment_id]);
$appointment = $stmt->fetch();

if (!$appointment) {
    redirect('appointments.php');
}

// Get all active clients
$stmt = $conn->query("SELECT id, first_name, last_name FROM clients WHERE status = 'Active' ORDER BY last_name, first_name");
$clients = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<div class="page-header">
    <h1>Edit Appointment</h1>
    <a href="appointments.php" class="btn btn-secondary">Back to Appointments</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="">
        <div class="form-group">
            <label for="client_id">Client *</label>
            <select id="client_id" name="client_id" required>
                <option value="">Select Client</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?php echo $client['id']; ?>" <?php echo ($appointment['client_id'] == $client['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($client['first_name'] . ' ' . $client['last_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="appointment_date">Date *</label>
                <input type="date" id="appointment_date" name="appointment_date" required value="<?php echo htmlspecialchars($appointment['appointment_date']); ?>">
            </div>

            <div class="form-group">
                <label for="appointment_time">Time *</label>
                <input type="time" id="appointment_time" name="appointment_time" required value="<?php echo htmlspecialchars($appointment['appointment_time']); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="duration_minutes">Duration (minutes)</label>
                <select id="duration_minutes" name="duration_minutes">
                    <option value="30" <?php echo $appointment['duration_minutes'] == 30 ? 'selected' : ''; ?>>30 minutes</option>
                    <option value="45" <?php echo $appointment['duration_minutes'] == 45 ? 'selected' : ''; ?>>45 minutes</option>
                    <option value="60" <?php echo $appointment['duration_minutes'] == 60 ? 'selected' : ''; ?>>60 minutes</option>
                    <option value="90" <?php echo $appointment['duration_minutes'] == 90 ? 'selected' : ''; ?>>90 minutes</option>
                    <option value="120" <?php echo $appointment['duration_minutes'] == 120 ? 'selected' : ''; ?>>120 minutes</option>
                </select>
            </div>

            <div class="form-group">
                <label for="session_type">Session Type</label>
                <select id="session_type" name="session_type">
                    <option value="Personal Training" <?php echo $appointment['session_type'] == 'Personal Training' ? 'selected' : ''; ?>>Personal Training</option>
                    <option value="Group Class" <?php echo $appointment['session_type'] == 'Group Class' ? 'selected' : ''; ?>>Group Class</option>
                    <option value="Consultation" <?php echo $appointment['session_type'] == 'Consultation' ? 'selected' : ''; ?>>Consultation</option>
                    <option value="Assessment" <?php echo $appointment['session_type'] == 'Assessment' ? 'selected' : ''; ?>>Assessment</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="Scheduled" <?php echo $appointment['status'] == 'Scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                <option value="Completed" <?php echo $appointment['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                <option value="Cancelled" <?php echo $appointment['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                <option value="No-Show" <?php echo $appointment['status'] == 'No-Show' ? 'selected' : ''; ?>>No-Show</option>
            </select>
        </div>

        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" rows="3"><?php echo htmlspecialchars($appointment['notes']); ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Appointment</button>
            <a href="appointments.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
