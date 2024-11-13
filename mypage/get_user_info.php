<?php
// 로그인 한 회원 정보를 DB에서 불러오기

include "../connection.php";

$email = $_POST['email'];

// 이메일(고유값)을 이용하여 회원 정보 가져오기
$sqlQuery = "SELECT * FROM user WHERE email = '$email'";

$result = $con->query($sqlQuery);

if ($result) {
  $row = $result->fetch_assoc();
  echo json_encode([
    "success" => true,
    "email" => $row['email'],
    "nickName" => $row['nickName'],
    "name" => $row['name'],
    "phoneNumber" => $row['phoneNumber'],
  ]);
} else {
  echo json_encode([
        "success" => false,
        "message" => "데이터를 불러오지 못했습니다: {$con->error}",
    ]);
}
