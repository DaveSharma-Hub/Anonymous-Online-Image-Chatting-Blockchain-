
<?php
// $target_dir = "uploads/";
// $target_file = $target_dir . basename($_FILES["filename"]["name"]);
// $uploadOk = 1;
// $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
//if(isset($_POST["submit"])) {
//   $check = getimagesize($_FILES["filename"]["tmp_name"]);
//   if($check !== false) {
//     echo "File is an image - " . $check["mime"] . ".";
//     $uploadOk = 1;
//   } else {
//     echo "File is not an image.";
//     $uploadOk = 0;
//   }
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

                    // $maxID=-1;
                    // $chat = $db->prepare("select * from images");
                    // $chat->execute();
                    // $chat_result=$chat->get_result();
                    // while($id=$chat_result->fetch_array()){
                    //     if($id['id']>$maxID){
                    //         $maxID=$id['id'];
                    //     }
                    // }
                    // if($maxID!=-1){
                    //     $null="";
                    //     $chat = $db->prepare("insert into chat(message,imageID) values(?,?)");
                    //     $chat ->bind_param("si",$null,$maxID);
                    // }
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
