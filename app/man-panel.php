<?php
session_start();
require('../app.php');
app_handler();
$page_title = "Manager Panel";
$css_path = "css/";
require('../comp/start.php');
require('../comp/navbar.php');
?>
<style>
    .col-4 {
        height: 90vh;
    }

    form img {
        margin-top: 2vh;
    }

    form input {
        margin-top: 4vh;
    }

    form .submit {
        width: 40vw;
    }
</style>
<div id="att" class="row">
    <div class="overflow-auto col-4">
        <a href="using-barcode.php" class="btn btn-outline-dark m-3">استخدام الباركود</a>
        <?php
        render_emp_list($_SESSION['pranch']);
        ?>
    </div>
    <div class="col-8 p-0">
        <div>
            <form class="text-center" method="POST">
                <?php
                if (isset($_GET['id']) && isset($_GET['stat'])) {
                    $id = $_GET['id'];
                    $stat = $_GET['stat'];
                    $host = $_SERVER['SERVER_NAME'];
                    $conn = makeConnection();
                    $sql = "SELECT img FROM users WHERE id=$id";
                    $result = $conn->query($sql);
                    $result->num_rows;
                    $row = $result->fetch_assoc();
                    $img = $row['img'];
                    echo "<label>الصوره الشخصيه</label>";
                    echo "<br>";
                    echo "<img src='../img/$img' width='500px' hight='500px'>";
                    echo "<br>";
                    echo "<input class='text-center' type='number' style='border:none;' value='$id' name='id' readonly> :الرقم الخاص";
                    echo "<br>";
                    echo "<input class='text-center' type='text' style='border:none;' value='$stat' name='stat' readonly> :حاله التواجد";
                    echo "<br>";
                    if ($stat === "offline") echo "<input type='submit' class='submit btn btn-outline-dark' value='حضور' name='start'>";
                    if ($stat === "online") echo "<input type='submit' class='submit btn btn-outline-dark' value='انصراف' name='end'>";
                    echo "<br>";
                    echo "<a class='btn btn-outline-info' style='margin-top:5vh;' href='http://$host:8080/hr/app/add-note.php?id=$id'>اضافه ملاحظه وخصومات او مكافئات</a>";
                    echo "<br>";
                    echo "<a class='btn btn-outline-primary' style='margin-top:5vh;' href='http://$host:8080/hr/app/move-emp.php?id=$id'>نقل الي فرع اخر</a>";
                    closeConnection($conn);
                }
                if (isset($_GET['r'])) {
                    if ($_GET['r'] == 'start') {
                        echo "<script>alert('تم تسجيل الحضور')</script>";
                    }
                    if ($_GET['r'] == 'end') {
                        echo "<script>alert('تم تسجيل الانصراف')</script>";
                    }
                    if ($_GET['r'] == 'error') {
                        echo "<script>alert('تم تسجيل الدخول والخروج بالفعل لهذا العامل اليوم')</script>";
                    }
                    if ($_GET['r'] == 'note-added') {
                        echo "<script>alert('تم اضافه الملاحظات')</script>";
                    }
                    if ($_GET['r'] == 'moved') {
                        echo "<script>alert('تم نقل الموظف')</script>";
                    }
                }
                ?>
                <br><br>
            </form>
        </div>
    </div>
</div>
<?php
require('../comp/end.php');
?>