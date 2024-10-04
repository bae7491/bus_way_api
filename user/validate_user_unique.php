<?php
// 회원가입 전 unique 값 (이메일, 전화번호) 중복 체크.

include "../connection.php";

$email = $_POST["email"];
$phoneNumber = $_POST["phoneNumber"];
$nickName = $_POST["nickName"];

//query문
$sqlQuery = "SELECT * FROM user WHERE email = '$email' OR phoneNumber = '$phoneNumber' OR nickName = '$nickName'";

$resultQuery = $con -> query($sqlQuery);

//이미 중복되는 값이 있을 시
if ($resultQuery -> num_rows > 0) {
  echo json_encode(["existUserUnique" => true]);
} else {
  echo json_encode(["existUserUnique" => false]);
}