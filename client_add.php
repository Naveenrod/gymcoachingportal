<?php
require_once 'config/config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

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
        $db = new Database();
        $conn = $db->getConnection();

        try {
            $stmt = $conn->prepare("INSERT INTO clients (first_name, last_name, email, phone, date_of_birth, gender, address, emergency_contact_name, emergency_contact_phone, membership_type, membership_start_date, membership_end_date, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->execute([
                $first_name, $last_name, $email, $phone, $date_of_birth, $gender,
                $address, $emergency_contact_name, $emergency_contact_phone,
                $membership_type, $membership_start_date, $membership_end_date,
                $notes, $status
            ]);

            redirect('clients.php');
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = 'Email already exists';
            } else {
                $error = 'Error adding client: ' . $e->getMessage();
            }
        }
    }
}

require_once 'includes/header.php';
?>

<div class="page-header">
    <h1>Add New Client</h1>
    <a href="clients.php" class="btn btn-secondary">Back to Clients</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="">
        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First Name *</label>
                <input type="text" id="first_name" name="first_name" required value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name *</label>
                <input type="text" id="last_name" name="last_name" required value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo isset($_POST['date_of_birth']) ? htmlspecialchars($_POST['date_of_birth']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender">
                    <option value="">Select Gender</option>
                    <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="2"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="emergency_contact_name">Emergency Contact Name</label>
                <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="<?php echo isset($_POST['emergency_contact_name']) ? htmlspecialchars($_POST['emergency_contact_name']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="emergency_contact_phone">Emergency Contact Phone</label>
                <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="<?php echo isset($_POST['emergency_contact_phone']) ? htmlspecialchars($_POST['emergency_contact_phone']) : ''; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="membership_type">Membership Type</label>
                <select id="membership_type" name="membership_type">
                    <option value="Basic" <?php echo (isset($_POST['membership_type']) && $_POST['membership_type'] == 'Basic') ? 'selected' : ''; ?>>Basic</option>
                    <option value="Premium" <?php echo (isset($_POST['membership_type']) && $_POST['membership_type'] == 'Premium') ? 'selected' : ''; ?>>Premium</option>
                    <option value="VIP" <?php echo (isset($_POST['membership_type']) && $_POST['membership_type'] == 'VIP') ? 'selected' : ''; ?>>VIP</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="Active" <?php echo (!isset($_POST['status']) || $_POST['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                    <option value="Inactive" <?php echo (isset($_POST['status']) && $_POST['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                    <option value="On Hold" <?php echo (isset($_POST['status']) && $_POST['status'] == 'On Hold') ? 'selected' : ''; ?>>On Hold</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="membership_start_date">Membership Start Date</label>
                <input type="date" id="membership_start_date" name="membership_start_date" value="<?php echo isset($_POST['membership_start_date']) ? htmlspecialchars($_POST['membership_start_date']) : date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label for="membership_end_date">Membership End Date</label>
                <input type="date" id="membership_end_date" name="membership_end_date" value="<?php echo isset($_POST['membership_end_date']) ? htmlspecialchars($_POST['membership_end_date']) : ''; ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" rows="3"><?php echo isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : ''; ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Client</button>
            <a href="clients.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
