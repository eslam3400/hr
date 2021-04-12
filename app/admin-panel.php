<?php
session_start();
require('../app.php');
app_handler(true);
$page_title = "Admin Panel";
$css_path = "css/";
require('../comp/start.php');
require('../comp/navbar.php');
?>
<div style="margin-top:10%">
    <a class="btn btn-outline-dark admin-links btn-block" href="/hr/app/add-emp.php"><i class="fas fa-user-plus mr-2"></i> اضافه موظف</a>
    <a class="btn btn-outline-dark admin-links btn-block" href="/hr/app/emp-data.php"><i class="fas fa-user-edit mr-2"></i> بيانات موظف</a>
    <a class="btn btn-outline-dark admin-links btn-block" href="/hr/app/add-man.php"><i class="fas fa-user-plus mr-2"></i> اضافه مدير</a>
    <a class="btn btn-outline-dark admin-links btn-block" href="/hr/app/man-data.php"><i class="fas fa-user-edit mr-2"></i> بيانات مدير</a>
</div>
<?php
require('../comp/end.php');
?>