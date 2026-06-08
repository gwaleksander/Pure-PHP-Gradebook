# School Gradebook System

A simple PHP and MySQL school gradebook for managing students, teachers, parents, classes, grades, notes, messages, and bug reports.

## Features

- Role-based login for admins, teachers, students, and parents
- Student grade views with semester switching, weighted averages, final grades, and print-friendly reports
- Teacher tools for adding and editing grades, final grades, and notes
- Parent panel with access to a selected child's grades, notes, statistics, and messages
- Admin panel for managing users, classes, subjects, assignments, system settings, messages, and bug reports
- Internal messaging between users and groups
- Bug report submission and status tracking
- Semester management through the system settings panel

## Tech Stack

- PHP
- MySQL / MariaDB
- HTML, CSS, JavaScript
- Chart.js for statistics charts

## Project Structure

- `index.php` - login page
- `login/` - authentication and logout
- `gradebook/` - role-based dashboards
- `actions/` - backend actions for grades, notes, messages, users, and system updates
- `templates/` - shared layout parts and messaging UI
- `utils/` - database, auth, settings, and shared constants
- `assets/` - styles, images, and other static files
