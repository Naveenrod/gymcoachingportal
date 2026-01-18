<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <h2><?php echo SITE_NAME; ?></h2>
        </div>
        <ul class="nav-menu">
            <li><a href="dashboard.php" <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'class="active"' : ''; ?>>Dashboard</a></li>
            <li><a href="clients.php" <?php echo strpos($_SERVER['PHP_SELF'], 'client') !== false && basename($_SERVER['PHP_SELF']) != 'checkin.php' ? 'class="active"' : ''; ?>>Clients</a></li>
            <li><a href="checkin.php" <?php echo basename($_SERVER['PHP_SELF']) == 'checkin.php' ? 'class="active"' : ''; ?>>Check In</a></li>
            <li><a href="appointments.php" <?php echo strpos($_SERVER['PHP_SELF'], 'appointment') !== false ? 'class="active"' : ''; ?>>Appointments</a></li>
            <li><a href="calendar.php" <?php echo basename($_SERVER['PHP_SELF']) == 'calendar.php' ? 'class="active"' : ''; ?>>Calendar</a></li>
        </ul>
        <div class="nav-user">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
            <a href="logout.php" class="btn btn-sm btn-secondary">Logout</a>
        </div>
    </nav>
    <div class="container">
