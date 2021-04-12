<?php
session_start();
require('app.php');
$page_title = "login";
$css_path = "app/css/";
require('comp/start.php');
require('comp/navbar.php');
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') redirect('http://localhost/hr/app/admin-panel.php');
}
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'man') redirect('http://localhost/hr/app/man-panel.php');
}
?>
<form id="login-form" method="POST">
    <div class="form-group">
        <label for="exampleInputEmail1">اسم المستخدم</label>
        <input type="text" name="username" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter username" onchange="barcode()">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">كلمه السر</label>
        <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
    </div>
    <button type="submit" name="login" class="btn btn-dark btn-block mt-4 p-2">تسجيل الدخول</button>
    <?php
    if (isset($_GET['r'])) {
        if ($_GET['r'] == 'error') {
            echo '
                    <div class="alert alert-danger mt-3" role="alert">
                        اسم المستخدم او كلمه المرور خطا برجاء المحاوله مره اخري
                    </div>';
        }
        if ($_GET['r'] == 'access_denied') {
            echo '
                    <div class="alert alert-danger mt-3" role="alert">
                        غير مسموح لك بالوصل لهذه الصفحه تاكد من امتلاك الصلاحيات المناسبه للوصول اليها
                    </div>';
        }
    }
    ?>
</form>
<?php
require('comp/end.php');
?>