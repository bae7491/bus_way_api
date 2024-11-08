<?php
// 회원의 관광지 팔로우 목록 DB에서 불러오기

include '../connection.php';

$email = $_POST['email'];
$pageNo = (int)$_POST['pageNo'];
$pageSize = (int)$_POST['pageSize'];

// OFFSET 계산
$offset = ($pageNo - 1) * $pageSize;

// 조건에 맞게 검색한 데이터 총 개수
$totalCountQuery = "SELECT COUNT(*) as total_count FROM travel_follow WHERE email = '$email'";

$totalCountResult = $con->query($totalCountQuery);

if ($totalCountResult) {
    $totalCountRow = $totalCountResult->fetch_assoc();
    $totalCount = (int)$totalCountRow['total_count'];
}

// 이메일(고유값)을 이용하여 회원의 관광지 팔로우 목록 불러오기
$sqlQuery = "SELECT * FROM travel_follow WHERE email = '$email' LIMIT $pageSize OFFSET $offset";

$result = $con->query($sqlQuery);

if ($result) {
  $followList = [];
  while($row = $result->fetch_assoc()) {
    $followList[] = [
      "follow_id" => $row['follow_id'],
      "email" => $row['email'],
      "title" => $row['title'],
      "content_id" => $row['content_id'],
      "content_type_id" => $row['content_type_id'],
      "firstimage" => $row['firstimage'],
    ];
  }

  echo json_encode([
    "success" => true,
    "total_count" => $totalCount,
    "followList" => $followList,
  ]);
} else {
  echo json_encode([
        "success" => false,
        "message" => "데이터를 불러오지 못했습니다: {$con->error}",
    ]);
}
