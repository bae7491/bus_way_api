<?php
// 회원의 관광지 후기 목록 DB에서 불러오기

include '../connection.php';

$server_url = $_POST['server_url'];

$email = $_POST['email'];
$pageNo = (int)$_POST['pageNo'];
$pageSize = (int)$_POST['pageSize'];
$orderType = $_POST['sort'];

// OFFSET 계산
$offset = ($pageNo - 1) * $pageSize;

// 조건에 맞게 검색한 데이터 총 개수 계산
$totalCountQuery = "SELECT COUNT(*) as total_count FROM travel_review WHERE email = '$email'";

$totalCountResult = $con->query($totalCountQuery);

if ($totalCountResult) {
  $totalCountRow = $totalCountResult->fetch_assoc();
  $totalCount = (int)$totalCountRow['total_count'];
}

// 정렬에 사용할 쿼리문 조건 (rate: 별점이 높은 순 / date: 최신순(수정 날짜 기준))
$orderCluase = $orderType === 'rate' ? 'ORDER BY review_rate DESC,modified_date DESC' : 'ORDER BY modified_date DESC';

// 이메일(고유값)을 이용햐여 회원의 관광지 후기 목록 불러오기
$sqlQuery = "SELECT * FROM travel_review WHERE email = '$email' $orderCluase LIMIT $pageSize OFFSET $offset";

$result = $con->query($sqlQuery);

if ($result) {
  $reviewList = [];

  while($row = $result->fetch_assoc()) {
    $reviewImage = $row['review_image'] 
      ? "{$server_url}/images/uploads/{$row['review_image']}" 
      : null;

    $reviewList[] = [
      "review_id" => $row['review_id'],
      "email" => $row['email'],
      "nickName" => $row['nickName'],
      "content_id" => $row['content_id'],
      "title" => $row['title'],
      "review_rate" => $row['review_rate'],
      "review_content" => $row['review_content'],
      "review_image_path" => $reviewImage,
      "review_image" => $row['review_image'],
      "modified_date" => $row['modified_date'],
    ];
  }

  echo json_encode([
    "success" => true,
    "total_count" => $totalCount,
    "reviewList" => $reviewList,
  ]);
} else {
  echo json_encode([
        "success" => false,
        "message" => "데이터를 불러오지 못했습니다: {$con->error}",
    ]);
}
