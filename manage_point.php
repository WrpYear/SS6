<?php

//function addPoint()

// $uid = 68;
// $order_id = "de_001s0012";
// $point = 10500;
// $point_type = 1;
// $comment = "TEST API x1";
// $action_by = "API TEST File";

// echo "<pre>";
// // addPoint($uid, $order_id, $point, $point_type, $comment, $action_by) ;
// deductPoint($uid, $order_id, $point, $point_type, $comment, $action_by);
// echo "<hr>";
// checkPoint($uid);

function addPoint($uid, $order_id, $point, $point_type, $comment, $action_by) {
    // if (!isset($_POST['id']) || $_POST['id'] == '') {
    //     echo json_encode(['ret' => '106', 'message' => 'invalid value id']);
    //     exit;
    // } else {
    //     $uid = $_POST['id'];
    //     if (!preg_match('/^[0-9]+$/', $uid)) {
    //         echo json_encode(['ret' => '110', 'message' => "Only number is allowed!"]);
    //         exit;
    //     }
    // }

    // if (!isset($_POST['order_id']) || $_POST['order_id'] == '') {
    //     echo json_encode(['ret' => '107', 'message' => 'invalid value order_id']);
    //     exit;
    // } else {
    //     $order_id = $_POST['order_id'];
    //     if (!preg_match("/^[a-zA-Z\d]{6,20}$/", $order_id)) {
    //         echo json_encode(['ret' => '111', 'message' => "invalid order_id format"]);
    //         exit;
    //     }
    // }

    // if (!isset($_POST['point']) || $_POST['point'] == '') {
    //     echo json_encode(['ret' => '108', 'message' => 'invalid value point']);
    //     exit;
    // } else {
    //     $point = intval($_POST['point']);
    //     if ($point <= 0) {
    //         echo json_encode(['ret' => '112', 'message' => "invalid point format"]);
    //         exit;
    //     }
    // }

    // if (!isset($_POST['point_type']) || $_POST['point_type'] == '') {
    //     echo json_encode(['ret' => '109', 'message' => 'invalid value point_type']);
    //     exit;
    // } else {
    //     $point_type = $_POST['point_type'];
    //     if (!in_array($point_type, ['1', '2'])) {
    //         echo json_encode(['ret' => '113', 'message' => "Point type allowed only 1(point) or 2(point_free)."]);
    //         exit;
    //     }
    // }

    // $comment = isset($_POST['comment']) ? $_POST['comment'] : '';
    // $action_by = isset($_POST['action_by']) ? $_POST['action_by'] : '';

    include_once('include/WebConfig.php');

    $web = new MySQLClass();
    $web->Connect2Web();
    if (empty($web->Connect)) {
        echo json_encode(['ret' => '999', 'message' => 'Cannot connect database']);
        exit;
    }

    $web->dbname(WebDB);
    $rs = $web->select("SELECT username, id FROM user_account WHERE id='{$uid}';");

    if (count($rs) != 1) {
        echo json_encode(['ret' => '101', 'message' => 'User not found!']);
        $web->closedb();
        exit;
    }

    $pointColumn = $point_type == '1' ? 'point' : 'point_free';
    
    $rswallet = $web->select("SELECT point, point_free FROM user_wallet WHERE user_id = '{$uid}';");
    $old_point = 0;
    $old_point_free = 0;
    
    if (count($rswallet) != 1) {
        // Create wallet
        $web->execute("INSERT INTO `user_wallet` (`user_id`) VALUES ('{$uid}');");
    } else {
        $old_point = $rswallet[0]->point;
        $old_point_free = $rswallet[0]->point_free;
    }
    
    $current_point = $point_type == '1' ? $old_point + $point : $old_point;
    $current_point_free = $point_type == '2' ? $old_point_free + $point : $old_point_free;

    // Check Duplicate of order_id
    $rsbalancelog = $web->select("SELECT id FROM user_balancelog WHERE order_id = '{$order_id}';");
    if (count($rsbalancelog) == 1) {
        echo json_encode(['ret' => '105', 'message' => 'Order_id is duplicated!']);
        $web->closedb();
        exit;
    }

    // Insert log พร้อมค่า current_point
    $insertBalanceLogSQL = "INSERT INTO `user_balancelog` 
        (`user_id`, `order_id`, `type`, `{$pointColumn}`, `current_point`, `current_point_free`, `comment`, `action_by`) 
        VALUES 
        ('{$uid}', '{$order_id}', '1', '{$point}', '{$current_point}', '{$current_point_free}', '{$comment}', '{$action_by}');";
    
    $insertBalanceLog = $web->execute($insertBalanceLogSQL);
    if (!$insertBalanceLog) {
        echo json_encode(['ret' => '102', 'message' => 'Insert balancelog fail!']);
        $web->closedb();
        exit;
    }


    // Update wallet
    $updateWalletSQL = "UPDATE `user_wallet` SET `{$pointColumn}` = `{$pointColumn}` + '{$point}' WHERE `user_id` = '{$uid}';";
    $updateWallet = $web->execute($updateWalletSQL);
    if (!$updateWallet) {
        echo json_encode(['ret' => '104', 'message' => 'Update wallet fail!']);
        $web->closedb();
        exit;
    }
    
    // Read updated value
    $rsUpdated = $web->select("SELECT point, point_free FROM user_wallet WHERE user_id = '{$uid}';");
    $now_point = $rsUpdated[0]->point;
    $now_point_free = $rsUpdated[0]->point_free;
    
    

    $data['point_before'] = ['point' => $old_point, 'point_free' => $old_point_free];
    $data['point_after'] = ['point' => $now_point, 'point_free' => $now_point_free];

    echo json_encode([
        'ret' => '200',
        'message' => 'Point updated successfully',
        'data' => $data
    ]);

    $web->closedb();
}
// addPoint();
// $res = addPoint(1, 'add_000001', 100, '1', '', '');

//function deducePoint()
function deductPoint($uid, $order_id, $point, $point_type, $comment, $action_by) {

    // if (!isset($_POST['id']) || $_POST['id'] == '') {
    //     echo json_encode(['ret' => '106', 'message' => 'invalid value id']);
    //     exit;
    // } else {
    //     $uid = $_POST['id'];
    //     if (!preg_match('/^[0-9]+$/', $uid)) {
    //         echo json_encode(['ret' => '110', 'message' => "Only number is allowed!"]);
    //         exit;
    //     }
    // }

    // if (!isset($_POST['order_id']) || $_POST['order_id'] == '') {
    //     echo json_encode(['ret' => '107', 'message' => 'invalid value order_id']);
    //     exit;
    // } else {
    //     $order_id = $_POST['order_id'];
    //     if (!preg_match("/^[a-zA-Z\d]{6,20}$/", $order_id)) {
    //         echo json_encode(['ret' => '111', 'message' => "invalid order_id format"]);
    //         exit;
    //     }
    // }

    // if (!isset($_POST['point']) || $_POST['point'] == '') {
    //     echo json_encode(['ret' => '108', 'message' => 'invalid value point']);
    //     exit;
    // } else {
    //     $point = intval($_POST['point']);
    //     if ($point <= 0) {
    //         echo json_encode(['ret' => '112', 'message' => "invalid point format"]);
    //         exit;
    //     }
    // }

    // if (!isset($_POST['point_type']) || $_POST['point_type'] == '') {
    //     echo json_encode(['ret' => '109', 'message' => 'invalid value point_type']);
    //     exit;
    // } else {
    //     $point_type = $_POST['point_type'];
    //     if (!in_array($point_type, ['1', '2'])) {
    //         echo json_encode(['ret' => '113', 'message' => "Point type allowed only 1(point) or 2(point_free)."]);
    //         exit;
    //     }
    // }

    // $comment = isset($_POST['comment']) ? $_POST['comment'] : '';
    // $action_by = isset($_POST['action_by']) ? $_POST['action_by'] : '';

    include_once('include/WebConfig.php');

    $web = new MySQLClass();
    $web->Connect2Web();
    if (empty($web->Connect)) {
        echo json_encode(['ret' => '999', 'message' => 'Cannot connect database']);
        exit;
    }

    $web->dbname(WebDB);
    $rs = $web->select("SELECT username, id FROM user_account WHERE id='{$uid}';");

    if (count($rs) != 1) {
        echo json_encode(['ret' => '101', 'message' => 'User not found!']);
        $web->closedb();
        exit;
    }

    $pointColumn = $point_type == '1' ? 'point' : 'point_free';
    
    // Check Duplicate of order_id
    $rsbalancelog = $web->select("SELECT id FROM user_balancelog WHERE order_id = '{$order_id}';");
    if (count($rsbalancelog) == 1) {
        echo json_encode(['ret' => '105', 'message' => 'Order_id is duplicated!']);
        $web->closedb();
        exit;
    }
    
    // ดึงคะแนนก่อนหัก
    $rswallet = $web->select("SELECT point, point_free FROM user_wallet WHERE user_id = '{$uid}';");
    $old_point = 0;
    $old_point_free = 0;
    
    if (count($rswallet) != 1) {
        echo json_encode(['ret' => '121', 'message' => 'Not enough point']);
        $web->closedb();
        exit;
    } else {
        // เช็คว่า point พอหรือไม่
        $old_point = $rswallet[0]->point;
        $old_point_free = $rswallet[0]->point_free;
        $current = ($point_type == '1') ? $old_point : $old_point_free;
        if ($point > $current) {
            $point_type = '2';
            $pointColumn = $point_type == '1' ? 'point' : 'point_free';
            $current = ($point_type == '1') ? $old_point : $old_point_free;   
            if($point > $current) {                
                $point_type = '3';
                $pointColumn = 'point, point_free';
                $current = $old_point + $old_point_free;   
                if($point > $current) {                
                echo json_encode(['ret' => '121', 'message' => 'Not enough point']);
                $web->closedb();
                exit;
                }
            }      
        }
        
    }
    if (in_array($point_type, ['1', '2'])){
        $current_point = $point_type == '1' ? $old_point - $point : $old_point;
        $current_point_free = $point_type == '2' ? $old_point_free - $point : $old_point_free;
        // Insert log พร้อมค่า current_point
        $insertBalanceLogSQL = "INSERT INTO `user_balancelog` (`user_id`, `order_id`, `type`, `{$pointColumn}`, `current_point`, `current_point_free`, `comment`, `action_by`) VALUES ('{$uid}', '{$order_id}', '2', '{$point}', '{$current_point}', '{$current_point_free}', '{$comment}', '{$action_by}');";
        $insertBalanceLog = $web->execute($insertBalanceLogSQL);
        if (!$insertBalanceLog) {
            echo json_encode(['ret' => '102', 'message' => 'Insert balancelog fail!']);
            $web->closedb();
            exit;
        }
    } else {
        $used_point = $point-$old_point_free;
        $used_point_free = $old_point_free;

        $current_point = $current - $point;
        $current_point_free = 0;

    $insertBalanceLogSQL = "INSERT INTO `user_balancelog` (`user_id`, `order_id`, `type`, `point`, `point_free`, `current_point`, `current_point_free`, `comment`, `action_by`) VALUES ('{$uid}', '{$order_id}', '2', '{$used_point}', '{$used_point_free}', '{$current_point}', '{$current_point_free}', '{$comment}', '{$action_by}');";
    $insertBalanceLog = $web->execute($insertBalanceLogSQL);
    if (!$insertBalanceLog) {
            echo json_encode(['ret' => '103', 'message' => 'Insert balancelog fail!']);
            $web->closedb();
            exit;
        }
    }
    
    
    // หักคะแนน
    $updateWalletSQL = "UPDATE `user_wallet` SET `point` = '{$current_point}', `point_free` = '{$current_point_free}'  WHERE `user_id` = '{$uid}';";
    $updateWallet = $web->execute($updateWalletSQL);
    if (!$updateWallet) {
        echo json_encode(['ret' => '104', 'message' => 'Update wallet fail!']);
        $web->closedb();
        exit;
    }
    
    // ค่าหลังหัก
    $rsUpdated = $web->select("SELECT point, point_free FROM user_wallet WHERE user_id = '{$uid}';");
    $now_point = $rsUpdated[0]->point;
    $now_point_free = $rsUpdated[0]->point_free;

    $data['point_before'] = ['point' => $old_point, 'point_free' => $old_point_free];
    $data['point_after'] = ['point' => $now_point, 'point_free' => $now_point_free];

    echo json_encode([
        'ret' => '200',
        'message' => 'Point deducted successfully',
        'data' => $data
    ]);

    $web->closedb();
}

// deductPoint();
// $res = deductPoint(1, 'deduct_000002', 100, '2', '', '');


//function checkPoint()
function checkPoint($uid) {
    // if (!isset($_POST['id']) || $_POST['id'] == '') {
    //     echo json_encode(['ret' => '106', 'message' => 'invalid value id']);
    //     exit;
    // } else {
    //     $uid = $_POST['id'];
    //     if (!preg_match('/^[0-9]+$/', $uid)) {
    //         echo json_encode(['ret' => '110', 'message' => "Only number is allowed!"]);
    //         exit;
    //     }
    // }

    include_once('include/WebConfig.php');

    $web = new MySQLClass();
    $web->Connect2Web();
    if (empty($web->Connect)) {
        echo json_encode(['ret' => '999', 'message' => 'Cannot connect database']);
        exit;
    }

    $web->dbname(WebDB);
    $rs = $web->select("SELECT id, username FROM user_account WHERE id='{$uid}';");

    if (count($rs) != 1) {
        echo json_encode(['ret' => '101', 'message' => 'User not found!']);
        $web->closedb();
        exit;
    } else {
        $rsWallet = $web->select("SELECT * FROM user_wallet WHERE user_id='{$uid}';");
        if (count($rsWallet) != 1) {
            return ['point' => 0, 'point_free' => 0];
        } else {
            return [
                'point' => $rsWallet[0]->point,
                'point_free' => $rsWallet[0]->point_free
            ];
        }
    }
}
// checkPoint();
?>