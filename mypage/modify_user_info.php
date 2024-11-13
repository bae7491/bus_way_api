<?php
// 수정된 내정보를 DB에 업데이트하도록 요청

include "../connection.php";

$email = $_POST['email'];
$phoneNumber = $_POST['phoneNumber'];
$nickName = $_POST['nickName'];

// 해당하는 이메일의 유저의 전화번호 및 닉네임 변경
$sqlQuery = "UPDATE user SET phoneNumber = '$phoneNumber', nickName = '$nickName' WHERE email = '$email'";

$result = $con->query($sqlQuery);

if ($result) {
  echo json_encode([
        "success" => true,
        "message" => "유저 정보 수정 성공!"
    ]);
} else {
  echo json_encode([
        "success" => false,
        "message" => "데이터를 불러오지 못했습니다: {$con->error}",
    ]);
}
