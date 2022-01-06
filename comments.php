<?php

    include "connect.php";
    $comment = $_POST['comment'];

    $imageID = $_POST['id'];

    $stmt=$db->prepare("select * from chat");
    $stmt->execute();
    $stmt_result = $stmt->get_result();
    
    $largest=-1;
    while($data =$stmt_result->fetch_array()){
        if($data['chatID']>$largest){
            $largest=$data['chatID'];
        }
    }
    if($largest!=-1){
        $stmt=$db->prepare("select * from chat where chatID=?");
        $stmt->bind_param("i",$largest);
        $stmt->execute();
        $stmt_result = $stmt->get_result();
        if($stmt_result->num_rows>0){
            $data = $stmt_result->fetch_assoc();
            $previousHash = $data['currentHash'];
            $nonce = intval($data['nonce'])+1;
            $longTime = time();

            $currentHash = hash('sha256',$comment.$imageID.$previousHash.$longTime.$nonce);
            $stmt=$db->prepare("insert into chat(chatMessage,imageID,previousHash,currentHash,longTime,nonce) value(?,?,?,?,?,?)");
            $stmt->bind_param("sissii",$comment,$imageID,$previousHash,$currentHash,$longTime,$nonce);
            $stmt->execute();
        }
    }
    header("Location:main.php");

?>