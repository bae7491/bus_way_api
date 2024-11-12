<?php
// 관광지 후기 삭제 요청 시, DB의 후기 삭제.

include "../connection.php";

$reviewId = $_POST['review_id'];
$reviewImage = $_POST['review_image']; // 이미지 이름

// query문
$sqlQuery = "DELETE FROM travel_review WHERE review_id = '$reviewId'";

$result = $con->query($sqlQuery);

if ($result) {
  if ($reviewImage != null) {
    unlink("../images/uploads/{$reviewImage}");
  }

  echo json_encode([
        "success" => true,
        "message" => "관광지 후기 삭제 성공!"
    ]);
} else {
  echo json_encode([
        "success" => false,
        "message" => "데이터를 불러오지 못했습니다: {$con->error}",
    ]);
}