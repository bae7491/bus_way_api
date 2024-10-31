<?php
// 관광지 상세 페이지에서 팔로우 버튼을 누르면 DB에 팔로우 상태 저장.

include "../connection.php";

$email = $_POST["email"];
$contentId = $_POST["content_id"];
$title = $_POST["title"];
$contentTypeId = $_POST["content_type_id"];
$travelImage = $_POST["firstimage"];

// query문
$sqlQuery = "INSERT INTO travel_follow SET email = '$email', content_id = '$contentId', title = '$title', content_type_id = '$contentTypeId', firstimage = '$travelImage'";

if ($con->query($sqlQuery) === TRUE) {
    echo json_encode([
        "success" => true,
        "message" => "관광지 팔로우 성공!"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "관광지 팔로우 실패: " . $con->error
    ]);
}
