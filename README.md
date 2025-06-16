# Task Manager PHP Application

A modern, full-featured PHP task management system with a beautiful Swift-style UI, built for teams and admins.

## Features

### User Management

- Admin and Team roles
- Add, edit, and delete users
- Assign roles (Admin/Team)
- Admin can reset any user's password

### Authentication

- Secure login/logout
- Role-based access control

### Task Management

- Add, edit, delete tasks
- Assign tasks to any user
- Only Admin can see all tasks; Team members see only their own
- Task status: Pending, In Progress, On Hold, Completed
- Task list shows next follow-up date
- Separate views for pending and completed tasks

### Task Follow-up

- Add follow-up comments, status, and next follow-up date
- AJAX follow-up submission
- Task status automatically updates to latest follow-up status
- Full follow-up history for each task
- Only follow-up can change task status (not direct edit)

### Dashboard

- Team members see their own task stats and charts
- Admin sees team performance: total/completed/pending tasks per user, overall stats, and charts

### Reports (Admin Only)

- Filter by team member, status, and date range
- View all matching tasks in a table
- Export filtered results to CSV (Excel-compatible)

### UI/UX

- Modern Swift-style UI (glassmorphism, gradients, rounded fields, icons)
- Responsive sidebar navigation with icons and tooltips
- Sidebar adapts to user role (Admin sees all menus, Team sees only relevant ones)

### Security

- Passwords stored securely (hashed)
- Session-based authentication
- Role-based menu and data access

### Database

- MySQL structure included (`db_structure.sql`)
- Users, Tasks, and Task Followups tables

## Getting Started

1. Import `db_structure.sql` into your MySQL database.
2. Configure database credentials in `includes/db.php`.
3. Set up your first admin user (use the hash generator if needed).
4. Log in and start managing users, tasks, and follow-ups!

## Requirements

- PHP 7.4+
- MySQL
- Web server (Apache, Nginx, etc.)

---

For any questions or customizations, contact your developer.
