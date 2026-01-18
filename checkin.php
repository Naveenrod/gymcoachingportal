<?php
require_once 'config/config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$db = new Database();
$conn = $db->getConnection();

// Handle form submission for updating check-in data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_checkin'])) {
    $client_id = intval($_POST['client_id']);
    $loom_link = sanitize($_POST['loom_link'] ?? '');
    $package = sanitize($_POST['package'] ?? '');
    $check_in_frequency = sanitize($_POST['check_in_frequency'] ?? '');
    $check_in_day = sanitize($_POST['check_in_day'] ?? '');
    $submitted = sanitize($_POST['submitted'] ?? '');
    $rank = sanitize($_POST['rank'] ?? '');
    
    $stmt = $conn->prepare("
        UPDATE clients 
        SET loom_link = ?, package = ?, check_in_frequency = ?, 
            check_in_day = ?, submitted = ?, rank = ?
        WHERE id = ?
    ");
    $stmt->execute([$loom_link, $package, $check_in_frequency, $check_in_day, $submitted, $rank, $client_id]);
    
    redirect('checkin.php?updated=1');
}

// Get all clients with check-in data
$sql = "SELECT id, first_name, last_name, loom_link, package, check_in_frequency, check_in_day, submitted, rank 
        FROM clients 
        ORDER BY last_name, first_name";
$clients = $conn->query($sql)->fetchAll();

require_once 'includes/header.php';
?>

<div class="page-header">
    <h1>Client Check In</h1>
</div>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert-updated">
        Check-in data updated successfully!
    </div>
<?php endif; ?>

<div class="checkin-container">
    <div class="checkin-header">
        <div class="checkin-logo">Building Her Coaching</div>
        <div class="checkin-title">2026 Client Check in Spreadsheet</div>
    </div>

    <div class="table-container">
        <table class="checkin-table">
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Loom Link</th>
                    <th>Package</th>
                    <th>Check In Frequency</th>
                    <th>When</th>
                    <th>Submitted</th>
                    <th>Rank</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clients)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--secondary-color);">
                            No clients found. <a href="client_add.php">Add a client</a> to get started.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td class="client-name-cell">
                                <?php echo htmlspecialchars($client['first_name'] . ' ' . $client['last_name']); ?>
                            </td>
                            <td class="loom-link-cell">
                                <form method="POST" class="checkin-form">
                                    <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
                                    <input type="url" name="loom_link" value="<?php echo htmlspecialchars($client['loom_link'] ?? ''); ?>" placeholder="https://loom.com/...">
                                    <input type="hidden" name="package" value="<?php echo htmlspecialchars($client['package'] ?? ''); ?>">
                                    <input type="hidden" name="check_in_frequency" value="<?php echo htmlspecialchars($client['check_in_frequency'] ?? ''); ?>">
                                    <input type="hidden" name="check_in_day" value="<?php echo htmlspecialchars($client['check_in_day'] ?? ''); ?>">
                                    <input type="hidden" name="submitted" value="<?php echo htmlspecialchars($client['submitted'] ?? ''); ?>">
                                    <input type="hidden" name="rank" value="<?php echo htmlspecialchars($client['rank'] ?? ''); ?>">
                                    <button type="submit" name="update_checkin" class="btn-save">Save</button>
                                </form>
                            </td>
                            <td class="package-cell">
                                <form method="POST" class="checkin-form">
                                    <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
                                    <input type="hidden" name="loom_link" value="<?php echo htmlspecialchars($client['loom_link'] ?? ''); ?>">
                                    <select name="package" onchange="this.form.submit()">
                                        <option value="">Select Package</option>
                                        <option value="Macros & Program" <?php echo ($client['package'] ?? '') == 'Macros & Program' ? 'selected' : ''; ?>>Macros & Program</option>
                                        <option value="Meal Plan & Prog" <?php echo ($client['package'] ?? '') == 'Meal Plan & Prog' ? 'selected' : ''; ?>>Meal Plan & Prog</option>
                                        <option value="Ambassador" <?php echo ($client['package'] ?? '') == 'Ambassador' ? 'selected' : ''; ?>>Ambassador</option>
                                        <option value="Program Only" <?php echo ($client['package'] ?? '') == 'Program Only' ? 'selected' : ''; ?>>Program Only</option>
                                    </select>
                                    <input type="hidden" name="check_in_frequency" value="<?php echo htmlspecialchars($client['check_in_frequency'] ?? ''); ?>">
                                    <input type="hidden" name="check_in_day" value="<?php echo htmlspecialchars($client['check_in_day'] ?? ''); ?>">
                                    <input type="hidden" name="submitted" value="<?php echo htmlspecialchars($client['submitted'] ?? ''); ?>">
                                    <input type="hidden" name="rank" value="<?php echo htmlspecialchars($client['rank'] ?? ''); ?>">
                                    <input type="hidden" name="update_checkin" value="1">
                                </form>
                            </td>
                            <td class="frequency-cell">
                                <form method="POST" class="checkin-form">
                                    <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
                                    <input type="hidden" name="loom_link" value="<?php echo htmlspecialchars($client['loom_link'] ?? ''); ?>">
                                    <input type="hidden" name="package" value="<?php echo htmlspecialchars($client['package'] ?? ''); ?>">
                                    <select name="check_in_frequency" onchange="this.form.submit()">
                                        <option value="">Select Frequency</option>
                                        <option value="Weekly" <?php echo ($client['check_in_frequency'] ?? '') == 'Weekly' ? 'selected' : ''; ?>>Weekly</option>
                                        <option value="Fortnightly" <?php echo ($client['check_in_frequency'] ?? '') == 'Fortnightly' ? 'selected' : ''; ?>>Fortnightly</option>
                                        <option value="Monthly" <?php echo ($client['check_in_frequency'] ?? '') == 'Monthly' ? 'selected' : ''; ?>>Monthly</option>
                                    </select>
                                    <input type="hidden" name="check_in_day" value="<?php echo htmlspecialchars($client['check_in_day'] ?? ''); ?>">
                                    <input type="hidden" name="submitted" value="<?php echo htmlspecialchars($client['submitted'] ?? ''); ?>">
                                    <input type="hidden" name="rank" value="<?php echo htmlspecialchars($client['rank'] ?? ''); ?>">
                                    <input type="hidden" name="update_checkin" value="1">
                                </form>
                            </td>
                            <td class="day-cell">
                                <form method="POST" class="checkin-form">
                                    <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
                                    <input type="hidden" name="loom_link" value="<?php echo htmlspecialchars($client['loom_link'] ?? ''); ?>">
                                    <input type="hidden" name="package" value="<?php echo htmlspecialchars($client['package'] ?? ''); ?>">
                                    <input type="hidden" name="check_in_frequency" value="<?php echo htmlspecialchars($client['check_in_frequency'] ?? ''); ?>">
                                    <select name="check_in_day" onchange="this.form.submit()">
                                        <option value="">Select Day</option>
                                        <option value="Monday" <?php echo ($client['check_in_day'] ?? '') == 'Monday' ? 'selected' : ''; ?>>Monday</option>
                                        <option value="Tuesday" <?php echo ($client['check_in_day'] ?? '') == 'Tuesday' ? 'selected' : ''; ?>>Tuesday</option>
                                        <option value="Wednesday" <?php echo ($client['check_in_day'] ?? '') == 'Wednesday' ? 'selected' : ''; ?>>Wednesday</option>
                                        <option value="Thursday" <?php echo ($client['check_in_day'] ?? '') == 'Thursday' ? 'selected' : ''; ?>>Thursday</option>
                                        <option value="Friday" <?php echo ($client['check_in_day'] ?? '') == 'Friday' ? 'selected' : ''; ?>>Friday</option>
                                        <option value="Saturday" <?php echo ($client['check_in_day'] ?? '') == 'Saturday' ? 'selected' : ''; ?>>Saturday</option>
                                        <option value="Sunday" <?php echo ($client['check_in_day'] ?? '') == 'Sunday' ? 'selected' : ''; ?>>Sunday</option>
                                    </select>
                                    <input type="hidden" name="submitted" value="<?php echo htmlspecialchars($client['submitted'] ?? ''); ?>">
                                    <input type="hidden" name="rank" value="<?php echo htmlspecialchars($client['rank'] ?? ''); ?>">
                                    <input type="hidden" name="update_checkin" value="1">
                                </form>
                            </td>
                            <td class="submitted-cell">
                                <form method="POST" class="checkin-form">
                                    <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
                                    <input type="hidden" name="loom_link" value="<?php echo htmlspecialchars($client['loom_link'] ?? ''); ?>">
                                    <input type="hidden" name="package" value="<?php echo htmlspecialchars($client['package'] ?? ''); ?>">
                                    <input type="hidden" name="check_in_frequency" value="<?php echo htmlspecialchars($client['check_in_frequency'] ?? ''); ?>">
                                    <input type="hidden" name="check_in_day" value="<?php echo htmlspecialchars($client['check_in_day'] ?? ''); ?>">
                                    <select name="submitted" onchange="this.form.submit()">
                                        <option value="">Not Submitted</option>
                                        <option value="Submitted" <?php echo ($client['submitted'] ?? '') == 'Submitted' ? 'selected' : ''; ?>>Submitted</option>
                                    </select>
                                    <input type="hidden" name="rank" value="<?php echo htmlspecialchars($client['rank'] ?? ''); ?>">
                                    <input type="hidden" name="update_checkin" value="1">
                                </form>
                            </td>
                            <td class="rank-cell">
                                <form method="POST" class="checkin-form">
                                    <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
                                    <input type="hidden" name="loom_link" value="<?php echo htmlspecialchars($client['loom_link'] ?? ''); ?>">
                                    <input type="hidden" name="package" value="<?php echo htmlspecialchars($client['package'] ?? ''); ?>">
                                    <input type="hidden" name="check_in_frequency" value="<?php echo htmlspecialchars($client['check_in_frequency'] ?? ''); ?>">
                                    <input type="hidden" name="check_in_day" value="<?php echo htmlspecialchars($client['check_in_day'] ?? ''); ?>">
                                    <input type="hidden" name="submitted" value="<?php echo htmlspecialchars($client['submitted'] ?? ''); ?>">
                                    <input type="text" name="rank" value="<?php echo htmlspecialchars($client['rank'] ?? ''); ?>" placeholder="e.g., 70-100%" style="width: 100px;">
                                    <button type="submit" name="update_checkin" class="btn-save">Save</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
