<?php
	require("app.php");
	$conn = new mysqli('localhost', 'root', '','hr');
	$sql = "ALTER TABLE `users` ADD `barcode` BIGINT NOT NULL AFTER `id`;";
        if ($conn->query($sql) === TRUE) {
            echo "updated user table to add barcode <br><br>";
        } else {
            echo "Error creating barcode user table: " . $conn->error . "<br><br>";
        }
?>