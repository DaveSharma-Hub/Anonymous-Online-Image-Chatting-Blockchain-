<?php

        // Database configuration
    $Host     = "localhost";
    $Username = "";
    $Password = "";
    $Name     = "";

    // Create database connection
    $db = new mysqli($Host, $Username, $Password, $Name);

    // Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
?>
