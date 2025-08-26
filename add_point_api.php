<?php
session_start();

if (!isset($_SESSION['uid'])) {
    echo json_encode([
        'ret' => '401',
        'message' => 'User not logged in'
    ]);
    exit;
}

include_once('manage_point.php');

    $user_id = '';
    $username = '';
    $add_point = '';
    $point_type = '';
    

    if (!isset($_POST['user_id']) || $_POST['user_id'] == '') {
        if (!isset($_POST['username']) || $_POST['username'] == '') {
        $response = array('ret' => '101', 'message' => 'invalid value username and user_id');
        echo json_encode($response);
        exit;
    } else {
        $username = $_POST['username'];
        if (!preg_match("/^[a-zA-Z\d]{8,20}$/", $username)) {
            echo json_encode(['ret' => '104', 'message' => "Only number is allowed!"]);
            exit;
        }

    }
    } else {
        $user_id = $_POST['user_id'];
        if (!preg_match('/^[0-9]+$/', $user_id)) {
            echo json_encode(['ret' => '105', 'message' => "Only number is allowed!"]);
            exit;
        }
    }
    
    if (!isset($_POST['add_point']) || $_POST['add_point'] == '') {
        $response = array('ret' => '102', 'message' => 'invalid value add_point');
        echo json_encode($response);
        exit;
    } else {
        $add_point = $_POST['add_point'];
        if (!preg_match('/^[0-9]+$/', $add_point) && $add_point < 500) {
            echo json_encode(['ret' => '106', 'message' => "Point added < 500 is not allowed!"]);
            exit;
        }
    }

    if (!isset($_POST['point_type']) || $_POST['point_type'] == '') {
        $response = array('ret' => '103', 'message' => 'invalid value add_point');
        echo json_encode($response);
        exit;
    } else {
        $point_type = $_POST['point_type'];
        if (!in_array($point_type, ['1', '2'])) {
            echo json_encode(['ret' => '107', 'message' => "Point type allowed only 1(point) or 2(free point)."]);
            exit;
        }
    }
    
    include_once('include/WebConfig.php');

    $web = new MySQLClass();
    $web->Connect2Web();
    if(empty($web->Connect)){
        echo "Cannot cennect database";
        exit;
    }
    $web->dbname(WebDB);
    $strSQL = "SELECT * FROM user_account WHERE id='{$user_id}' OR username='{$username}';";
    $rs = $web->select($strSQL);
    if (count($rs)!=1) {
        $response = array("ret"=>'103','message'=>'User ID and Username not found!');
        echo json_encode($response);
        $web->closedb();
        exit;
    }
    $uid = $rs[0]->id;
    $order_id = "manual_added_" . uniqid();
    $point = $add_point;
    $comment = '';
    $action_by = 'Manually added';
    addPoint($uid, $order_id, $point, $point_type, $comment, $action_by);
?>