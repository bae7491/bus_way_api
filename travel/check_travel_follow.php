<?php
// 선택한 관광지가 DB에 저장되어 있는지 체크. 

include "../connection.php";

$email = $_POST["email"];
$contentId = $_POST["content_id"];


// query문
$sqlQuery = "SELECT * FROM travel_follow WHERE email = '$email' AND content_id = '$contentId'";

$resultQuery = $con -> query($sqlQuery);

// 선택한 관광지의 정보가 있으면, true 반환
if ($resultQuery -> num_rows > 0) {
  echo json_encode(["existTravelInfo" => true]);
} else {
  echo json_encode(["existTravelInfo" => false]);
}
