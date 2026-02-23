<?php
session_start();
require 'db_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get current user data to compare
$current_user_stmt = $conn->prepare("SELECT email, mobile FROM users WHERE id=?");
$current_user_stmt->bind_param("i", $user_id);
$current_user_stmt->execute();
$current_user = $current_user_stmt->get_result()->fetch_assoc();
$current_user_stmt->close();

// Get POST data
$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$district = trim($_POST['district'] ?? '');
$state = trim($_POST['state'] ?? '');

// Validate required fields
if (empty($fullname) || empty($email) || empty($mobile)) {
    echo json_encode(['status' => 'error', 'message' => 'Full name, email, and mobile are required.']);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
    exit;
}

// Only check for duplicate email if it has changed
if (strtolower(trim($email)) !== strtolower(trim($current_user['email']))) {
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE LOWER(TRIM(email))=LOWER(TRIM(?)) AND id!=?");
    $check_stmt->bind_param("si", $email, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    if ($check_result->num_rows > 0) {
        $check_stmt->close();
        echo json_encode(['status' => 'error', 'message' => 'Email already exists.']);
        exit;
    }
    $check_stmt->close();
}

// Only check for duplicate mobile if it has changed
if (trim($mobile) !== trim($current_user['mobile'])) {
    $check_mobile = $conn->prepare("SELECT id FROM users WHERE TRIM(mobile)=TRIM(?) AND id!=?");
    $check_mobile->bind_param("si", $mobile, $user_id);
    $check_mobile->execute();
    $check_mobile_result = $check_mobile->get_result();
    if ($check_mobile_result->num_rows > 0) {
        $check_mobile->close();
        echo json_encode(['status' => 'error', 'message' => 'Mobile number already exists.']);
        exit;
    }
    $check_mobile->close();
}

// Update database
$stmt = $conn->prepare("UPDATE users SET fullname=?, email=?, mobile=?, district=?, state=? WHERE id=?");
$stmt->bind_param("sssssi", $fullname, $email, $mobile, $district, $state, $user_id);

if ($stmt->execute()) {
    $stmt->close();
    echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully!']);
} else {
    $error = $stmt->error;
    $stmt->close();
    echo json_encode(['status' => 'error', 'message' => 'Error updating profile: ' . $error]);
}
?>
