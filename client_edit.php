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

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $date_of_birth = sanitize($_POST['date_of_birth']);
    $gender = sanitize($_POST['gender']);
    $address = sanitize($_POST['address']);
    $emergency_contact_name = sanitize($_POST['emergency_contact_name']);
    $emergency_contact_phone = sanitize($_POST['emergency_contact_phone']);
    $membership_type = sanitize($_POST['membership_type']);
    $membership_start_date = sanitize($_POST['membership_start_date']);
    $membership_end_date = sanitize($_POST['membership_end_date']);
    $notes = sanitize($_POST['notes']);
    $status = sanitize($_POST['status']);

    if (empty($first_name) || empty($last_name) || empty($email)) {
        $error = 'First name, last name, and email are required';
    } else {
        try {
            $stmt = $conn->prepare("UPDATE clients SET first_name = ?, last_name = ?, email = ?, phone = ?, date_of_birth = ?, gender = ?, address = ?, emergency_contact_name = ?, emergency_contact_phone = ?, membership_type = ?, membership_start_date = ?, membership_end_date = ?, notes = ?, status = ? WHERE id = ?");

            $stmt->execute([
                $first_name, $last_name, $email, $phone, $date_of_birth, $gender,
                $address, $emergency_contact_name, $emergency_contact_phone,
                $membership_type, $membership_start_date, $membership_end_date,
                $notes, $status, $client_id
            ]);

            $success = 'Client updated successfully';
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = 'Email already exists';
            } else {
                $error = 'Error updating client: ' . $e->getMessage();
            }
        }
    }
}

// Get client data
$stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch();

if (!$client) {
    redirect('clients.php');
}

require_once 'includes/header.php';
?>

<div class="page-header">
    <h1>Edit Client</h1>
    <a href="clients.php" class="btn btn-secondary">Back to Clients</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="">
        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First Name *</label>
                <input type="text" id="first_name" name="first_name" required value="<?php echo htmlspecialchars($client['first_name']); ?>">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name *</label>
                <input type="text" id="last_name" name="last_name" required value="<?php echo htmlspecialchars($client['last_name']); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($client['email']); ?>">
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($client['phone']); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($client['date_of_birth']); ?>">
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender">
                    <option value="">Select Gender</option>
                    <option value="Male" <?php echo $client['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo $client['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo $client['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="2"><?php echo htmlspecialchars($client['address']); ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="emergency_contact_name">Emergency Contact Name</label>
                <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="<?php echo htmlspecialchars($client['emergency_contact_name']); ?>">
            </div>

            <div class="form-group">
                <label for="emergency_contact_phone">Emergency Contact Phone</label>
                <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="<?php echo htmlspecialchars($client['emergency_contact_phone']); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="membership_type">Membership Type</label>
                <select id="membership_type" name="membership_type">
                    <option value="Basic" <?php echo $client['membership_type'] == 'Basic' ? 'selected' : ''; ?>>Basic</option>
                    <option value="Premium" <?php echo $client['membership_type'] == 'Premium' ? 'selected' : ''; ?>>Premium</option>
                    <option value="VIP" <?php echo $client['membership_type'] == 'VIP' ? 'selected' : ''; ?>>VIP</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="Active" <?php echo $client['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                    <option value="Inactive" <?php echo $client['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                    <option value="On Hold" <?php echo $client['status'] == 'On Hold' ? 'selected' : ''; ?>>On Hold</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="membership_start_date">Membership Start Date</label>
                <input type="date" id="membership_start_date" name="membership_start_date" value="<?php echo htmlspecialchars($client['membership_start_date']); ?>">
            </div>

            <div class="form-group">
                <label for="membership_end_date">Membership End Date</label>
                <input type="date" id="membership_end_date" name="membership_end_date" value="<?php echo htmlspecialchars($client['membership_end_date']); ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" rows="3"><?php echo htmlspecialchars($client['notes']); ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Client</button>
            <a href="clients.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
