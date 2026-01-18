<?php
require_once 'config/config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$db = new Database();
$conn = $db->getConnection();

// Get month and year from query string or use current
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

// Validate month and year
if ($month < 1 || $month > 12) {
    $month = (int)date('m');
}
if ($year < 2020 || $year > 2100) {
    $year = (int)date('Y');
}

// Calculate previous and next month
$prev_month = $month - 1;
$prev_year = $year;
if ($prev_month < 1) {
    $prev_month = 12;
    $prev_year--;
}

$next_month = $month + 1;
$next_year = $year;
if ($next_month > 12) {
    $next_month = 1;
    $next_year++;
}

// Get first and last day of month
$first_day = mktime(0, 0, 0, $month, 1, $year);
$last_day = mktime(0, 0, 0, $month + 1, 0, $year);

$days_in_month = date('t', $first_day);
$day_of_week = date('w', $first_day); // 0 (Sunday) to 6 (Saturday)

// Get all appointments for this month
$start_date = date('Y-m-01', $first_day);
$end_date = date('Y-m-t', $first_day);

$stmt = $conn->prepare("
    SELECT a.*, CONCAT(c.first_name, ' ', c.last_name) as client_name
    FROM appointments a
    JOIN clients c ON a.client_id = c.id
    WHERE a.appointment_date >= ? AND a.appointment_date <= ?
    AND a.status != 'Cancelled'
    ORDER BY a.appointment_time ASC
");
$stmt->execute([$start_date, $end_date]);
$all_appointments = $stmt->fetchAll();

// Organize appointments by day
$appointments_by_day = [];
foreach ($all_appointments as $appointment) {
    $day = (int)date('j', strtotime($appointment['appointment_date']));
    if (!isset($appointments_by_day[$day])) {
        $appointments_by_day[$day] = [];
    }
    $appointments_by_day[$day][] = $appointment;
}

require_once 'includes/header.php';
?>

<div class="page-header">
    <h1>Calendar</h1>
    <a href="appointment_add.php" class="btn btn-primary">Schedule Appointment</a>
</div>

<div class="calendar-container">
    <div class="calendar-header">
        <h2><?php echo date('F Y', $first_day); ?></h2>
        <div class="calendar-nav">
            <a href="calendar.php?month=<?php echo $prev_month; ?>&year=<?php echo $prev_year; ?>" class="btn btn-secondary">Previous</a>
            <a href="calendar.php" class="btn btn-secondary">Today</a>
            <a href="calendar.php?month=<?php echo $next_month; ?>&year=<?php echo $next_year; ?>" class="btn btn-secondary">Next</a>
        </div>
    </div>

    <div class="calendar-grid">
        <div class="calendar-day-header">Sun</div>
        <div class="calendar-day-header">Mon</div>
        <div class="calendar-day-header">Tue</div>
        <div class="calendar-day-header">Wed</div>
        <div class="calendar-day-header">Thu</div>
        <div class="calendar-day-header">Fri</div>
        <div class="calendar-day-header">Sat</div>

        <?php
        // Add empty cells for days before the first day of the month
        for ($i = 0; $i < $day_of_week; $i++) {
            echo '<div class="calendar-day other-month"></div>';
        }

        // Add cells for each day of the month
        for ($day = 1; $day <= $days_in_month; $day++) {
            $is_today = ($day == date('j') && $month == date('m') && $year == date('Y'));
            $class = 'calendar-day';
            if ($is_today) {
                $class .= ' today';
            }

            echo '<div class="' . $class . '">';
            echo '<div class="calendar-day-number">' . $day . '</div>';

            // Display appointments for this day
            if (isset($appointments_by_day[$day])) {
                echo '<div class="calendar-appointments">';
                foreach ($appointments_by_day[$day] as $appointment) {
                    $time = date('g:i A', strtotime($appointment['appointment_time']));
                    echo '<a href="appointment_edit.php?id=' . $appointment['id'] . '" class="calendar-appointment" title="' . htmlspecialchars($appointment['client_name']) . ' - ' . $time . '">';
                    echo $time . ' ' . htmlspecialchars(substr($appointment['client_name'], 0, 15));
                    echo '</a>';
                }
                echo '</div>';
            }

            echo '</div>';
        }

        // Add empty cells to complete the last row
        $total_cells = $day_of_week + $days_in_month;
        $remaining_cells = 7 - ($total_cells % 7);
        if ($remaining_cells < 7) {
            for ($i = 0; $i < $remaining_cells; $i++) {
                echo '<div class="calendar-day other-month"></div>';
            }
        }
        ?>
    </div>

    <div style="margin-top: 2rem;">
        <h3>Summary for <?php echo date('F Y', $first_day); ?></h3>
        <div class="stats-row" style="margin-top: 1rem;">
            <div class="stat-card">
                <h3><?php echo count($all_appointments); ?></h3>
                <p>Total Appointments</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count(array_filter($all_appointments, function($a) { return $a['status'] == 'Scheduled'; })); ?></h3>
                <p>Scheduled</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count(array_filter($all_appointments, function($a) { return $a['status'] == 'Completed'; })); ?></h3>
                <p>Completed</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
