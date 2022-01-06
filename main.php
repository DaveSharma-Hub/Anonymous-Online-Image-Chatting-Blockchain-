<?php
    include "connect.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <link rel="stylesheet" href="main.css">
    <title>ImageChain</title>
</head>
<body>
    <div class="container" id="container">
        <form action="post.php" method="POST" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="personname" required>
            <input type="file" id="myFile" name="filename" required>
            <input type="submit" id="submit" name="submit">
        </form>

        <p>Verification</p>
        <div id="miner">
            <p class="mine"><a id="mine" href="mine.php">Mine Image Chain PoW</a></p>
            <!-- <p class="mine"><a id="mine" href="mine.php">Mine Image Chain PoS</a></p> -->

        </div>

        <div id="verify">
            <form action='smartCheck.php' method='POST' id="smart">
                <input type='hidden' name='id' value='".$row['id']."'/>
                <label for='smartHash'>Smart Hash: </label>
                <input type='text' id='smartHash' name='comment' required>
                <label for='chatID'>Chat ID: </label>
                <input type='text' id='chatID' name='chatID' required>
                <label for='fName'>Name: </label>
                <input type='text' id='fName' name='name' required>
                <input type='submit' id='SmartContract' name='SmartContract' value='Verify Smart Contract'>
            </form>
        </div>

        <!-- <form autocomplete="off" action="" method="POST">
            <div class="autocomplete" style="width:100%; text-align:center;">
                <label for="myInput">Search:</label>
                <input id="myInput" type="text" name="friends" required>
                <input type="submit" value="Search" id="search">
            </div>
        </form>
        <p class="clear"><a id="clear" href="#">Clear Search</a></p> -->

    </div>
    
    <div class="pool" id="pool">
        <h1>Decentralized Images</h1>
            <?php
        // Get images from the database
        $query = $db->query("SELECT * FROM images");

        if($query->num_rows > 0){
            while($row = $query->fetch_assoc()){
                $imageURL = 'uploads/'.$row["file_name"];
                
                if($row['id']!=3){
                    
                        echo "<div id='outer'>";     
                        echo "<img src='". $imageURL."' alt='' width='250px' />";
                        echo "<p>".$row['file_name']." Author: ".$row['name']."</p>";
                        echo "<p>Comments:</p>";
                        echo "<div class='displayComments'>";

                        $message= $db->prepare("select * from chat");
                        $message->execute();
                        $message_result = $message->get_result();
                        while($chat = $message_result->fetch_array()){
                        
                        if($chat['imageID']==$row['id'] && $chat['chatID']!=10){
                            echo "<p>Anonymous&nbsp;&nbsp;&nbsp;".$chat['chatMessage']."</p>";
                            }
                        }
                        echo"</div>";
                        echo "<form action='comments.php' method='POST'>
                                <label for='name'>Comment</label>
                                <input type='hidden' name='id' value='".$row['id']."'/>
                                <input type='text' id='name' name='comment' required>
                                <input type='submit' id='submit' name='Comment' value='Send'>
                            </form>";
                            echo "<form action='smartContract.php' method='POST'>
                                <input type='hidden' name='id' value='".$row['id']."'/>
                                <label for='name'>Name: </label>
                                <input type='text' id='name' name='comment' required>
                                <input type='submit' id='SmartContract' name='SmartContract' value='SmartContract Download'>
                            </form>";
                        echo "</div>";
                        
                }
         }
        }else{
            echo "<p>No image(s) found...</p>";
        }
         ?>
    </div>

</body>
</html>