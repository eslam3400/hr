<?php
session_start();
require('../app.php');
app_handler();
$page_title = "Employee Report";
$css_path = "css/";
require('../comp/start.php');
require('../comp/navbar.php');
$data = final_report_data($_GET['id']);
?>
<style>
    h4,
    div,
    table {
        font-size: 3.5rem;
        font-weight: 1000;
    }

    .table-bordered,
    .table-bordered th,
    .table-bordered thead th {
        border: solid 3px black;
    }

    .table-bordered td {
        border: solid 2px black;
    }

    h4 {
        background-color: #b7aeae;
    }

    #name,
    #salary,
    #details p,
    #details2 p {
        border: .5px solid black;
        padding: 10px;
    }

    #total p {
        background-color: #b7aeae;
        border: 1px solid black;
        padding: 50px;
    }
</style>
<h4 class="text-center p-3">خواطر دمشقيه</h4>
<div class="text-center pb-4 mt-5 mb-3">
    <p id="name" class="d-inline">الاسم: <?php echo $data['firstName'] . " " . $data['lastName'] ?></p>
    <p id="salary" class="d-inline ml-5">المرتب الاساسي: <?php echo $data['salary'] ?></p>
</div>
<table class="table table-bordered text-center">
    <tbody>
        <tr>
            <th scope="col" style="font-size:2.5rem">ساعات العمل</th>
            <th scope="col">انصراف</th>
            <th scope="col">حضور</th>
            <th scope="col">اليوم</th>
        </tr>
        <?php $totals = emp_final_report($_GET['id'], $_GET['year'], $_GET['month']) ?>
    </tbody>
</table>
<div id="details" class="text-center pt-4 mb-5">
    <p class="d-inline ml-3 mr-3">المكافئات: <?php echo $totals[1] ?></p>
    <p class="d-inline ml-3 mr-3">الخصومات: <?php echo $totals[2] ?></p>
    <p class="d-inline">السلف: <?php echo $totals[3] ?></p>
</div>
<div id="details2" class="text-center">
    <p class="d-inline">مجموع ساعات الحضور: <?php echo $totals[0] ?></p>
</div>
<div id="total" class="text-center pt-5">
    <p>الراتب النهائي: <?php echo (($totals[0] * $data['hourPrice']) + ($totals[1] - $totals[2] - $totals[3])) ?></p>
</div>
<p style="color:white">ahmed</p>
<p style="color:white">ahmed</p>
<?php
require('../comp/end.php');
?>