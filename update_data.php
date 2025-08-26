<?php

    $id = '';
    $name = '';
    $lastname = '';
    $email = '';
    $phone = '';
    $address = '';

    if (!isset($_POST['id']) || $_POST['id'] == '') {
        $response = array('ret' => '101', 'message' => 'invalid value id');
        echo json_encode($response);
        exit;
    } else {
        $id = $_POST['id'];
    }
    if (!isset($_POST['name']) || $_POST['name'] == '') {
        $response = array('ret' => '102', 'message' => 'invalid value name');
        echo json_encode($response);
        exit;
    } else {
        $name = $_POST['name'];
    }
    if (!isset($_POST['lastname']) || $_POST['lastname'] == '') {
        $response = array('ret' => '103', 'message' => 'invalid value lastname');
        echo json_encode($response);
        exit;
    } else {
        $lastname = $_POST['lastname'];
    }
    if (!isset($_POST['email']) || $_POST['email'] == '') {
        $response = array('ret' => '104', 'message' => 'invalid value email');
        echo json_encode($response);
        exit;
    } else {
        $email = $_POST['email'];
    }
    if (!isset($_POST['phone']) || $_POST['phone'] == '') {
        $response = array('ret' => '105', 'message' => 'invalid value phone');
        echo json_encode($response);
        exit;
    } else {
        $phone = $_POST['phone'];
    }
    if (!isset($_POST['address']) || $_POST['address'] == '') {
        $response = array('ret' => '106', 'message' => 'invalid value address');
        echo json_encode($response);
        exit;
    } else {
        $address = $_POST['address'];
    }
    
    include_once('include/WebConfig.php');

    $web = new MySQLClass();
    $web->Connect2Web();
    if(empty($web->Connect)){
        echo "Cannot cennect database";
        exit;
    }
    $web->dbname(WebDB);

    // // ตรวจสอบว่า ID มีอยู่หรือไม่
    // $sql = "SELECT * FROM user_account WHERE id = '{$id}'";
    // $user = $web->select($sql);
    
    // if (count($user) != 1) {
    //     echo json_encode(['ret' => '100', 'message' => "No ID: '{$id}'"]);
    //     $web->closedb();
    //     exit;
    // }

    // // ตรวจสอบว่า email หรือ phone ชนกับ user อื่นหรือไม่
    // $sql_email_check = "SELECT id FROM user_account WHERE (email = '{$email}') AND id != '{$id}'";
    // $duplicate_email = $web->select($sql_email_check);
    // $sql_phone_check = "SELECT id FROM user_account WHERE (phone = '{$phone}') AND id != '{$id}'";
    // $duplicate_phone = $web->select($sql_phone_check);
    
    // if (count($duplicate_phone) > 0 && count($duplicate_email) > 0) {
    //     $update = "UPDATE user_account SET 
    //             name = '{$name}', 
    //             lastname = '{$lastname}', 
    //             address = '{$address}', 
    //             update_time = NOW() 
    //         WHERE id = '{$id}'";
    
    // $result = $web->execute($update);
    //     echo json_encode(['ret' => '109', 'message' => 'email and phone number already used']);
    //     $web->closedb();
    //     exit;
    // }

    // if (count($duplicate_email) > 0) {
    //     $update = "UPDATE user_account SET 
    //             name = '{$name}', 
    //             lastname = '{$lastname}', 
    //             phone = '{$phone}', 
    //             address = '{$address}', 
    //             update_time = NOW() 
    //         WHERE id = '{$id}'";

    // $result = $web->execute($update);
    //     echo json_encode(['ret' => '107', 'message' => 'email already used']);
    //     $web->closedb();
    //     exit;
    // }
    

    // if (count($duplicate_phone) > 0) {
    //     $update = "UPDATE user_account SET 
    //             name = '{$name}', 
    //             lastname = '{$lastname}', 
    //             email = '{$email}', 
    //             address = '{$address}', 
    //             update_time = NOW() 
    //         WHERE id = '{$id}'";

    // $result = $web->execute($update);
    //     echo json_encode(['ret' => '108', 'message' => 'phone number already used']);
    //     $web->closedb();
    //     exit;
    // }

    // // อัปเดตข้อมูล
    // $update = "UPDATE user_account SET 
    //             name = '{$name}', 
    //             lastname = '{$lastname}', 
    //             email = '{$email}', 
    //             phone = '{$phone}', 
    //             address = '{$address}', 
    //             update_time = NOW() 
    //         WHERE id = '{$id}'";

    $strSQL = "SELECT name,lastname,email,phone,address FROM user_account WHERE id='{$id}';";


        $rs = $web->select($strSQL);
        if(count($rs) == 1){
            $dataUpadte = "";
            $colSelect = "";
            $is_update = FALSE;
            if($rs[0]->name != $name && preg_match("/^[ก-๙a-zA-Z\s'-]+$/u",$name)){
                $dataUpadte .= " name='{$name}' "; 
                $colSelect .= " name ";
                $is_update = TRUE;
            }
            if($rs[0]->lastname != $lastname && preg_match("/^[ก-๙a-zA-Z\s'-]+$/u",$lastname)){
                if($is_update){
                    $addComma = " , ";
                }else{
                    $addComma = "";
                }
                $dataUpadte .=  $addComma." lastname='{$lastname}' "; 
                $colSelect .= $addComma." lastname ";
                $is_update = TRUE;
            }
            if($rs[0]->email != $email && filter_var($email, FILTER_VALIDATE_EMAIL)){
                $sqlChk = "SELECT email FROM user_account WHERE email='{$email}';";
                $rsChk = $web->select($sqlChk);
                if(count($rsChk)==0){
                    if($is_update){
                        $addComma = " , ";
                    }else{
                        $addComma = "";
                    }
                    $dataUpadte .= $addComma." email='{$email}' "; 
                    $colSelect .= $addComma." email ";
                    $is_update = TRUE;
                }
                
            }
            if($rs[0]->phone != $phone && preg_match('/[0-9]{10,}/',$phone)){
                $sqlChk = "SELECT phone FROM user_account WHERE phone='{$phone}';";
                $rsChk = $web->select($sqlChk);
                if(count($rsChk)==0){
                    if($is_update){
                        $addComma = " , ";
                    }else{
                        $addComma = "";
                    }
                    $dataUpadte .= $addComma." phone='{$phone}' "; 
                    $colSelect .= $addComma." phone ";
                    $is_update = TRUE;
                }
            }
            if($rs[0]->address != $address){
                if($is_update){
                    $addComma = " , ";
                }else{
                    $addComma = "";
                }
                $dataUpadte .= $addComma." address='{$address}' "; 
                $colSelect .= $addComma." address ";
                $is_update = TRUE;
            }
            if($is_update){
                $strSQL = "UPDATE user_account SET {$dataUpadte} WHERE  id='{$id}';";
                $rs = $web->execute($strSQL);
                
                if(!$rs){
                    $response = array("ret"=>'201','message'=>'Update fail');
                    echo json_encode($response);
                    $web->closedb();
                    exit;
                }
                $sqlUpdateCol = "SELECT {$colSelect} FROM user_account WHERE id='{$id}';";
                $rsUpdateCol = $web->select($sqlUpdateCol);
            } else {
                $rsUpdateCol = array();
            } 
            $response = array("ret"=>'101','message'=>'success','update_data'=> $rsUpdateCol);
            echo json_encode($response);
        }else{
            $response = array("ret"=>'202','message'=>'Record not found');
            echo json_encode($response); 
        }


            


    // if ($result) {
    //     echo json_encode(['ret' => '200', 'message' => 'Update success']);
    // } else {
    //     echo json_encode(['ret' => '100', 'message' => 'Update unsuccess']);
    // }

    $web->closedb();

?>