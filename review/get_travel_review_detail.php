<?php
// 선택한 관광지의 상세 후기 정보를 DB에서 불러오기

include "../connection.php";

$server_url = $_POST['server_url'];

$review_id = $_POST['review_id'];

// 해당하는 리뷰 ID의 관광지 상세 정보 조회 쿼리
$sqlQuery = "SELECT * FROM travel_review WHERE review_id = '$review_id'";

$result = $con->query($sqlQuery);

if ($result) {
  $reviewDetailList = [];
  $row = $result->fetch_assoc();
  $reviewImage = $row['review_image'] 
      ? "{$server_url}/images/uploads/{$row['review_image']}" 
      : null;

  echo json_encode([
    "success" => true,
    "review_id" => $row['review_id'],
    "review_rate" => $row['review_rate'],
    "review_content" => $row['review_content'],
    "review_image" => $reviewImage,
  ]);
} else {
  echo json_encode([
    "success" => false,
    "message" => "데이터를 불러오지 못했습니다: {$con->error}",
  ]);
}
