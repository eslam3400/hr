<?php
session_start();
require('../app.php');
app_handler();
$page_title = "Employee Report";
$css_path = "css/";
require('../comp/start.php');
require('../comp/navbar.php');
?>
<div class="p-3">
    <?php $back_url = "http://{$_SERVER['SERVER_NAME']}:8080/hr/app/emp-report.php?id={$_GET['id']}";
    require('../comp/back-button.php'); ?>
</div>
<div style="width:100%">
    <form id='login-form' method="POST" style="width:80%;margin:auto;text-align:center">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="inputEmail4">حضور</label>
                <input type="time" name="startt" class="form-control" id="inputEmail4" value="<?php echo $_GET['start'] ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="inputPassword4">الانصراف</label>
                <input type="time" name="finishh" class="form-control" id="inputPassword4" value="<?php echo $_GET['finish'] ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="inputPassword4">ساعات العمل</label>
                <input type="number" name="worked-hours" class="form-control" id="inputPassword4" value="<?php echo $_GET['worked-time'] ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputEmail4">المكافئه</label>
                <input type="number" name="bounes" class="form-control" id="inputEmail4" value="<?php echo $_GET['bounes'] ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="inputPassword4">الخصم</label>
                <input type="number" name="subtract" class="form-control" id="inputPassword4" value="<?php echo $_GET['subtract'] ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="inputAddress">الملاحظات</label>
            <input type="text" class="form-control" name="note" id="inputAddress" value="<?php echo $_GET['note'] ?>">
        </div>
        <button type="submit" name="update-report" class="btn btn-outline-dark btn-block mt-5">تحديث</button>
    </form>
</div>
<?php
require('../comp/end.php');
$id;
if (isset($_GET['id'])) $GLOBALS['id'] = $_GET['id'];
if (isset($_GET['day'])) $GLOBALS['day'] = $_GET['day'];
if (isset($_POST['update-report'])) {
    $start = $_POST['startt'];
    $finish = $_POST['finishh'];
    $worked_hours = $_POST['worked-hours'];
    $bounes = $_POST['bounes'];
    $subtract = $_POST['subtract'];
    $note = $_POST['note'];
    $id = $GLOBALS['id'];
    $day = $GLOBALS['day'];
    $flag = false;
    $conn = makeConnection();
    $sql = "UPDATE att SET `start`='$start', `finsih`='$finish', `worked_time`=$worked_hours, `bounes`=$bounes, `subtract`=$subtract, `note`='$note' WHERE `user_id`= $id AND `day`='$day'";
    if ($conn->query($sql)) $flag = true;
    closeConnection($conn);
    redirect("http://{$_SERVER['SERVER_NAME']}:8080/hr/app/emp-report.php?r=update-success&id={$id}");
}
?>