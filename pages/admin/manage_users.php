<?php
session_start();

// Verify admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    session_unset();
    session_destroy();
    header("Location: ../../pages/login.php?error=unauthorized");
    exit();
}

require_once('../../connection/db_con.php');

// Handle role update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $new_role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
    
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $new_role, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "User role updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating user role: " . $conn->error;
    }
    $stmt->close();
    
    // Redirect to prevent form resubmission
    header("Location: manage_users.php");
    exit();
}

// Get all users with optional filtering
$search = isset($_GET['search']) ? "%" . $conn->real_escape_string($_GET['search']) . "%" : "%";
$role_filter = isset($_GET['role_filter']) ? $conn->real_escape_string($_GET['role_filter']) : '';

$sql = "SELECT id, username, email, role, created_at FROM users WHERE username LIKE ?";
$params = [$search];
$types = "s";

if (!empty($role_filter)) {
    $sql .= " AND role = ?";
    $params[] = $role_filter;
    $types .= "s";
}

$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
if ($types === "s") {
    $stmt->bind_param($types, $params[0]);
} else {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Admin Dashboard</title>
    <link rel="stylesheet" href="../../styles/styles.css">
    <style>
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .user-table th, .user-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .user-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .user-table tr:hover {
            background-color: #f5f5f5;
        }
        .role-select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .search-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-input {
            flex: 1;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .filter-select {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="dashboard.php" class="navbar-brand">Admin Portal</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                <li class="nav-item"><a href="manage_users.php" class="nav-link">User Management</a></li>
                <li class="nav-item"><a href="settings.php" class="nav-link">System Settings</a></li>
                <li class="nav-item"><a href="../../authentication/logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
        <h1>Manage Users</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <div class="search-container">
            <form method="GET" class="search-form" style="display: flex; width: 100%; gap: 10px;">
                <input type="text" name="search" placeholder="Search by username..." 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" 
                       class="search-input">
                
                <select name="role_filter" class="filter-select">
                    <option value="">All Roles</option>
                    <option value="admin" <?php echo (isset($_GET['role_filter']) && $_GET['role_filter'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="faculty" <?php echo (isset($_GET['role_filter']) && $_GET['role_filter'] === 'faculty') ? 'selected' : ''; ?>>Faculty</option>
                    <option value="student" <?php echo (isset($_GET['role_filter']) && $_GET['role_filter'] === 'student') ? 'selected' : ''; ?>>Student</option>
                </select>
                
                <button type="submit" class="btn">Search</button>
                <a href="manage_users.php" class="btn btn-danger">Reset</a>
            </form>
        </div>
        
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <form method="POST" class="role-form">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <select name="role" class="role-select" onchange="this.form.submit()">
                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                <option value="faculty" <?php echo $user['role'] === 'faculty' ? 'selected' : ''; ?>>Faculty</option>
                                <option value="student" <?php echo $user['role'] === 'student' ? 'selected' : ''; ?>>Student</option>
                            </select>
                            <input type="hidden" name="update_role" value="1">
                        </form>
                    </td>
                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                    <td>
                        <a href="view_user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm">View</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if (empty($users)): ?>
            <div class="alert alert-info">No users found matching your criteria.</div>
        <?php endif; ?>
    </div>
    
    <script>
    // Confirm before changing admin roles
    document.querySelectorAll('.role-select').forEach(select => {
        select.addEventListener('change', function() {
            if (this.value === 'admin') {
                if (!confirm('Warning: Granting admin privileges gives full system access. Continue?')) {
                    this.form.reset();
                }
            }
        });
    });
    </script>
</body>
</html>