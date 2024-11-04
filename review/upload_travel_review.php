<?php
// 작성한 리뷰를 DB에 저장하도록 요청

include "../connection.php";

$email = $_POST['email'];
$content_id = $_POST['content_id'];
$review_rate = $_POST['review_rate'];
$review_content = $_POST['review_content'];

// 이미지가 있는지 확인 (없으면 null로 설정)
if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $image = $_FILES['image']['name'];
    $imagePath = "../images/uploads/{$image}";
    $tmp_name = $_FILES['image']['tmp_name'];

    move_uploaded_file($tmp_name, $imagePath) 
    ? $image_path = $image 
    : $image_path = null;
} else {
    $image_path = null;
}

// SQL 쿼리 작성: 이미지가 있는 경우와 없는 경우에 따라 쿼리를 달리 작성
$sqlQuery = "INSERT INTO travel_review (email, content_id, review_rate, review_content, review_image) VALUES ('$email', '$content_id', '$review_rate', '$review_content', " . ($image_path ? "'$image_path'" : "NULL") . ")";

if ($con->query($sqlQuery) === TRUE) {
    echo json_encode([
        "success" => true,
        "message" => "리뷰 작성 성공!"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "리뷰 작성 실패: {$con->error}"
    ]);
}
