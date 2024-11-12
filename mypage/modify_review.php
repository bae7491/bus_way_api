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
$image_path = isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK 
    ? ($_FILES['image']['name']) 
    : (file_exists("../images/uploads/{$original_image_path}") 
        ? $original_image_path 
        : null);

if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['image']['tmp_name'];
    $imagePath = "../images/uploads/{$_FILES['image']['name']}";
}


// SQL 쿼리 작성: 닉네임 검색 쿼리 작성
$nickNameSqlQuery = "SELECT nickName FROM user WHERE email = '$email'";
$nickNameResult = $con->query($nickNameSqlQuery);

// 닉네임 조회가 성공하고 결과가 있는지 확인
if ($nickNameResult && $nickNameResult->num_rows > 0) {
  $row = $nickNameResult->fetch_assoc();
  $nickName = $row['nickName']; // 닉네임을 변수에 저장
  // 이미지가 있는 경우와 없는 경우에 따라 쿼리를 달리 작성
  $sqlQuery = "UPDATE travel_review SET review_rate = '$review_rate',review_content = '$review_content', review_image = " . ($image_path ?"'$image_path'" : "NULL") . " WHERE review_id = '$review_id'";
  
  // INSERT 쿼리 실행
  if ($con->query($sqlQuery) === TRUE) {
    // 새로운 이미지로 수정을 했을 시, 새 이미지 저장 및 수정 전의 이미지 삭제
    if ($original_image_path != null && $original_image_path!=$image_path) {
      // 수정 후 이미지 저장
      move_uploaded_file($tmp_name, $imagePath);

      // 수정 전 이미지 서버에서 삭제 
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
