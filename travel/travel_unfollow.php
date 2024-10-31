<?php
// 관광지 상세 페이지에서 팔로우 버튼을 누르면 DB에 팔로우 상태 삭제(취소).

include "../connection.php";

$email = $_POST["email"];
$contentId = $_POST["content_id"];
$title = $_POST["title"];
$contentTypeId = $_POST["content_type_id"];
$travelImage = $_POST["firstimage"];

// query문
$sqlQuery = $con->prepare("DELETE FROM travel_follow WHERE email = ? AND content_id = ? AND title = ? AND content_type_id = ? AND firstimage = ?");
$sqlQuery->bind_param("sssss", $email, $contentId, $title, $contentTypeId, $travelImage);

if ($sqlQuery->execute()) {
  echo json_encode([
        "success" => true,
        "message" => "관광지 팔로우 삭제 성공!"
    ]);
} else {
  echo json_encode([
        "success" => false,
        "message" => "관광지 팔로우 삭제 실패: " . $con->error
    ]);
}