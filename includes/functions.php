<?php
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function password_hash_secure($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

function verify_recaptcha($response) {
    $secret = '6LdyaYArAAAAAI7QvLHVoN6tANXiby9voMYR546H'; // Replace with your actual secret key
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    
    $data = [
        'secret' => $secret,
        'response' => $response
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
            'verify_peer' => false, // Bypass SSL verification (for development only)
            'verify_peer_name' => false
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return json_decode($result)->success;
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function check_role($allowed_roles) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
        redirect('../index.php');
    }
}
?>