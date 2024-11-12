<?php
// 유저 계정 탈퇴(삭제) 요청 시, DB의 유저 정보 삭제.

include "../connection.php";

$email = $_POST['email'];

// query문
$sqlQuery = "DELETE FROM user WHERE email = '$email'";

$result = $con->query($sqlQuery);

if ($result) {
  echo json_encode([
        "success" => true,
        "message" => "유저 계정 삭제 성공!"
    ]);
} else {
  echo json_encode([
        "success" => false,
        "message" => "데이터를 불러오지 못했습니다: {$con->error}",
    ]);
}