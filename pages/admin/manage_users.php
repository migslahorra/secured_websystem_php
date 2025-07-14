<?php
require_once __DIR__ . '/../../includes/csrf.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../connection/db_connect.php';

checkRole(['admin']);

// Handle form submission to update user roles
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    if (!verifyCsrfToken($_POST['csrf_token'])) {
        $_SESSION['error'] = "Invalid CSRF token!";
        redirect('manage_users.php');
    }

    $userId = $_POST['user_id'];
    $newRole = $_POST['new_role'];

    try {
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$newRole, $userId]);
        
        $_SESSION['success'] = "User role updated successfully!";
        redirect('manage_users.php');
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        redirect('manage_users.php');
    }
}

// Get all users
try {
    $stmt = $pdo->query("SELECT id, username, email, role FROM users ORDER BY role, username");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    $users = [];
}

// Display messages
$error = '';
$success = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Dashboard</title>
    <link rel="stylesheet" href="../../styles/styles.css">
</head>
<body>
    <div class="container">
        <h1>Manage Users</h1>
        
        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <a href="dashboard.php" class="btn">Back to Dashboard</a>
        
        <div class="user-list">
            <h2>User Accounts</h2>
            
            <?php if (empty($users)): ?>
                <p>No users found.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Current Role</th>
                            <th>New Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($user['role'])); ?></td>
                            <td>
                                <form method="POST" class="role-form">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    
                                    <select name="new_role" required>
                                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        <option value="faculty" <?php echo $user['role'] === 'faculty' ? 'selected' : ''; ?>>Faculty</option>
                                        <option value="student" <?php echo $user['role'] === 'student' ? 'selected' : ''; ?>>Student</option>
                                    </select>
                            </td>
                            <td>
                                    <button type="submit" name="update_role" class="btn btn-small">Update</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>