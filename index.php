<?php
    session_start();
    require('app.php');
    $page_title = "Use Barcode";
    $css_file = "css.css";
    require('comp/start.php');
    require('comp/navbar.php');
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
    <label>رجاء تمرير الباركود</label>
    <br>
    <input type="text" name="barcode" id="barcode">
    <br>
    <input style="visibility:hidden" class="btn btn-outline-dark" style="width:20%" type="submit" name="attendance">
</form>
<script>
    document.getElementById("barcode").focus();
    setTimeout(() => {
        let host = document.getElementById('r');
        host.style.visibility = 'hidden';
    }, 5000);
</script>
<?php
    require('comp/end.php');
    $id;
    $status;
    function get_status($barcode){
        $conn = makeConnection();
        $sql = "SELECT statue
                FROM users
                WHERE barcode = $barcode";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $result->num_rows;
        closeConnection($conn);
        return $row['statue'];
    }
    function get_id($barcode){
        $conn = makeConnection();
        $sql = "SELECT id
                FROM users
                WHERE barcode = $barcode";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $result->num_rows;
        closeConnection($conn);
        return $row['id'];
    }
    function get_name($barcode){
        $conn = makeConnection();
        $sql = "SELECT firstName, lastName
                FROM users
                WHERE barcode = $barcode";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $result->num_rows;
        closeConnection($conn);
        return $row['firstName']." ".$row['lastName'];
    }
    if (isset($_POST['attendance'])){
        $GLOBALS['status'] = get_status(intval($_POST['barcode']));
        $GLOBALS['id'] = get_id(intval($_POST['barcode']));
        att_barcode($GLOBALS['id'],$GLOBALS['status'],$_POST['barcode']);
    }
    if (isset($_GET['r']) AND $_GET['r'] == 'error') echo '<span id="r">تم تسجيل الدخول والخروج بالفعل ل'.get_name($_GET['barcode']).' لهذا اليوم </span>';
    if (isset($_GET['r']) AND $_GET['r'] == 'start') echo '<span id="r">تم تسجيل الدخول ل'.get_name($_GET['barcode']).'</span>';
    if (isset($_GET['r']) AND $_GET['r'] == 'end') echo '<span id="r"> تم تسجيل الانصراف ل'.get_name($_GET['barcode']).'</span>';
?>