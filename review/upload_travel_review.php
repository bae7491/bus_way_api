<?php
// 작성한 리뷰를 DB에 저장하도록 요청

include "../connection.php";

$image = $_FILES['image']['name'];
$imagePath = "../images/uploads/{$image}";
$tmp_name = $_FILES['image']['tmp_name'];

move_uploaded_file($tmp_name, $imagePath);

$email = $_POST['email'];
$review_rate = $_POST['review_rate'];
$review_content = $_POST['review_content'];

// SQL 쿼리 작성
$sqlQuery = "INSERT INTO travel_review (email, review_rate, review_content, review_image) VALUES ('$email', '$review_rate', '$review_content', '$image')";

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
