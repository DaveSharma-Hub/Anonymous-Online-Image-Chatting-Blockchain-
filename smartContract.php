<?php
include "connect.php";

    
    $id= $_POST['id'];
    $name =$_POST['comment'];

    $stmt=$db->prepare("select * from chat");
    $stmt->execute();
    $stmt_result=$stmt->get_result();
    $appendHash="";
    $chatID=-1;
    while($data = $stmt_result->fetch_array()){
        $appendHash = $appendHash.$data['currentHash'];
        if($data['chatID']>$chatID){
            $chatID=$data['chatID'];
        }
    }
    if($appendHash!="" && $chatID!=-1){
        $totalHash = hash('sha256',$appendHash.$name);

        $stmt=$db->prepare("insert into smartcontract(chatID,totalHash,name) values(?,?,?)");
        $stmt->bind_param("iss",$chatID,$totalHash,$name);
        $stmt->execute();

    }
    header("Location:main.php");
?>