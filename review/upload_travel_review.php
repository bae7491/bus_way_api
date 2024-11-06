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

// SQL 쿼리 작성: 닉네임 검색 쿼리 작성
$nickNameSqlQuery = "SELECT nickName FROM user WHERE email = '$email'";
$nickNameResult = $con->query($nickNameSqlQuery);

// 닉네임 조회가 성공하고 결과가 있는지 확인
if ($nickNameResult && $nickNameResult->num_rows > 0) {
    $row = $nickNameResult->fetch_assoc();
    $nickName = $row['nickName']; // 닉네임을 변수에 저장

    // 이미지가 있는 경우와 없는 경우에 따라 쿼리를 달리 작성
    $sqlQuery = "INSERT INTO travel_review (email, nickName, content_id, review_rate, review_content, review_image) 
                 VALUES ('$email', '$nickName', '$content_id', '$review_rate', '$review_content', " . ($image_path ? "'$image_path'" : "NULL") . ")";
    
    // INSERT 쿼리 실행
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
} else {
    // 닉네임을 찾지 못한 경우
    echo json_encode([
        "success" => false,
        "message" => "사용자의 닉네임을 찾을 수 없습니다."
    ]);
}
