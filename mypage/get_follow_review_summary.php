<?php
// 로그인한 회원이 팔로우한 관광지, 후기 개수 DB에서 불러오기

include "../connection.php";

$email = $_POST['email'];

$response = [];

// 팔로우 총 개수 조회
$followQuery = "SELECT COUNT(*) as follow_count FROM travel_follow WHERE email = '$email'";

$followResponse = $con->query($followQuery);

if ($followResponse) {
  $followResult = $followResponse->fetch_assoc();
  $follow_count = $followResult['follow_count'];
} else {
  echo json_encode([
        "success" => false,
        "message" => "데이터를 불러오지 못했습니다: {$con->error}",
    ]);
}

// 리뷰 총 개수 조회
$reviewQuery = "SELECT COUNT(*) as review_count FROM travel_review WHERE email = '$email'";

$reviewResponse = $con->query($reviewQuery);

if ($reviewResponse) {
  $reviewResult = $reviewResponse->fetch_assoc();
  $review_count = $reviewResult['review_count'];
} else {
  echo json_encode([
        "success" => false,
        "message" => "데이터를 불러오지 못했습니다: {$con->error}",
    ]);
}

echo json_encode([
  "success" => true, 
  "follow_count" => $follow_count,
  "review_count" => $review_count,
]);
