<?php
require_once 'config/config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$db = new Database();
$conn = $db->getConnection();

$error = '';
$success = '';
$preselected_client = isset($_GET['client_id']) && is_numeric($_GET['client_id']) ? $_GET['client_id'] : '';

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
            $stmt = $conn->prepare("INSERT INTO appointments (client_id, appointment_date, appointment_time, duration_minutes, session_type, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");

            $stmt->execute([
                $client_id, $appointment_date, $appointment_time,
                $duration_minutes, $session_type, $status, $notes
            ]);

            redirect('appointments.php');
        } catch (PDOException $e) {
            $error = 'Error scheduling appointment: ' . $e->getMessage();
        }
    }
}

// Get all active clients
$stmt = $conn->query("SELECT id, first_name, last_name FROM clients WHERE status = 'Active' ORDER BY last_name, first_name");
$clients = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<div class="page-header">
    <h1>Schedule Appointment</h1>
    <a href="appointments.php" class="btn btn-secondary">Back to Appointments</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="">
        <div class="form-group">
            <label for="client_id">Client *</label>
            <select id="client_id" name="client_id" required>
                <option value="">Select Client</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?php echo $client['id']; ?>" <?php echo ($preselected_client == $client['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($client['first_name'] . ' ' . $client['last_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="appointment_date">Date *</label>
                <input type="date" id="appointment_date" name="appointment_date" required value="<?php echo isset($_POST['appointment_date']) ? htmlspecialchars($_POST['appointment_date']) : date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label for="appointment_time">Time *</label>
                <input type="time" id="appointment_time" name="appointment_time" required value="<?php echo isset($_POST['appointment_time']) ? htmlspecialchars($_POST['appointment_time']) : '09:00'; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="duration_minutes">Duration (minutes)</label>
                <select id="duration_minutes" name="duration_minutes">
                    <option value="30">30 minutes</option>
                    <option value="45">45 minutes</option>
                    <option value="60" selected>60 minutes</option>
                    <option value="90">90 minutes</option>
                    <option value="120">120 minutes</option>
                </select>
            </div>

            <div class="form-group">
                <label for="session_type">Session Type</label>
                <select id="session_type" name="session_type">
                    <option value="Personal Training" selected>Personal Training</option>
                    <option value="Group Class">Group Class</option>
                    <option value="Consultation">Consultation</option>
                    <option value="Assessment">Assessment</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="Scheduled" selected>Scheduled</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
                <option value="No-Show">No-Show</option>
            </select>
        </div>

        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" rows="3"><?php echo isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : ''; ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Schedule Appointment</button>
            <a href="appointments.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
