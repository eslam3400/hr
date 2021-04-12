<?php
    session_start();
    require('../app.php');
    app_handler(true);
    $page_title = "Add Loan";
    $css_file = "../css.css";
    require('../comp/start.php');
    require('../comp/navbar.php');
?>
<div class="p-3">
    <?php $back_url='http://'.$_SERVER['SERVER_NAME'].':8080/hr/app/emp-data.php'; require('../comp/back-button.php');?>
</div>
<form id='login-form' class="text-center" method="POST">
    <div class="form-row">
        <div class="form-group col-md-3" style="margin:auto;">
            <label for="inputPassword4">السلفه</label>
            <input type="number" name="loan" class="form-control" id="inputPassword4" value="0">
        </div>
    </div>
    <button type="submit" name="add-loan" class="btn btn-outline-dark btn-block mt-5 col-md-6" style="margin:auto;">اضافه</button>
</form>
<?php
    require('../comp/end.php');
    $id = $_GET['id'];
    function make_loan($id,$loan){
        $host = $_SERVER['SERVER_NAME'];
        $conn = makeConnection();
        $date = date("Y-m-d");
        $sql = "INSERT INTO loan (employee_id, loan_value)
                VALUES ($id,$loan)";
        if ($conn->query($sql)) redirect("http://$host:8080/hr/app/emp-data.php?r=loan-added");
        closeConnection($conn);
    }
    if (isset($_POST['add-loan'])){
        make_loan($id,$_POST['loan']);
    }
?>