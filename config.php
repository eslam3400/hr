<!-- <form method="post">
    enter admin first name: <input type="text" name="fname">
    <br><br>
    enter admin username: <input type="text" name="username">
    <br><br>
    enter admin password: <input type="password" name="password">
    <br><br>
    <input type="submit" name="config" value="Start The Configs">
</form> -->
<?php
// require("app.php");
// if (isset($_POST['config'])) {
//     $conn = new mysqli('localhost', 'root', '');
//     $sql = "CREATE DATABASE hr";
//     if ($conn->query($sql) === TRUE) {
//         echo "Database created successfully <br><br>";
//     } else {
//         echo "Error creating database: " . $conn->error . "<br><br>";
//     }
//     $sql = "CREATE TABLE `hr`.`users` ( `id` INT NOT NULL AUTO_INCREMENT , `firstName` VARCHAR(15) NOT NULL , `lastName` VARCHAR(15) NOT NULL , `username` VARCHAR(15) NULL DEFAULT NULL , `password` VARCHAR(15) NOT NULL , `role` VARCHAR(15) NOT NULL , `startTime` TIME NOT NULL , `endTime` TIME NOT NULL , `salary` FLOAT NOT NULL , `dayOff` TEXT NOT NULL , `pranch` TEXT NOT NULL , `workHours` FLOAT NULL DEFAULT NULL , `hourPrice` FLOAT NULL DEFAULT NULL , `statue` VARCHAR(15) NOT NULL DEFAULT 'offline' , `img` VARCHAR(15) NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB";
//     if ($conn->query($sql) === TRUE) {
//         echo "user table created successfully <br><br>";
//         $sql = "ALTER TABLE `users` ADD `barcode` VARCHAR(25) NULL DEFAULT NULL AFTER `statue`, ADD UNIQUE `barcode` (`barcode`)";
//         if ($conn->query($sql) === TRUE) {
//             echo "add barcode to users successfully <br><br>";
//         } else {
//             echo "Error add user barcode: " . $conn->error . "<br><br>";
//         }
//     } else {
//         echo "Error creating user table: " . $conn->error . "<br><br>";
//     }
//     $sql = "ALTER TABLE `users` ADD `barcode` BIGINT NOT NULL AFTER `id`;";
//     if ($conn->query($sql) === TRUE) {
//         echo "updated user table to add barcode <br><br>";
//     } else {
//         echo "Error creating barcode user table: " . $conn->error . "<br><br>";
//     }
//     $sql = "CREATE TABLE `hr`.`att` ( `user_id` INT NULL DEFAULT NULL , `day` DATE NULL DEFAULT NULL , `start` TIME NULL DEFAULT NULL , `finsih` TIME NULL DEFAULT NULL , `worked_time` FLOAT NULL DEFAULT NULL , `bounes` INT NULL DEFAULT NULL , `subtract` INT NULL DEFAULT NULL , `note` TEXT NULL DEFAULT NULL ) ENGINE = InnoDB";
//     if ($conn->query($sql) === TRUE) {
//         echo "att table created successfully <br><br>";
//     } else {
//         echo "Error creating att table: " . $conn->error . "<br><br>";
//     }
//     $sql = "CREATE TABLE `hr`.`loan` ( `employee_id` INT NOT NULL , `loan_value` FLOAT NOT NULL , `date` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP ) ENGINE = InnoDB;";
//     if ($conn->query($sql) === TRUE) {
//         echo "loan table created successfully <br><br>";
//     } else {
//         echo "Error creating loan table: " . $conn->error . "<br><br>";
//     }
//     closeConnection($conn);
//     $conn = makeConnection();
//     $firstName = $_POST['fname'];
//     $username = $_POST['username'];
//     $password = $_POST['password'];
//     $sql = "INSERT INTO users (firstName, username, password, role)
//         VALUES ('$firstName', '$username', '$password', 'admin')";
//     if ($conn->query($sql) === TRUE) {
//         echo "admin created successfully <br><br>";
//     } else {
//         echo "Error creating admin: " . $conn->error . "<br><br>";
//     }
//     closeConnection($conn);
// }
// $conn = new mysqli('localhost', 'root', '', 'hr');
// $sql = "ALTER TABLE `users` ADD `barcode` BIGINT NOT NULL AFTER `id`;";
// if ($conn->query($sql) === TRUE) {
//     echo "updated user table to add barcode <br><br>";
// } else {
//     echo "Error creating barcode user table: " . $conn->error . "<br><br>";
// }
$conn = new mysqli('localhost', 'root', '', 'hr');
$sql = "ALTER TABLE `att` CHANGE `bounes` `bounes` INT(11) NULL DEFAULT '0';";
if ($conn->query($sql) === TRUE) {
    echo "updated att table to make bounes default 0 <br><br>";
} else {
    echo "Error updating bounes att table: " . $conn->error . "<br><br>";
}
$sql = "ALTER TABLE `att` CHANGE `subtract` `subtract` INT(11) NULL DEFAULT '0';";
if ($conn->query($sql) === TRUE) {
    echo "updated att table to make subtract default 0 <br><br>";
} else {
    echo "Error updating subtract att table: " . $conn->error . "<br><br>";
}
?>