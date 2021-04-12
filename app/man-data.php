<?php
session_start();
require('../app.php');
app_handler(true);
$page_title = "Manager Data";
$css_path = "css/";
require('../comp/start.php');
require('../comp/navbar.php');
?>
<div class="p-3">
    <?php $back_url = 'admin-panel.php';
    require('../comp/back-button.php'); ?>
</div>
<table class="table table-striped text-center">
    <thead>
        <tr>
            <th scope="col">الرقم الخاص</th>
            <th scope="col">الاسم الاول</th>
            <th scope="col">اللقب</th>
            <th scope="col">اسم المستخدم</th>
            <th scope="col">بدايه وفت العمل</th>
            <th scope="col">نهايه وقت العمل</th>
            <th scope="col">المرتب</th>
            <th scope="col">يوم الاجازه</th>
            <th scope="col">الفرع</th>
            <th scope="col">ادوات التحكم</th>
        </tr>
    </thead>
    <tbody>
        <?php render_man_data() ?>
    </tbody>
</table>
<?php
if (isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] == 'delete') {
    delete_man($_GET['id']);
}
if (isset($_GET['r'])) {
    if ($_GET['r'] == 'update_success') {
        echo '
                <script>
                    alert("تم تحديث بيانات المدير بنجاح")
                </script>';
    }
}
if (isset($_GET['r'])) {
    if ($_GET['r'] == 'delete-success') {
        echo '
                <script>
                    alert("تم حذف المدير بنجاح")
                </script>';
    }
}
require('../comp/end.php');
?>