<?php
    session_start();
    require('../app.php');
    app_handler(true);
    $page_title = "Adding Manager";
    $css_file = "../css.css";
    require('../comp/start.php');
    require('../comp/navbar.php');

    if (isset($_GET['action'])&&isset($_GET['id'])){
        if ($_GET['action'] == 'update'){
            $id = $_GET['id'];
            $result = get_data_by_id($id);
            while ($row = $result->fetch_assoc()){
                $first_name = $row["firstName"];
                $last_name = $row["lastName"];
                $username = $row["username"];
                $password = $row["password"];
                $start_time = $row["startTime"];
                $end_time = $row["endTime"];
                $salary = $row["salary"];
                $day_off = $row["dayOff"];
                $pranch = $row["pranch"];
            }
        }
    }
?>
<style>
    #add-form{
        margin: 6% 20% auto 20%;
        text-align: center;
    }
    #add-form input{
        text-align: center;
    }
    #add-form select{
        width: 100%;
    }
</style>
<div class="p-3">
    <?php $back_url='http://'.$_SERVER['SERVER_NAME'].':8080/hr/app/man-data.php'; require('../comp/back-button.php');?>
</div>
<form id="add-form" method="POST" style="direction:rtl">
    </div>
    <div class="form-row">
        <div class="form-group col-md-<?php if (isset($_GET['action'])) echo'4'; else echo'6'?>">
            <label for="first-name">الاسم الاول</label>
            <input type="text" name="first-name" class="form-control" id="first-name" value="<?php if(isset($_GET['action'])&&isset($first_name)){echo $first_name;}?>" required>
        </div>
        <div class="form-group col-md-<?php if (isset($_GET['action'])) echo'4'; else echo'6'?>">
            <label for="last-name">اسم العيله</label>
            <input type="text" name="last-name" class="form-control" id="last-name" value=" <?php if(isset($last_name)){echo $last_name;}?>" required>
        </div>
        <?php 
            if (isset($_GET['action'])){
                $id = $_GET['id'];
                echo"
                    <div class='form-group col-md-4'>
                        <label for='id'>الرقم الخاص</label>
                        <input type='text' name='id' class='form-control' id='id' value='$id' readonly>
                    </div>
                ";
            }
        ?>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="username">اسم المستخدم</label>
            <input type="text" name="username" class="form-control" id="username" value="<?php if(isset($username)){echo $username;}?>" required>
        </div>
        <div class="form-group col-md-6">
            <label for="password">الباسورد</label>
            <input type="password" name="password" class="form-control" id="password" value="<?php if(isset($password)){echo $password;}?>" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="salary">الراتب</label>
            <input type="number" name="salary" class="form-control" id="salary" value="<?php if(isset($salary)){echo $salary;}?>" required>
        </div>

        <div class="form-group col-md-4">
            <label for="end-time">وقت نهايه العمل</label>
            <input type="time" name="end-time" class="form-control" id="end-time" value="<?php if(isset($end_time)){echo $end_time;}?>" required>
        </div>
        <div class="form-group col-md-4">
            <label for="start-time">وقت بدايه العمل</label>
            <input type="time" name="start-time" class="form-control" id="start-time" value="<?php if(isset($start_time)){echo $start_time;}?>" required>
        </div>
    </div>
    <div class="form-row mb-3">
    <div class="form-group col-md-6">
            <label for="day-off">يوم الاجازه</label>
            <select class="mt-1" id="day-off" name="day-off">
                <option value="الجمعه" <?php if(isset($day_off)) if ($day_off == 'الجمعه') echo 'selected'?>>الجمعه</option>
                <option value="السبت" <?php if(isset($day_off)) if ($day_off == 'السبت') echo 'selected'?>>السبت</option>
                <option value="الاحد" <?php if(isset($day_off)) if ($day_off == 'الاحد') echo 'selected'?>>الاحد</option>
                <option value="الاتنين" <?php if(isset($day_off)) if ($day_off == 'الاتنين') echo 'selected'?>>الاثنين</option>
                <option value="الثلاثاء" <?php if(isset($day_off)) if ($day_off == 'الثلاثاء') echo 'selected'?>>الثلاثاء</option>
                <option value="الاربعاء" <?php if(isset($day_off)) if ($day_off == 'الاربعاء') echo 'selected'?>>الاربعاء</option>
                <option value="الخميس" <?php if(isset($day_off)) if ($day_off == 'الخميس') echo 'selected'?>>الخميس</option>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="pranch">الفرع</label>
            <select class="mt-1" id="pranch" name="pranch">
                <option value="سبورتنح" <?php if(isset($pranch)) if ($pranch == 'سبورتنح') echo 'selected'?>>سبورتنح</option>
                <option value="سموحه" <?php if(isset($pranch)) if ($pranch == 'سموحه') echo 'selected'?>>سموحه</option>
                <option value="المخزن" <?php if(isset($pranch)) if ($pranch == 'المخزن') echo 'selected'?>>المخزن</option>
                <option value="المكتب" <?php if(isset($pranch)) if ($pranch == 'المكتب') echo 'selected'?>>المكتب</option>
            </select>
        </div>
    </div>
    <?php
        if (isset($_GET['action'])){
            if ($_GET['action'] == 'update') echo '<button type="submit" name="update-man" class="btn btn-dark btn-block">
            <i class="fas fas fa-pen mr-2"></i> تحديث</button>';
        }else echo '<button type="submit" name="add-man" class="btn btn-dark btn-block"><i class="fas fa-plus-circle mr-2"></i>
        اضافه</button>';

        if (isset($_GET['r'])){
            if ($_GET['r'] == 'error'){
                echo '
                    <div class="alert alert-danger mt-3" role="alert">
                        حدث خطا غير معروف الرجاء الاتصال بالدعم الفني لمزيد من المعلومات
                    </div>';
            }
            if ($_GET['r'] == 'success'){
                echo '
                    <div class="alert alert-success mt-3" role="alert">
                        تم اضافه مدير بنجاح
                    </div>';
            }
            if ($_GET['r'] == 'username_error'){
                echo '
                    <div class="alert alert-danger mt-3" role="alert">
                        اسم المستخدم هذا فيد الاستعمال برجاء اختيار اخر
                    </div>';
            }
        }
    ?>
</form>
<?php
    require('../comp/end.php');
?>