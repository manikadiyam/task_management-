<?php
// Sidebar template for Task Manager
if (!isset($activePage))
    $activePage = '';
require_once '../includes/auth.php';
require_once '../includes/config.php';
?>
<link href="<?php echo BASE_URL; ?>/assets/sidebar.css" rel="stylesheet">
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <span>Task Manager</span>
        <button class="toggle-btn" onclick="toggleSidebar()">&#9776;</button>
    </div>
    <div class="sidebar-menu" id="sidebarMenu">
        <ul>
            <?php if ($user_role === 'admin'): ?>
                <li>
                    <a href="dashboard.php" class="<?php echo $activePage === 'dashboard' ? 'active' : ''; ?>"
                        title="Dashboard">
                        <span class="sidebar-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="#2c5364" viewBox="0 0 24 24" style="display:inline;">
                                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" />
                            </svg></span>
                        <span class="sidebar-label">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="tasks.php" class="<?php echo $activePage === 'tasks' ? 'active' : ''; ?>" title="Tasks">
                        <span class="sidebar-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="#2c5364" viewBox="0 0 24 24" style="display:inline;">
                                <path
                                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H7v-2h5v2zm5-4H7v-2h10v2zm0-4H7V7h10v2z" />
                            </svg></span>
                        <span class="sidebar-label">Tasks</span>
                    </a>
                </li>
                <li>
                    <a href="users.php" class="<?php echo $activePage === 'users' ? 'active' : ''; ?>" title="Users">
                        <span class="sidebar-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="#2c5364" viewBox="0 0 24 24" style="display:inline;">
                                <path
                                    d="M12 12c2.7 0 8 1.34 8 4v2H4v-2c0-2.66 5.3-4 8-4zm0-2a4 4 0 1 1 0-8 4 4 0 0 1 0 8z" />
                            </svg></span>
                        <span class="sidebar-label">Users</span>
                    </a>
                </li>
                <li>
                    <a href="reports.php" class="<?php echo $activePage === 'reports' ? 'active' : ''; ?>" title="Reports">
                        <span class="sidebar-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="#2c5364" viewBox="0 0 24 24" style="display:inline;">
                                <path
                                    d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 8h14v-2H7v2zm0-4h14v-2H7v2zm0-6v2h14V7H7z" />
                            </svg></span>
                        <span class="sidebar-label">Reports</span>
                    </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="dashboard.php" class="<?php echo $activePage === 'dashboard' ? 'active' : ''; ?>"
                        title="Dashboard">
                        <span class="sidebar-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="#2c5364" viewBox="0 0 24 24" style="display:inline;">
                                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" />
                            </svg></span>
                        <span class="sidebar-label">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="tasks.php" class="<?php echo $activePage === 'tasks' ? 'active' : ''; ?>" title="Tasks">
                        <span class="sidebar-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="#2c5364" viewBox="0 0 24 24" style="display:inline;">
                                <path
                                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H7v-2h5v2zm5-4H7v-2h10v2zm0-4H7V7h10v2z" />
                            </svg></span>
                        <span class="sidebar-label">Tasks</span>
                    </a>
                </li>
            <?php endif; ?>
            <li>
                <a href="<?php echo BASE_URL; ?>/pages/logout.php" title="Logout">
                    <span class="sidebar-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="#2c5364" viewBox="0 0 24 24" style="display:inline;">
                            <path
                                d="M16 13v-2H7V8l-5 4 5 4v-3zM20 3h-8c-1.1 0-2 .9-2 2v4h2V5h8v14h-8v-4h-2v4c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z" />
                        </svg></span>
                    <span class="sidebar-label">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('collapsed');
    }
</script>
<style>
    .sidebar-icon {
        display: flex !important;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        min-height: 32px;
        margin-right: 10px;
        color: #2c5364;
    }

    .sidebar.collapsed .sidebar-label {
        display: none !important;
    }

    .sidebar.collapsed .sidebar-icon {
        margin-right: 0;
        display: flex !important;
        align-items: center;
        justify-content: center;
    }

    .sidebar.collapsed .sidebar-menu ul li a {
        display: flex;
        justify-content: center;
        align-items: center;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    .sidebar-menu ul li a {
        display: flex;
        align-items: center;
    }

    .sidebar-menu ul li a .sidebar-label {
        transition: opacity 0.2s;
    }
</style>