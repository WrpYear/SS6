<?php

    // echo '<pre>';
    // print_r($_POST);
    write_log("METHOD : ".$_SERVER['REQUEST_METHOD']."\nINPUT : ".json_encode($_POST));
    function write_log($log){
        //Something to write to txt log

        $date_log = date("Y-m-d H:i:s").PHP_EOL.
        "IP : ".get_client_ip().PHP_EOL.
        "DATA : ".$log.PHP_EOL."-------------------------".PHP_EOL;
        //Save string to log, use FILE_APPEND to append.
        file_put_contents('logs/log_'.date("Ymd").'.txt', $date_log, FILE_APPEND);
    }

    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    $fname = '';
    $lname = '';
    $username = '';
    $password = '';
    $confirm_password = '';
    $email = '';
    $phone = '';
    $address = '';
    $ref_code = '';

    if (!isset($_POST['fname']) || $_POST['fname'] == '') {
        $response = array('ret' => '102', 'message' => 'invalid value fname');
        echo json_encode($response);
        exit;
    } else {
        $fname = trim($_POST['fname']);
        if (!preg_match("/^[ก-๙a-zA-Z\s'-]+$/u",$fname)) {
        $response = array('ret' => '102', 'message' => "Only letters and white space allowed");
        echo json_encode($response);
        exit;
        }
    }
    if (!isset($_POST['lname']) || $_POST['lname'] == '') {
        $response = array('ret' => '103', 'message' => 'invalid value lname');
        echo json_encode($response);
        exit;
    } else {
        $lname = trim($_POST['lname']);
        if (!preg_match("/^[ก-๙a-zA-Z\s'-]+$/u",$lname)) {
        $response = array('ret' => '103', 'message' => "Only letters and white space allowed");
        echo json_encode($response);
        exit;
        }
    }
    if (!isset($_POST['username']) || $_POST['username'] == '') {
        $response = array('ret' => '104', 'message' => 'invalid value username');
        echo json_encode($response);
        exit;
    } else {
        $username = trim($_POST['username']);
        if (!preg_match("/^[a-zA-Z\d]{8,20}$/",$username)) {
        $response = array('ret' => '104', 'message' => "Username not in right format.");
        echo json_encode($response);
        exit;
        }
    }
    if (!isset($_POST['password']) || $_POST['password'] == '') {
        $response = array('ret' => '105', 'message' => 'invalid value password');
        echo json_encode($response);
        exit;
    } else {
        $password = trim($_POST['password']);
        if (!preg_match("/[A-Za-z0-9#?!@$%^&*-]{8,}$/",$password)) {
        $response = array('ret' => '105', 'message' => "Password not in right format.");
        echo json_encode($response);
        exit;
        }
    }
    if (!isset($_POST['confirm-password']) || $_POST['confirm-password'] == '') {
        $response = array('ret' => '106', 'message' => 'invalid value confirm-password');
        echo json_encode($response);
        exit;
    } else {
        $confirm_password = trim($_POST['confirm-password']);
        if ($confirm_password != $password) {
        $response = array('ret' => '106', 'message' => "Confirm-password not match with the password.");
        echo json_encode($response);
        exit;
        }
    }
    if (!isset($_POST['email']) || $_POST['email'] == '') {
        $response = array('ret' => '107', 'message' => 'invalid value email');
        echo json_encode($response);
        exit;
    } else {
        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $response = array('ret' => '107', 'message' => "invalid email format.");
        echo json_encode($response);
        exit;
        }
    }
    if (!isset($_POST['phone']) || $_POST['phone'] == '') {
        $response = array('ret' => '108', 'message' => 'invalid value phone');
        echo json_encode($response);
        exit;
    } else {
        $phone = trim($_POST['phone']);
        if (!preg_match('/[0-9]{10,}/', $phone)) {
        $response = array('ret' => '108', 'message' => "invalid phone format");
        echo json_encode($response);
        exit;
        }
    }
    if (!isset($_POST['address']) || $_POST['address'] == '') {
        $response = array('ret' => '109', 'message' => 'invalid value address');
        echo json_encode($response);
        exit;
    } else {
        $address = trim($_POST['address']);
    }
    if (!isset($_POST['ref_code']) || $_POST['ref_code'] == '') {

    } else {
        $ref_code = trim($_POST['ref_code']);
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
    $sqlChk = "SELECT username, email, phone FROM user_account WHERE username='{$username}' OR email='{$email}' OR phone='{$phone}';";
    $rsChk = $web->select($sqlChk);
    // echo '<pre>';
    // print_r($rsChk) ;
    if(count($rsChk)>0){

        for ($i=0;$i<count($rsChk);$i++){
            if($username == $rsChk[$i]->username){
                $response = json_encode(array("ret"=>"102", "message"=>"username is duplicated."));
                echo $response;
                exit;
            }
            if($email == $rsChk[$i]->email){
                $response = json_encode(array("ret"=>"103", "message"=>"email is duplicated."));
                echo $response;
                exit;
            }
            if($phone == $rsChk[$i]->phone){
                $response = json_encode(array("ret"=>"104", "message"=>"phone is duplicated."));
                echo $response;
                exit;
            }
        }
    }

    $insert = "INSERT INTO `user_account` (`name`, `lastname`, `username`, `password`, `email`, `phone`, `address`, `ref_code`) VALUES ('{$fname}', '{$lname}', '{$username}', '{$password}', '{$email}', '{$phone}', '{$address}', '{$ref_code}');";
    $rs = $web->execute($insert);
    if (!$rs) {
    $response = json_encode(array("ret"=>"102", "message"=>"unsuccess"));
    echo $response;
    exit;
    } else {
    $response = json_encode(array("ret"=>"200", "message"=>"success"));
    echo $response;
    exit;
}
$web->closedb();

    // echo 'fname: '.$fname.'<hr/>';
    // echo 'lname: '.$lname.'<hr/>';
    // echo 'username: '.$username.'<hr/>';
    // echo 'password: '.$password.'<hr/>';
    // echo 'confirm-password: '.$confirm_password.'<hr/>';
    // echo 'email: '.$email.'<hr/>';
    // echo 'phone: '.$phone.'<hr/>';
    // echo 'address: '.$address.'<hr/>';

    // if ($_POST['fname'] !== '' && $_POST['lname'] !== '' && $_POST['username'] !== '' && $_POST['password'] !== '' && $_POST['confirm-password'] !== '' && $_POST['email'] !== '' && $_POST['phone'] !== '' && $_POST['address'] !== '') {
    //     $response = array('ret' => '200', 'message' => 'success');
    //     echo json_encode($response);
    //     exit;
    // }
?>