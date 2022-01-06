<?php

        // Database configuration
    $Host     = "localhost";
    $Username = "dave(2)";
    $Password = "ensf409";
    $Name     = "imagechain";

    // Create database connection
    $db = new mysqli($Host, $Username, $Password, $Name);

    // Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
?>