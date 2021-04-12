<?php
    session_start();
    require('../app.php');
    app_handler(true);
    $page_title = "Adding Employee";
    $css_file = "../css.css";
    require('../comp/start.php');
    require('../comp/navbar.php');
?>
<style>
    body{
        text-align:center;
    }
    #img-form{
        margin-top:15vh;
    }
    #img-form input{
        margin: 5vh auto;
    }
</style>
<form id="img-form" method="POST" enctype="multipart/form-data">
    <label>رجاء ارفاق صوره العامل</label>
    <br>
    <input type="file" name="file">
    <br>
    <input class="btn btn-outline-dark" style="width:30%" type="submit" value="ارفاق" name="submit">
</form>
<?php
    $host = $_SERVER['SERVER_NAME'];
    $id = $_GET['id'];
    $conn = makeConnection();        
    if (isset($_POST['submit'])){
        $file = $_FILES['file'];
        $fileName = $_FILES['file']['name'];
        $fileTempName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];
        $fileExt = explode('.' , $fileName);
        $fileActualExt = strtolower(end($fileExt));
        $allowedExt = array('jpg','jpeg','png');
        if (in_array($fileActualExt,$allowedExt)){
            if($fileError===0) {
                $fileNewName = $id."." .$fileActualExt;
                $fileDestination = "../img/".$fileNewName;
                move_uploaded_file($fileTempName,$fileDestination);
                $sql = "UPDATE users SET img = '$fileNewName' WHERE id = $id";
                $conn->query($sql);
                closeConnection($conn);
                if (isset($_GET['action']) AND $_GET['action'] == 'add-emp') redirect("http://$host:8080/hr/app/add-barcode.php?id=$id&action=add-emp");
                else redirect("http://$host:8080/hr/app/add-barcode.php?id=$id&action=update-emp");
            }
        }else{
            closeConnection($conn);
            if (isset($_GET['action']) AND $_GET['action'] == 'add-emp') redirect("http://$host:8080/hr/app/add-barcode.php?id=$id&action=add-emp");
            else redirect("http://$host:8080/hr/app/add-barcode.php?id=$id&action=update-emp");
        }
    }
    require('../comp/end.php');
?>