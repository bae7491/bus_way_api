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
$not_chagned = boolval($_POST['not_changed']);

// 이미지 처리 로직
// 기존의 이미지 (경로)가 있는 경우
if (file_exists("../images/uploads/{$original_image_path}")) {
  // 기존의 이미지가 있는 상태에서 새로운 이미지를 등록하는 경우
  if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $image_path = $_FILES['image']['name'];
  } else {
    $image_path = $not_chagned
    ? $image_path = $original_image_path // 기존의 이미지가 있는 상태를 유지하는 경우
    : $image_path = null;  // 기존의 이미지가 있는 상태에서 이미지를 지우는 경우
  }
}
// 기존의 이미지 (경로)가 없는 경우
else {
  $image_path = isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK
  ? $_FILES['image']['name'] // 기존의 이미지가 없는 상태에서 새로운 이미지 등록
  : null; // 기존의 이미지가 없는 상태를 유지
}

if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
  $tmp_name = $_FILES['image']['tmp_name'];
  $imagePath = "../images/uploads/{$image_path}";
}

// 닉네임 가져오기
$nickNameSqlQuery = "SELECT nickName FROM user WHERE email = '$email'";
$nickNameResult = $con->query($nickNameSqlQuery);

if ($nickNameResult && $nickNameResult->num_rows > 0) {
  $row = $nickNameResult->fetch_assoc();
  $nickName = $row['nickName'];

  // SQL 쿼리 작성
  $sqlQuery = "UPDATE travel_review 
               SET review_rate = '$review_rate',
                   review_content = '$review_content', 
                   review_image = " . ($image_path ? "'$image_path'" : "NULL") . " 
               WHERE review_id = '$review_id'";

  if ($con->query($sqlQuery) === TRUE) {
    // 기존의 이미지 (경로)가 있는 경우
    if (file_exists("../images/uploads/{$original_image_path}")) {
      // 기존의 이미지가 있는 상태에서 새로운 이미지를 등록한 경우
      if (isset($_FILES['image']) && $_FILES['image']['error'] ==     UPLOAD_ERR_OK) {
        // 새 이미지 업로드
        move_uploaded_file($tmp_name, $imagePath);
        // 기존 이미지 삭제
        unlink("../images/uploads/{$original_image_path}");
      } 
      else {
          // 기존의 이미지가 있는 상태에서 이미지를 지우는 경우
        if (!$not_chagned) {
          // 기존 이미지 삭제
          unlink("../images/uploads/{$original_image_path}");
        }
      }
    }
    // 기존의 이미지 (경로)가 없는 경우
    else {
      if($image_path = isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // 새 이미지 업로드
        move_uploaded_file($tmp_name, $imagePath);
      }
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
  echo json_encode([
    "success" => false,
    "message" => "사용자의 닉네임을 찾을 수 없습니다."
  ]);
}
