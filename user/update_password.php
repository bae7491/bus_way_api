<?php
// 로그인 시, 비밀번호가 바뀌었으면 비밀번호 업데이트.
error_reporting(E_ALL);
ini_set("display_errors", 1);

include '../connection.php';

$email = $_POST['email'];
$new_password = password_hash($_POST['password'], PASSWORD_DEFAULT); // 비밀번호 암호화

$sqlQuery = "UPDATE user SET password = '$new_password' WHERE email = '$email';";

if ($con->query($sqlQuery) === TRUE) {
    echo json_encode([
        "success" => true,
        "message" => "비밀번호 변경 성공!"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "비밀번호 변경 실패: " . $con->error
    ]);
}