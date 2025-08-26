<?php
include_once('include/WebConfig.php'); // เชื่อมต่อ DB ตามที่คุณใช้

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = intval($_POST['status']);

    $web = new MySQLClass();
    $web->Connect2Web();
    if (!$web->Connect) {
        http_response_code(500);
        echo "Database connection failed";
        exit;
    }

    $web->dbname(WebDB);
    $stmt = $web->Connect->prepare("UPDATE user_account SET status = ?, update_time = NOW() WHERE id = ?");
    $stmt->bind_param("ii", $status, $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        http_response_code(500);
        echo "failed";
    }

    $stmt->close();
    $web->Connect->close();
} else {
    http_response_code(400);
    echo "Invalid input";
}
?>
