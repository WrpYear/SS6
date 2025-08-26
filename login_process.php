<?php
if(!isset($_SESSION)){
    session_start();
}
    
    $username = '';
    $password = '';

    if (!isset($_POST['username']) || $_POST['username'] == '') {
        $response = array('ret' => '101', 'message' => 'invalid value username');
        echo json_encode($response);
        exit;
    } else {
        $username = $_POST['username'];
    }
    if (!isset($_POST['password']) || $_POST['password'] == '') {
        $response = array('ret' => '102', 'message' => 'invalid value password');
        echo json_encode($response);
        exit;
    } else {
        $password = $_POST['password'];
    }
    
    include_once('include/WebConfig.php');

    $web = new MySQLClass();
    $web->Connect2Web();
    if(empty($web->Connect)){
        echo "Cannot cennect database";
        exit;
    }
    $web->dbname(WebDB);
    $password = md5($password);
    $strSQL = "SELECT * FROM user_account WHERE username='{$username}' AND password='{$password}';";
        $rs = $web->select($strSQL);
        if(count($rs) == 1){
            if($rs[0]->status=='1'){
                $_SESSION['username'] = $rs[0]->username;
                $_SESSION['uid'] = $rs[0]->id;
                $response = array("ret"=>'200','message'=>'success');
                echo json_encode($response);
            }else{
                $response = array("ret"=>'201','message'=>'User not active');
            echo json_encode($response);
            }
            
        }else{
            $response = array("ret"=>'102','message'=>'This user not exist.');
            echo json_encode($response); 
        }


            


    // if ($result) {
    //     echo json_encode(['ret' => '200', 'message' => 'Update success']);
    // } else {
    //     echo json_encode(['ret' => '100', 'message' => 'Update unsuccess']);
    // }

    $web->closedb();

?>