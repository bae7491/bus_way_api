<?php
// 관광지 리뷰 총 개수 및 평점 평균을 DB에서 불러오기

include "../connection.php";

$content_id = $_POST['content_id'];

$sqlQuery = "SELECT COUNT(review_rate) as review_count, ROUND(AVG(review_rate), 1) as average_rate FROM travel_review WHERE content_id='$content_id'";

$result = $con->query($sqlQuery);

if ($result) {
  $row = $result->fetch_assoc();
  echo json_encode([
    "success" => true,
    "review_count" => $row['review_count'] ?? '0',
    "review_rate" => $row['average_rate'] ?? '0.0',
  ]);
} else {
  echo json_encode([
        "success" => false,
        "message" => "데이터를 불러오지 못했습니다: {$con->error}",
    ]);
}
