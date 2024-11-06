<?php
// DB에서 조건에 맞게 관광지 후기 불러오기

include "../connection.php";

$server_url = $_POST['server_url'];

$content_id = $_POST['content_id'];
$pageNo = (int)$_POST['pageNo'];
$pageSize = (int)$_POST['pageSize'];
$orderType = $_POST['sort'];

// OFFSET 계산
$offset = ($pageNo - 1) * $pageSize;

// 조건에 맞게 검색한 데이터 총 개수
$totalCountQuery = "SELECT COUNT(*) as total_count FROM travel_review WHERE content_id = '$content_id'";
$totalCountResult = $con->query($totalCountQuery);
$totalCount = 0;

if ($totalCountResult) {
    $totalCountRow = $totalCountResult->fetch_assoc();
    $totalCount = (int)$totalCountRow['total_count'];
}

// 정렬에 사용할 쿼리문 조건 (rate: 별점이 높은 순 / date: 최신순(수정 날짜 기준))
$orderCluase = $orderType === 'rate' ? 'ORDER BY review_rate DESC,modified_date DESC' : 'ORDER BY modified_date DESC';

// 조건에 맞게 검색한 데이터
$sqlQuery = "SELECT * FROM travel_review WHERE content_id = '$content_id' $orderCluase LIMIT $pageSize OFFSET $offset";

$result = $con->query($sqlQuery);

if ($result) {
  $reviewList = [];
  while ($row = $result->fetch_assoc()) {
    $reviewImage = $row['review_image'] 
        ? "{$server_url}/images/uploads/{$row['review_image']}" 
        : null;

    $reviewList[] = [
            "review_id" => $row['review_id'],
            "email" => $row['email'],
            'nickName' => $row['nickName'],
            "content_id" => $row['content_id'],
            "review_rate" => $row['review_rate'],
            "review_content" => $row['review_content'],
            "modified_date" => $row['modified_date'],
            "review_image" => $reviewImage,
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