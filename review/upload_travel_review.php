<?php
// 작성한 리뷰를 DB에 저장하도록 요청

include "../connection.php";

$target_dir = "uploads/";

// uploads 폴더가 존재하지 않으면 생성
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// POST 데이터 가져오기
$email = $_POST['email'];
$review_rate = $_POST['review_rate'];
$review_content = $_POST['review_content'];

// 이미지 업로드 여부 확인 및 처리
$image_uploaded = isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK;
$target_file = $image_uploaded ? $target_dir . basename($_FILES["image"]["name"]) : null;

if ($image_uploaded && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    $image_path = $target_file; // 이미지 경로 저장
} else {
    $image_path = null; // 이미지가 없으면 null로 설정
}

// SQL 쿼리 작성
$sqlQuery = "INSERT INTO travel_review (email, review_rate, review_content, review_image) VALUES ('$email', '$review_rate', '$review_content', " . ($image_path ? "'$image_path'" : "NULL") . ")";

if ($con->query($sqlQuery) === TRUE) {
    echo json_encode([
        "success" => true,
        "message" => "리뷰 작성 성공!"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "리뷰 작성 실패: " . $con->error
    ]);
}

$con->close();
?>
