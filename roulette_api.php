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

$spin_type = isset($_POST['spin_type']) ? $_POST['spin_type'] : "";
if ($spin_type == '1') {
  
  $uid = $_SESSION['uid'];
    $order_id = "spin_" . uniqid(); // ป้องกันซ้ำ
    $cost_per_spin = 500;
    $point_type = '1'; 
    $comment = 'Spin cost';
    $action_by = 'Spin System';

    // หัก point ก่อน spin โดยเรียก deductPoint()
    ob_start(); // ซ่อน echo ใน deductPoint
    deductPoint($uid, $order_id, $cost_per_spin, $point_type, $comment, $action_by);
    $responseJson = ob_get_clean();
    
    $response = json_decode($responseJson, true);

    // if (!isset($response['ret']) || $response['ret'] !== '200') {
    //     echo json_encode([
    //         'ret' => '120',
    //         'message' => 'Not enough point or deduction failed',
    //         'error_data' => $response
    //     ]);
    //     exit;
    // }
    
    $reward_data = array(
        50 => 700,
        100 => 1700,
        200 => 2700,
        300 => 3700,
        400 => 5200,
        500 => 7392,
        600 => 8892,
        1000 => 9892,
        1500 => 9992,
        2000 => 9997,
        3000 => 9999,
        5000 => 10000
    );

  $range = rand(0, 10000);
  $sum = 0;
  $selected_point = 0;
  $index = 0;

  foreach ($reward_data as $point => $probability) {
    $sum = $probability;
    if ($range <= $sum) {
        $selected_point = $point;
        break;
    }
    $index++; // เพิ่ม index ไว้ใช้งานใน more_data 
    }

    $comment_add = 'Spin reward';
    $order_id_add = "spin_reward_" . uniqid(); //order_id กันซ้ำ
    $point_type_add = '1';

    ob_start(); // ซ่อน echo ของ addPoint
    addPoint($uid, $order_id_add, $selected_point, $point_type_add, $comment_add, $action_by);
    ob_end_clean();

    $current_points = checkPoint($uid);


    include_once('include/WebConfig.php');

    $web = new MySQLClass();
    $web->Connect2Web();
    if (empty($web->Connect)) {
        echo json_encode(['ret' => '999', 'message' => 'Cannot connect database']);
        exit;
    }

    $web->dbname(WebDB);

    $rs = $web->select("SELECT id FROM user_balancelog WHERE user_id='{$uid}' ORDER BY id DESC LIMIT 1;");
    if (count($rs) != 1) {
        echo json_encode(['ret' => '101', 'message' => 'User not found!']);
        $web->closedb();
        exit;
    }
    $log_id = $rs[0]->id;
    $insertPlayLogSQL ="INSERT INTO `play_log` (`user_id`, `log_id`, `spin_reward`) VALUES ('{$uid}', '{$log_id}', '{$selected_point}');";
    $insertPlayLog = $web->execute($insertPlayLogSQL);
    if (!$insertPlayLog) {
        echo json_encode(['ret' => '102', 'message' => 'Insert insert playlog fail!']);
        $web->closedb();
        exit;
    }
    
    

  $more_data = array(
        'index' => $index,
        'point' => $reward_data[$point],
        'point_display' => $selected_point,
        'range' => $range
    );

    $res = array(
        'ret' => '200',
        'message' => 'success',
        'more_data' => $more_data,
        'current_point' => $current_points
    );
    echo json_encode($res);


} else if ($spin_type=='test') {

$sumPointReword = 0;
$arrPropJackpot = array();
  for($x=0;$x<10000;$x++){
    $callTest = testProp();
      $sumPointReword = $sumPointReword + $callTest['reward_point'];
      if(!isset($arrPropJackpot[$callTest['propJackpot']])){
        $arrPropJackpot[$callTest['propJackpot']] = 0;
      }
      $arrPropJackpot[$callTest['propJackpot']] = $arrPropJackpot[$callTest['propJackpot']] + 1;
     }
     $userCost = (500*10000);
     echo "User cost : ".number_format($userCost);
     echo "<hr>";
     echo "System Reward : ".number_format($sumPointReword);
     echo "<hr>";
    
      echo "System Win/Loss : ".number_format($userCost - $sumPointReword);
       echo "<pre>";

     print_r($arrPropJackpot);

    
      
     
} else {
  $res = array(
    'ret' => '102', 
    'message'=>'invalid spin_type',
  );
  echo json_encode($res);
}


 function testProp(){
      $reward_data = array(
        50 => 700,
        100 => 1700,
        200 => 2700,
        300 => 3700,
        400 => 5200,
        500 => 7392,
        600 => 8892,
        1000 => 9892,
        1500 => 9992,
        2000 => 9997,
        3000 => 9999,
        5000 => 10000
    );
    

      $selected_point = 0;
      $range = rand(0, 10000);
      $sum = 0;

          foreach ($reward_data as $point => $probability) {
              $sum = $probability;
              if ($range <= $sum) {
                  $selected_point = $point;
                  $arrRes = array('reward_point'=>$selected_point,'propJackpot'=>$selected_point);
                  return $arrRes;
              }
          }
      

     }
?>
