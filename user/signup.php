<?php
// 회원가입 정보 DB에 저장.

include "../connection.php";

$email = $_POST["email"];
$password = md5($_POST["password"]);
$name = $_POST["name"];
$nickName = $_POST["nickName"];
$phoneNumber = $_POST["phoneNumber"];
$idToken = $_POST["idToken"];

$sqlQuery = "INSERT INTO user SET email = '$email', password = '$password', name = '$name', nickName = '$nickName', phoneNumber = '$phoneNumber';";
if ($con->query($sqlQuery) === TRUE) {
    echo json_encode([
        "success" => true,
        "message" => "회원가입 성공!"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "회원가입 실패: " . $con->error
    ]);
}