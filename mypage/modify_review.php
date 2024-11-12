<?php
// 수정된 후기를 DB에 업데이트하도록 요청

include "../connection.php";

$review_id = $_POST['review_id'];
$email = $_POST['email'];
$content_id = $_POST['content_id'];
$title = $_POST['title'];
$review_rate = $_POST['review_rate'];
$review_content = $_POST['review_content'];
$original_image_path = $_POST['original_image_path'];

// 이미지가 있는지 확인
if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
  $image = $_FILES['image']['name'];
  $imagePath = "../images/uploads/{$image}";
  $tmp_name = $_FILES['image']['tmp_name'];

  $image_path = $image;
} else {
  $image_path = null;
}

// SQL 쿼리 작성: 닉네임 검색 쿼리 작성
$nickNameSqlQuery = "SELECT nickName FROM user WHERE email = '$email'";
$nickNameResult = $con->query($nickNameSqlQuery);

// 닉네임 조회가 성공하고 결과가 있는지 확인
if ($nickNameResult && $nickNameResult->num_rows > 0) {
    $row = $nickNameResult->fetch_assoc();
    $nickName = $row['nickName']; // 닉네임을 변수에 저장

    // 이미지가 있는 경우와 없는 경우에 따라 쿼리를 달리 작성
    $sqlQuery = "UPDATE travel_review SET review_rate = '$review_rate', review_content = '$review_content', review_image = " . ($image_path ? "'$image_path'" : "NULL") . " WHERE review_id = '$review_id'";
    
    // INSERT 쿼리 실행
    if ($con->query($sqlQuery) === TRUE) {
        // 수정 후 이미지 저장
        move_uploaded_file($tmp_name, $imagePath) 
          ? $image_path = $image 
          : $image_path = null;

        // 수정 전 이미지 서버에서 삭제
        if ($original_image_path != null) {
          unlink("../images/uploads/{$original_image_path}");
        }

        echo json_encode([
            "success" => true,
            "message" => "리뷰 수정 성공!"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "리뷰 수정 실패: {$con->error}"
        ]);
    }
} else {
    // 닉네임을 찾지 못한 경우
    echo json_encode([
        "success" => false,
        "message" => "사용자의 닉네임을 찾을 수 없습니다."
    ]);
}
