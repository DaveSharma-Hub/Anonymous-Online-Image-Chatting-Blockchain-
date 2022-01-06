
<?php

    include "connect.php";

$name = $_POST['personname'];

$stmt = $db->prepare("select * from images");
$stmt->execute();
$largest=-1;
$stmt_result=$stmt->get_result();
while($data =$stmt_result->fetch_array()){
    if($data['id']>$largest){
        $largest=$data['id'];
    }
}
if($largest!=-1){

    $stmt = $db->prepare("select * from images where id=?");
    $stmt->bind_param("i",$largest);
    $stmt->execute();
    $stmt_result=$stmt->get_result();
    if($stmt_result->num_rows>0){
        $getHash =$stmt_result->fetch_assoc();
        $prevHash = $getHash['currentHash']; 
        $nonce = intval($getHash['nonce'])+1;

        $longTime = time();


        $targetDir = "uploads/";
        $fileName = basename($_FILES["filename"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

        if(isset($_POST["submit"]) && !empty($_FILES["filename"]["name"])){
            // Allow certain file formats
            $allowTypes = array('jpg','png','jpeg','gif','pdf','JPG','JPEG');
            if(in_array($fileType, $allowTypes)){
                // Upload file to server
                if(move_uploaded_file($_FILES["filename"]["tmp_name"], $targetFilePath)){
                    // Insert image file name into database
                   
                    $currentHash = hash('sha256',$prevHash.$name.$longTime.$nonce.$fileName);
                    $now =date("Y-m-d H:i:s");
                    $insert = $db->prepare("INSERT into images (file_name, uploaded_on, name, currentHash,previousHash,longTime,nonce) VALUES (?,?,?,?,?,?,?)");
                    $insert->bind_param("sssssii",$fileName,$now,$name,$currentHash,$prevHash,$longTime,$nonce);
                    $insert->execute();

                   
                    if($insert){
                        $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
                    }else{
                        $statusMsg = "File upload failed, please try again.";
                    } 
                }else{
                    $statusMsg = "Sorry, there was an error uploading your file.";
                }
            }else{
                $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
            }
        }else{
            $statusMsg = 'Please select a file to upload.';
        }
    }
}
// Display status message
echo $statusMsg;
header("Location:main.php");

?>
