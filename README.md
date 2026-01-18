# Gym Coaching Portal

A comprehensive web-based portal built with PHP for managing gym clients and scheduling appointments. This system replaces Excel-based client management with a professional, easy-to-use web application.

## Features

### Client Management
- Add, edit, view, and delete clients
- Track client information including:
  - Personal details (name, email, phone, date of birth, gender, address)
  - Emergency contact information
  - Membership type (Basic, Premium, VIP)
  - Membership dates and status
  - Client notes
- Search and filter clients by name, email, or status
- View client history and appointments

### Appointment Scheduling
- Schedule appointments with clients
- Track appointment details:
  - Date and time
  - Duration (30, 45, 60, 90, or 120 minutes)
  - Session type (Personal Training, Group Class, Consultation, Assessment)
  - Status (Scheduled, Completed, Cancelled, No-Show)
  - Notes
- Filter appointments by date, status, or client
- Edit and manage existing appointments

### Calendar View
- Visual monthly calendar display
- See all appointments at a glance
- Navigate between months
- Quick access to appointment details
- Monthly statistics summary

### Dashboard
- Overview of key statistics
- Today's appointments with client details
- Upcoming appointments for the next 7 days
- Recently added clients
- Quick access to schedule new appointments

### Authentication
- Secure login system
- Session management
- Password protection

## Installation

### Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache, Nginx, etc.)

### Setup Instructions

1. **Clone or download the repository**

2. **Database Setup**
   - Create a MySQL database named `gym_coaching_portal`
   - Import the database schema:
     ```bash
     mysql -u root -p gym_coaching_portal < database/schema.sql
     ```

3. **Configure Database Connection**
   - Edit `config/database.php` if your database credentials are different:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'gym_coaching_portal');
     define('DB_USER', 'your_username');
     define('DB_PASS', 'your_password');
     ```

4. **Set Up Web Server**
   - Point your web server's document root to the project directory
   - Ensure the web server has read permissions for all files

5. **Access the Application**
   - Open your browser and navigate to your configured URL
   - Default login credentials:
     - Username: `admin`
     - Password: `admin123`
   - **Important:** Change the default password after first login

## File Structure

```
gymcoachingportal/
├── config/
│   ├── config.php          # Main configuration file
│   └── database.php        # Database connection
├── database/
│   └── schema.sql          # Database schema
├── includes/
│   ├── header.php          # Page header template
│   └── footer.php          # Page footer template
├── assets/
│   └── css/
│       └── style.css       # Stylesheet
├── index.php               # Entry point (redirects to login or dashboard)
├── login.php               # Login page
├── logout.php              # Logout handler
├── dashboard.php           # Dashboard
├── clients.php             # Client list
├── client_add.php          # Add new client
├── client_edit.php         # Edit client
├── client_view.php         # View client details
├── appointments.php        # Appointment list
├── appointment_add.php     # Schedule appointment
├── appointment_edit.php    # Edit appointment
├── calendar.php            # Calendar view
└── README.md               # This file
```

## Usage

### Managing Clients

1. **Add a Client**
   - Click "Clients" in the navigation
   - Click "Add New Client"
   - Fill in the client information
   - Click "Add Client"

2. **View Client Details**
   - Click on a client's name or "View" button
   - See all client information and appointment history

3. **Edit Client**
   - Click "Edit" button next to a client
   - Update the information
   - Click "Update Client"

### Scheduling Appointments

1. **Schedule an Appointment**
   - Click "Appointments" or use "Schedule Appointment" button
   - Select the client
   - Choose date and time
   - Set duration and session type
   - Add any notes
   - Click "Schedule Appointment"

2. **View Appointments**
   - Use the "Appointments" page to see all appointments
   - Filter by date, status, or client
   - Use "Calendar" view for a visual monthly overview

3. **Update Appointment Status**
   - Edit an appointment to change its status
   - Mark as Completed, Cancelled, or No-Show as needed

### Using the Calendar

- Navigate to the "Calendar" page
- Use "Previous" and "Next" buttons to browse months
- Click "Today" to return to the current month
- Click on any appointment to edit it
- View monthly statistics at the bottom

## Security Considerations

1. **Change Default Credentials**
   - Immediately change the default admin password after installation

2. **Database Security**
   - Use strong database passwords
   - Restrict database user permissions

3. **File Permissions**
   - Ensure config files are not publicly accessible
   - Set appropriate file permissions on the server

4. **HTTPS**
   - Use HTTPS in production to encrypt data in transit

## Feature Enhancement Implemented

**Calendar View** - A visual monthly calendar that displays all appointments in an easy-to-navigate format. This enhancement addresses the obvious need for a visual scheduling tool, making it much easier to see appointment availability and manage the schedule compared to Excel spreadsheets.

## Support

For issues or questions, please refer to the documentation or contact your system administrator.

## License

This project is provided as-is for use by the gym coaching business.
