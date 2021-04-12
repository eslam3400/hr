<?php
    session_start();
    require('../app.php');
    app_handler();
    $page_title = "Employee Report";
    $css_file = "../css.css";
    require('../comp/start.php');
    require('../comp/navbar.php');
?>
<div class="pt-3 pl-3">
    <?php
        $back_url="http://{$_SERVER['SERVER_NAME']}:8080/hr/app/emp-data.php";
        require('../comp/back-button.php');
        if (isset($_GET['month'])){
            $host = $_SERVER['SERVER_NAME'];
            $month = $_GET['month'];
            $year = $_GET['year'];
            $id = $_GET['id'];
            echo "<a href='http://{$host}:8080/hr/app/print-report.php?id=$id&year=$year&month=$month' class='btn btn-outline-success ml-3'>Print This Report</a>";
        }
    ?>
</div>
<?php
    if (!isset($_GET['month'])) $id = $_GET['id']; echo "
        <form method='get' class='text-center'>
            <h3 class='mb-3'>من فضلك اختر تاريخ التقرير</h3>
            Year: 
            <select name='year'>
                <option value='2020'>2020</option>
                <option value='2021'>2021</option>
            </select>
            Month:
            <select name='month'>
                <option value='01'>01</option>
                <option value='02'>02</option>
                <option value='03'>03</option>
                <option value='04'>04</option>
                <option value='05'>05</option>
                <option value='06'>06</option>
                <option value='07'>07</option>
                <option value='08'>08</option>
                <option value='09'>09</option>
                <option value='10'>10</option>
                <option value='11'>11</option>
                <option value='12'>12</option>
            </select>
            <br>
            <input class='btn btn-outline-dark ml-3 mt-2' type='submit' name='time' value='Select'>
            <input style='display:none' type='number' name='id' value='$id'>
        </form>";

    if (isset($_GET['id']) AND isset($_GET['month'])){
        echo "
            <table class='table table-striped text-center mt-4'>
                <thead>
                    <tr>
                    <th scope='col'>ادوات التحكم</th>
                    <th scope='col'>ملاحظات</th>
                    <th scope='col'>خصم</th>
                    <th scope='col'>مكافئه</th>
                    <th scope='col'>عدد ساعات العمل</th>
                    <th scope='col'>انصراف</th>
                    <th scope='col'>حضور</th>
                    <th scope='col'>اليوم</th>
                    </tr>
                </thead>
                <tbody>";
        emp_report($_GET['id'],$_GET['year'],$_GET['month']);
        echo "
                </tbody>
            </table>";
    }
    if (isset($_GET['r']) AND $_GET['r'] == 'update-success') echo "<script>alert('تم تحديث البيانات بنجاح')</script>";
    require('../comp/end.php');
?>