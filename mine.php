<?php

    include "connect.php";

    $stmt=$db->prepare("select * from images");
    $stmt->execute();
    $stmt_result=$stmt->get_result();
    while($data=$stmt_result->fetch_array()){
        $prevHash = $data['previousHash'];
        $currentHash = $data['currentHash'];
        $nonce = $data['nonce'];
        $longTime = $data['longTime'];
        $name = $data['name'];
        $fileName = $data['file_name'];

            $currentHashTest = hash('sha256',$prevHash.$name.$longTime.$nonce.$fileName);

            if($currentHash!=$currentHashTest){
                header("Location:error/errorMine.php");
            }
    }
    
    header("Location:error/noErrorMine.php");

?>