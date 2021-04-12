<?php
session_start();
require('../app.php');
app_handler(true);
$page_title = "Adding Employee";
$css_path = "css/";
require('../comp/start.php');
require('../comp/navbar.php');
?>
<style>
    body {
        text-align: center;
    }

    #img-form {
        margin-top: 15vh;
    }

    #img-form input {
        margin: 5vh auto;
    }
</style>
<form id="img-form" method="POST" enctype="multipart/form-data">
    <label>رجاء ارفاق الباركود</label>
    <br>
    <input type="text" name="barcode" id="barcode">
    <br>
    <input class="btn btn-outline-dark" style="width:20%" type="submit" value="ارفاق" name="barcode-submit">
    <input class="btn btn-outline-dark" style="width:20%" type="submit" value="تخطي" name="skip">
</form>
<script>
    document.getElementById("barcode").focus();
</script>
<?php
if (isset($_GET['id']) and isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
}
if (isset($_GET['r']) and $_GET['r'] == 'e') echo "<script>alert('هذا الباركود مستخدم من قبل')</script>";
require('../comp/end.php');
if (isset($_POST['barcode-submit'])) add_emp_barcode($id, $_POST['barcode'], $action);
if (isset($_POST['skip']) and $action == 'add-emp') redirect('http://' . $_SERVER['SERVER_NAME'] . ':8080/hr/app/add-emp.php?r=success');
if (isset($_POST['skip']) and $action != 'add-emp') redirect('http://' . $_SERVER['SERVER_NAME'] . ':8080/hr/app/emp-data.php?r=update_success');
?>