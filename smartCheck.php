<?php
    include "connect.php";
    
    $chatID = $_POST['chatID'];
    $hash = $_POST['comment'];
    $firstName = $_POST['name'];

    $stmt=$db->prepare("select * from chat");
    $stmt->execute();
    $stmt_result=$stmt->get_result();
    $appendHash="";
    while($data = $stmt_result->fetch_array()){
        if($data['chatID']<=$chatID){
        $appendHash = $appendHash.$data['currentHash'];
        }
    }
    if($appendHash!="" ){
        

            $totalHash = hash('sha256',$appendHash.$firstName);
            if($totalHash==$hash){
                header("Location:error/noErrorSmart.php");
            }else{
                header("Location:error/errorSmart.php");
            }
        }
    

?>