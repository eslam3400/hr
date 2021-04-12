<?php
session_start();
require('../app.php');
app_handler();
$page_title = "Manager Panel";
$css_path = "css/";
require('../comp/start.php');
require('../comp/navbar.php');
?>
<div class="p-3">
    <?php $back_url = 'man-panel.php';
    require('../comp/back-button.php'); ?>
</div>
<form id='login-form' method="POST">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputEmail4">المكافئه</label>
            <input type="number" name="bonus" class="form-control" id="inputEmail4" value="0">
        </div>
        <div class="form-group col-md-6">
            <label for="inputPassword4">الخصم</label>
            <input type="number" name="subtract" class="form-control" id="inputPassword4" value="0">
        </div>
    </div>
    <div class="form-group">
        <label for="inputAddress">الملاحظات</label>
        <input type="text" class="form-control" name="note" id="inputAddress" value=" ">
    </div>
    <button type="submit" name="add-note" class="btn btn-outline-dark btn-block mt-5">اضافه</button>
</form>
<?php
require('../comp/end.php');
$id = $_GET['id'];
function make_note($id, $bonus, $subtract, $note)
{
    $host = $_SERVER['SERVER_NAME'];
    $conn = makeConnection();
    $date = date("Y-m-d");
    $sql = "SELECT * FROM att WHERE user_id = $id AND day = '$date'";
    $result = $conn->query($sql);
    if ($result->num_rows === 1) {
        $sql = "UPDATE `att` SET `bounes`= $bonus,`subtract`= $subtract,`note`= '$note' WHERE `user_id` = $id AND `day` = '$date'";
        $conn->query($sql);
        redirect("http://$host:8080/hr/app/man-panel.php?r=note-added");
    } else if ($result->num_rows === 0) {
        $date = date('Y-m-d', strtotime("-1 days"));
        $sql = "UPDATE `att` SET `bounes`= $bonus,`subtract`= $subtract,`note`= '$note' WHERE `user_id` = $id AND `day` = '$date'";
        $conn->query($sql);
        closeConnection($conn);
    }
}
if (isset($_POST['add-note'])) {
    make_note($id, $_POST['bonus'], $_POST['subtract'], $_POST['note']);
}
?>