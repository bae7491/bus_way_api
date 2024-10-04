<?php
// DB에 연결.

$host = "localhost";
$user = "root";
$pw = "1234";
$dbName = "BusWay";

$con = mysqli_connect($host, $user, $pw, $dbName);

if (mysqli_connect_error()) { // mysqli_connect_error()로 변경하여 연결 오류 처리
    error_log("MySQL 접속 실패!! 오류 원인: " . mysqli_connect_error());
    echo json_encode(["success" => false, "message" => "DB 연결 실패"]);
    exit();
}

// 연결 확인 성공 시 메시지 로그에 기록 (웹 페이지에 표시하지 않음)
error_log("MySQL 접속 성공!!");

// 연결 확인
if ($con->connect_error) {
    error_log("Connection failed: " . $con->connect_error); // 연결 실패 시 오류 출력
    die("Connection failed: " . $con->connect_error);
}