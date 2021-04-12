<?php
// Done
function makeConnection()
{
    $conn = new mysqli("localhost", "root", "", "hr");
    if ($conn->connect_error) die("Connection faild: " . $conn->connect_error);
    else return $conn;
}
// Done
function login($username, $password)
{
    $host = $_SERVER['SERVER_NAME'];
    $conn = makeConnection();
    $username = test_input($conn, $username);
    $password = test_input($conn, $password);
    $sql = "SELECT username, password, firstName, role, pranch FROM users WHERE NOT username = ''";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['username'] == $username and $row['password']) {
                $_SESSION['role'] = $row['role'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['first-name'] = $row['firstName'];
                $_SESSION['pranch'] = $row['pranch'];
                if ($row['role'] == 'admin') redirect("http://$host:8080/hr/app/admin-panel.php");
                if ($row['role'] == 'man') redirect("http://$host:8080/hr/app/man-panel.php");
            }
        }
        redirect("http://$host:8080/hr/login.php?r=error");
    }
}
//Done
function is_username_unique($username, $conn)
{
    $sql = "SELECT username FROM users";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($username == $row['username']) return false;
        }
    }
    return true;
}
//done
function get_data_by_id($id)
{
    $conn = makeConnection();
    $id = test_input($conn, $id);
    $sql = "SELECT * FROM users WHERE id='$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result;
    } else {
        return "none";
    }
}

function add_emp($first_name, $last_name, $start_time, $end_time, $salary, $day_off, $pranch)
{
    $host = $_SERVER['SERVER_NAME'];
    $conn = makeConnection();
    $first_name = test_input($conn, $first_name);
    $last_name = test_input($conn, $last_name);
    $start_time = test_input($conn, $start_time);
    $end_time = test_input($conn, $end_time);
    $salary = test_input($conn, $salary);
    $day_off = test_input($conn, $day_off);
    $pranch = test_input($conn, $pranch);
    $role = 'emp';
    $sql = "INSERT INTO users (firstName, lastName, role, startTime, endTime, salary, dayOff, pranch,statue) VALUES ('$first_name', '$last_name', '$role', '$start_time', '$end_time', '$salary', '$day_off', '$pranch','offline')";
    $conn->query($sql);
    $sql = "SELECT startTime,endTime,salary,id FROM users ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $id = $row["id"];
        $salary = $row['salary'];
        $start_time = make_time_num($row["startTime"]);
        $end_time = make_time_num($row["endTime"]);
        if ($end_time < 12 && $start_time > 12) {
            $end_time = $end_time + 12;
            $start_time = $start_time - 12;
            $work_time = abs($start_time - $end_time);
            $hour_cost = $salary / ($work_time * 30);
            $sql = "UPDATE users SET hourPrice = $hour_cost, workHours = $work_time WHERE id = $id";
            $conn->query($sql);
            closeConnection($conn);
            // redirect("http://$host/hr/app/add-emp.php?r=success");
            redirect("http://$host:8080/hr/app/add-img.php?id=$id&action=add-emp");
        } else {
            $work_time = abs($start_time - $end_time);
            $hour_cost = $salary / ($work_time * 30);
            $sql = "UPDATE users SET hourPrice = $hour_cost, workHours = $work_time WHERE id = $id";
            $conn->query($sql);
            closeConnection($conn);
            redirect("http://$host:8080/hr/app/add-img.php?id=$id&action=add-emp");
            // redirect("http://$host/hr/app/add-emp.php?r=success");
        }
    } else {
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        closeConnection($conn);
        redirect("http://$host:8080/hr/app/add-emp.php?r=error");
    }
}

function add_emp_barcode($id, $barcode, $action)
{
    $host = $_SERVER['SERVER_NAME'];
    $conn = makeConnection();
    $barcode = test_input($conn, $barcode);
    $sql = "SELECT *
                FROM `users`
                WHERE `barcode` = $barcode ";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) die(redirect("http://$host:8080/hr/app/add-barcode.php?r=e&action=$action&id=$id"));
    else {
        $sql = "UPDATE users SET barcode = $barcode WHERE id = $id";
        if ($conn->query($sql)) {
            closeConnection($conn);
            if ($action == 'add-emp') die(redirect("http://$host:8080/hr/app/add-emp.php?r=success"));
            else redirect("http://$host:8080/hr/app/emp-data.php?r=update_success");
        }
    }
}

function render_emp_data()
{
    $conn = makeConnection();
    $sql = "SELECT * FROM users WHERE role = 'emp'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $first_name = $row["firstName"];
            $last_name = $row["lastName"];
            $start_time = $row["startTime"];
            $end_time = $row["endTime"];
            $salary = $row["salary"];
            $day_off = $row["dayOff"];
            $pranch = $row["pranch"];
            echo "
                    <tr>
                        <td>
                            <a class='text-dark mr-2' href='/hr/app/emp-data.php?action=delete&id={$id}'><i class='fas fa-trash-alt'></i></a>
                            <a class='text-dark mr-2' href='/hr/app/loan.php?action=loans&id={$id}'><i class='fas fa-coins'></i></a>
                            <a class='text-dark mr-2' href='/hr/app/add-emp.php?action=update&id={$id}'><i class='fas fa-edit'></i></a>
                            <a class='text-dark' href='/hr/app/emp-report.php?id={$id}'><i class='far fa-file-alt'></i></a>
                        </td>
                        <td>$pranch</td>
                        <td>$day_off</td>
                        <td>$end_time</td>
                        <td>$start_time</td>
                        <td>$salary</td>
                        <td>$last_name</td>
                        <td>$first_name</td>
                        <th scope='row'>$id</th>
                    </tr>
                ";
        }
    }
    closeConnection($conn);
}

function emp_report($id, $year, $month)
{
    $conn = makeConnection();
    $sql = "SELECT * FROM users WHERE `id` = $id";
    $result = $conn->query($sql)->fetch_assoc();
    $workHours = $result['workHours'];
    $sql = "SELECT * FROM att WHERE `day` LIKE '$year-$month%' AND `user_id` = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $date = $row['day'];
            $start = $row['start'];
            $finish = $row['finsih'];
            $worked_time = $row['worked_time'];
            $bounes = isset($row['bounes']) ? $row['bounes'] : '-';
            $subtract = isset($row['subtract']) ? $row['subtract'] : '-';
            $note = isset($row['note']) ? $row['note'] : '-';
            if ($worked_time > $workHours) {
                echo "
                    <tr style='background-color: rgba(245, 79, 28, 0.6)'>
                        <td><a class='text-dark mr-2' href='/hr/app/edit-report.php?action=edit&id={$id}&day={$date}&start={$start}&finish={$finish}&worked-time={$worked_time}&bounes={$bounes}&subtract={$subtract}&note={$note}'><i class='fas fa-edit'></i></a></td>
                        <td>$note</td>
                        <td>$subtract</td>
                        <td>$bounes</td>
                        <td>$worked_time</td>
                        <td>$finish</td>
                        <td>$start</td>
                        <td>$date</td>
                    </tr>
                ";
            } else {
                echo "
                    <tr>
                        <td><a class='text-dark mr-2' href='/hr/app/edit-report.php?action=edit&id={$id}&day={$date}&start={$start}&finish={$finish}&worked-time={$worked_time}&bounes={$bounes}&subtract={$subtract}&note={$note}'><i class='fas fa-edit'></i></a></td>
                        <td>$note</td>
                        <td>$subtract</td>
                        <td>$bounes</td>
                        <td>$worked_time</td>
                        <td>$finish</td>
                        <td>$start</td>
                        <td>$date</td>
                    </tr>
                ";
            }
        }
    }
    closeConnection($conn);
}

function update_emp($id, $first_name, $last_name, $start_time, $end_time, $salary, $day_off, $pranch)
{
    $host = $_SERVER['SERVER_NAME'];
    $conn = makeConnection();
    $first_name = test_input($conn, $first_name);
    $last_name = test_input($conn, $last_name);
    $start_time = test_input($conn, $start_time);
    $end_time = test_input($conn, $end_time);
    $salary = test_input($conn, $salary);
    $day_off = test_input($conn, $day_off);
    $pranch = test_input($conn, $pranch);
    $sql = "UPDATE users SET firstName='$first_name', lastName='$last_name', startTime='$start_time', endTime='$end_time', salary='$salary', dayOff='$day_off',pranch='$pranch' WHERE id='$id'";
    $conn->query($sql);
    $sql = "SELECT startTime,endTime FROM users WHERE id =$id";
    $result = $conn->query($sql);
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $start_time = make_time_num($row["startTime"]);
        $end_time = make_time_num($row["endTime"]);
        if ($end_time < 12 && $start_time > 12) {
            $end_time = $end_time + 12;
            $start_time = $start_time - 12;
            $work_time = abs($start_time - $end_time);
            $hour_cost = $salary / ($work_time * 30);
            $sql = "UPDATE users SET hourPrice = $hour_cost, workHours = $work_time WHERE id = $id";
            $conn->query($sql);
            closeConnection($conn);
            redirect("http://$host:8080/hr/app/add-barcode.php?action=update&id=$id");
            // redirect("http://$host/hr/app/emp-data.php?r=update_success");
        } else {
            $work_time = abs($start_time - $end_time);
            $hour_cost = $salary / ($work_time * 30);
            $sql = "UPDATE users SET hourPrice = $hour_cost, workHours = $work_time WHERE id = $id";
            $conn->query($sql);
            closeConnection($conn);
            redirect("http://$host:8080/hr/app/add-barcode.php?action=update&id=$id");
            // redirect("http://$host/hr/app/emp-data.php?r=update_success");
        }
    }
}

function delete_emp($id)
{
    $host = $_SERVER['SERVER_NAME'];
    $conn = makeConnection();
    $sql = "DELETE FROM users WHERE id=$id";
    $conn->query($sql);
    closeConnection($conn);
    redirect("http://$host:8080/hr/app/emp-data.php?r=delete-success");
}

function add_man($first_name, $last_name, $username, $password, $start_time, $end_time, $salary, $day_off, $pranch)
{
    $host = $_SERVER['SERVER_NAME'];
    $conn = makeConnection();
    $first_name = test_input($conn, $first_name);
    $last_name = test_input($conn, $last_name);
    $username = test_input($conn, $username);
    $password = test_input($conn, $password);
    $start_time = test_input($conn, $start_time);
    $end_time = test_input($conn, $end_time);
    $salary = test_input($conn, $salary);
    $day_off = test_input($conn, $day_off);
    $pranch = test_input($conn, $pranch);
    $role = 'man';
    if (is_username_unique($username, $conn)) {
        $sql = "INSERT INTO users (`firstName`, `lastName`, `username`, `password`, `role`, `startTime`, `endTime`, `salary`, `dayOff`, `pranch`, `statue`) 
                VALUES ('$first_name', '$last_name', '$username', '$password', '$role', '$start_time', '$end_time', '$salary', '$day_off', '$pranch', 'offline')";
        $conn->query($sql);
        $sql = "SELECT startTime,endTime,salary,id FROM users ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql);
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $id = $row["id"];
            $salary = $row['salary'];
            $start_time = make_time_num($row["startTime"]);
            $end_time = make_time_num($row["endTime"]);
            if ($end_time < 12 && $start_time > 12) {
                $end_time = $end_time + 12;
                $start_time = $start_time - 12;
                $work_time = abs($start_time - $end_time);
                $hour_cost = $salary / ($work_time * 30);
                $sql = "UPDATE users SET hourPrice = $hour_cost, workHours = $work_time WHERE id = $id";
                $conn->query($sql);
                closeConnection($conn);
                redirect("http://$host:8080/hr/app/add-man.php?r=success");
            } else {
                $work_time = abs($start_time - $end_time);
                $hour_cost = $salary / ($work_time * 30);
                $sql = "UPDATE users SET hourPrice = $hour_cost, workHours = $work_time WHERE id = $id";
                $conn->query($sql);
                closeConnection($conn);
                redirect("http://$host:8080/hr/app/add-man.php?r=success");
            }
        } else {
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            closeConnection($conn);
            redirect("http://$host:8080/hr/app/add-emp.php?r=error");
        }
    } else {
        closeConnection($conn);
        redirect("http://$host:8080/hr/app/add-man.php?r=username_error");
    }
}

function render_man_data()
{
    $conn = makeConnection();
    $sql = "SELECT * FROM users WHERE role = 'man'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $first_name = $row["firstName"];
            $last_name = $row["lastName"];
            $username = $row["username"];
            $start_time = $row["startTime"];
            $end_time = $row["endTime"];
            $salary = $row["salary"];
            $day_off = $row["dayOff"];
            $pranch = $row["pranch"];
            echo "
                    <tr>
                        <th scope='row'>$id</th>
                        <td>$first_name</td>
                        <td>$last_name</td>
                        <td>$username</td>
                        <td>$start_time</td>
                        <td>$end_time</td>
                        <td>$salary</td>
                        <td>$day_off</td>
                        <td>$pranch</td>
                        <td>
                            <a class='text-dark mr-3' href='/hr/app/add-man.php/?action=update&id={$id}'><i class='fas fa-edit'></i></a>
                            <a class='text-dark mr-3' href='/hr/app/man-data.php/?action=delete&id={$id}'><i class='fas fa-trash-alt'></i></a>
                            <a class='text-dark' href='/hr/app/emp-report.php/?id={$id}'><i class='far fa-file-alt'></i></a>
                        </td>
                    </tr>
                ";
        }
    }
}

function update_man($id, $first_name, $last_name, $username, $password, $start_time, $end_time, $salary, $day_off, $pranch)
{
    $host = $_SERVER['SERVER_NAME'];
    $conn = makeConnection();
    $first_name = test_input($conn, $first_name);
    $last_name = test_input($conn, $last_name);
    $username = test_input($conn, $username);
    $password = test_input($conn, $password);
    $start_time = test_input($conn, $start_time);
    $end_time = test_input($conn, $end_time);
    $salary = test_input($conn, $salary);
    $day_off = test_input($conn, $day_off);
    $pranch = test_input($conn, $pranch);
    $sql = "UPDATE users SET firstName='$first_name', lastName='$last_name', username='$username', password='$password',startTime='$start_time', endTime='$end_time', salary='$salary', dayOff='$day_off',pranch='$pranch' WHERE id='$id'";
    $conn->query($sql);
    $sql = "SELECT startTime,endTime FROM users WHERE id =$id";
    $result = $conn->query($sql);
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $start_time = make_time_num($row["startTime"]);
        $end_time = make_time_num($row["endTime"]);
        if ($end_time < 12 && $start_time > 12) {
            $end_time = $end_time + 12;
            $start_time = $start_time - 12;
            $work_time = abs($start_time - $end_time);
            $hour_cost = $salary / ($work_time * 30);
            $sql = "UPDATE users SET hourPrice = $hour_cost, workHours = $work_time WHERE id = $id";
            $conn->query($sql);
            closeConnection($conn);
            redirect("http://$host:8080/hr/app/man-data.php?r=update_success");
        } else {
            $work_time = abs($start_time - $end_time);
            $hour_cost = $salary / ($work_time * 30);
            $sql = "UPDATE users SET hourPrice = $hour_cost, workHours = $work_time WHERE id = $id";
            $conn->query($sql);
            closeConnection($conn);
            redirect("http://$host:8080/hr/app/man-data.php?r=update_success");
        }
    }
}

function delete_man($id)
{
    $host = $_SERVER['SERVER_NAME'];
    $conn = makeConnection();
    $sql = "DELETE FROM users WHERE id=$id";
    $conn->query($sql);
    closeConnection($conn);
    redirect("http://$host:8080/hr/app/man-data.php?r=delete-success");
}

function render_emp_list($pranch)
{
    function add_icon($statue)
    {
        if ($statue == 'online') return "<img src='../pics/online.jpg' width='15px' class='mr-2'>";
        if ($statue == 'offline') return "<img src='../pics/Basic_red_dot.png' width='15px' class='mr-2'>";
    }
    $conn = makeConnection();
    $sql = "SELECT * FROM users WHERE role = 'emp' AND pranch = '$pranch'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $first_name = $row["firstName"];
            $last_name = $row["lastName"];
            $stat = $row["statue"];
            echo "<a class='list-group-item list-group-item-action text-center' href='/hr/app/man-panel.php?id=$id&stat=$stat'>" . add_icon($row['statue']) . "$first_name $last_name</a>";
        }
    }
    closeConnection($conn);
}

function att($id, $stat)
{
    $host = $_SERVER['SERVER_NAME'];
    $conn = makeConnection();
    $date = date("Y-m-d");
    $sql = "SELECT * FROM att WHERE user_id = $id AND day = '$date'";
    $result = $conn->query($sql);
    if ($stat == "offline" && $result->num_rows >= 1) {
        closeConnection($conn);
        redirect("http://$host:8080/hr/app/man-panel.php?r=error");
    }
    if ($stat == "offline" && $result->num_rows == 0) {
        $time = date("H:i");
        $sql = "UPDATE users SET statue = 'online' WHERE id = $id";
        $conn->query($sql);
        $sql = "INSERT INTO att (user_id,day,start) VALUES ('$id','$date','$time')";
        $conn->query($sql);
        closeConnection($conn);
        redirect("http://$host:8080/hr/app/man-panel.php?r=start");
    }
    if ($stat == "online") {
        $time = date("H:i");
        $sql = "UPDATE users SET statue = 'offline' WHERE id = $id";
        $conn->query($sql);
        $sql = "SELECT * FROM att WHERE user_id = $id AND day = '$date'";
        $result = $conn->query($sql);
        if ($result->num_rows === 1) {
            //checked
            $sql = "UPDATE `att` SET `finsih`= '$time' WHERE `user_id` = $id AND `day` = '$date'";
            $conn->query($sql);
            $sql = "SELECT start,finsih FROM att WHERE user_id = $id AND day = '$date'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $result->num_rows;
            $start_time = make_time_num($row['start']);
            $end_time = make_time_num($row['finsih']);
            if ($end_time < $start_time) {
                //checked
                $end_time = $end_time + 12;
                $start_time = $start_time - 12;
                $work_time = abs($end_time - $start_time);
                $sql = "UPDATE att SET worked_time = $work_time WHERE user_id = $id AND day = '$date'";
                $conn->query($sql);
                closeConnection($conn);
                redirect("http://$host:8080/hr/app/man-panel.php?r=end");
            } else {
                //checked
                $work_time = abs($start_time - $end_time);
                $sql = "UPDATE att SET worked_time = $work_time WHERE user_id = $id AND day = '$date'";
                $conn->query($sql);
                closeConnection($conn);
                redirect("http://$host:8080/hr/app/man-panel.php?r=end");
            }
        } else if ($result->num_rows === 0) {
            $date = date('Y-m-d', strtotime("-1 days"));
            echo $date;
            $sql = "SELECT `finsih`,`start` FROM att WHERE user_id = $id AND day = '$date'";
            $result = $conn->query($sql);
            if ($result->num_rows === 1) {
                $sql = "UPDATE `att` SET `finsih`= '$time' WHERE `user_id` = $id AND `day` = '$date'";
                $conn->query($sql);
                $sql = "SELECT start,finsih FROM att WHERE user_id = $id AND day = '$date'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $result->num_rows;
                $start_time = make_time_num($row['start']);
                $end_time = make_time_num($row['finsih']);
                if ($end_time < $start_time) {
                    //checked
                    $end_time = $end_time + 12;
                    $start_time = $start_time - 12;
                    $work_time = abs($end_time - $start_time);
                    $sql = "UPDATE att SET worked_time = $work_time WHERE user_id = $id AND day = '$date'";
                    $conn->query($sql);
                    closeConnection($conn);
                    redirect("http://$host:8080/hr/app/man-panel.php?r=end");
                } else {
                    //checked
                    $work_time = abs($start_time - $end_time);
                    $sql = "UPDATE att SET worked_time = $work_time WHERE user_id = $id AND day = '$date'";
                    $conn->query($sql);
                    closeConnection($conn);
                    redirect("http://$host:8080/hr/app/man-panel.php?r=end");
                }
            }
            closeConnection($conn);
        }
    }
}

function att_barcode($id, $stat, $barcode)
{
    $host = $_SERVER['SERVER_NAME'];
    $conn = makeConnection();
    $date = date("Y-m-d");
    $sql = "SELECT * FROM att WHERE user_id = $id AND day = '$date'";
    $result = $conn->query($sql);
    if ($stat == "offline" && $result->num_rows >= 1) {
        closeConnection($conn);
        redirect("http://$host:8080/hr/index.php?r=error&barcode=$barcode");
    }
    if ($stat == "offline" && $result->num_rows == 0) {
        $time = date("H:i");
        $sql = "UPDATE users SET statue = 'online' WHERE id = $id";
        $conn->query($sql);
        $sql = "INSERT INTO att (user_id,day,start) VALUES ('$id','$date','$time')";
        $conn->query($sql);
        closeConnection($conn);
        redirect("http://$host:8080/hr/index.php?r=start&barcode=$barcode");
    }
    if ($stat == "online") {
        $time = date("H:i");
        $sql = "UPDATE users SET statue = 'offline' WHERE id = $id";
        $conn->query($sql);
        $sql = "SELECT * FROM att WHERE user_id = $id AND day = '$date'";
        $result = $conn->query($sql);
        if ($result->num_rows === 1) {
            //checked
            $sql = "UPDATE `att` SET `finsih`= '$time' WHERE `user_id` = $id AND `day` = '$date'";
            $conn->query($sql);
            $sql = "SELECT start,finsih FROM att WHERE user_id = $id AND day = '$date'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $result->num_rows;
            $start_time = make_time_num($row['start']);
            $end_time = make_time_num($row['finsih']);
            if ($end_time < $start_time) {
                //checked
                $end_time = $end_time + 12;
                $start_time = $start_time - 12;
                $work_time = abs($end_time - $start_time);
                $sql = "UPDATE att SET worked_time = $work_time WHERE user_id = $id AND day = '$date'";
                $conn->query($sql);
                closeConnection($conn);
                redirect("http://$host:8080/hr/index.php?r=end&barcode=$barcode");
            } else {
                //checked
                $work_time = abs($start_time - $end_time);
                $sql = "UPDATE att SET worked_time = $work_time WHERE user_id = $id AND day = '$date'";
                $conn->query($sql);
                closeConnection($conn);
                redirect("http://$host:8080/hr/index.php?r=end&barcode=$barcode");
            }
        } else if ($result->num_rows === 0) {
            $date = date('Y-m-d', strtotime("-1 days"));
            echo $date;
            $sql = "SELECT `finsih`,`start` FROM att WHERE user_id = $id AND day = '$date'";
            $result = $conn->query($sql);
            if ($result->num_rows === 1) {
                $sql = "UPDATE `att` SET `finsih`= '$time' WHERE `user_id` = $id AND `day` = '$date'";
                $conn->query($sql);
                $sql = "SELECT start,finsih FROM att WHERE user_id = $id AND day = '$date'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $result->num_rows;
                $start_time = make_time_num($row['start']);
                $end_time = make_time_num($row['finsih']);
                if ($end_time < $start_time) {
                    //checked
                    $end_time = $end_time + 12;
                    $start_time = $start_time - 12;
                    $work_time = abs($end_time - $start_time);
                    $sql = "UPDATE att SET worked_time = $work_time WHERE user_id = $id AND day = '$date'";
                    $conn->query($sql);
                    closeConnection($conn);
                    redirect("http://$host:8080/hr/index.php?r=end&barcode=$barcode");
                } else {
                    //checked
                    $work_time = abs($start_time - $end_time);
                    $sql = "UPDATE att SET worked_time = $work_time WHERE user_id = $id AND day = '$date'";
                    $conn->query($sql);
                    closeConnection($conn);
                    redirect("http://$host:8080/hr/index.php?r=end&barcode=$barcode");
                }
            }
            closeConnection($conn);
        }
    }
}

function final_report_data($id)
{
    $conn = makeConnection();
    $sql = "SELECT firstName, lastName, salary, hourPrice FROM users WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        closeConnection($conn);
        return $result->fetch_assoc();
    }
}

function emp_final_report($id, $year, $month)
{
    $total_time_worked = 0;
    $total_bounes = 0;
    $total_subtract = 0;
    $total_loan = 0;
    $conn = makeConnection();
    $sql = "SELECT * FROM att WHERE `day` LIKE '$year-$month%' AND `user_id` = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $date = $row['day'];
            $start = $row['start'];
            $finish = $row['finsih'];
            $worked_time = $row['worked_time'];
            $bounes = isset($row['bounes']) ? $row['bounes'] : '-';
            $subtract = isset($row['subtract']) ? $row['subtract'] : '-';
            $note = isset($row['note']) ? $row['note'] : '-';;
            echo "
                    <tr>
                        <td style='font-size:2.7rem'>$worked_time</td>
                        <td style='font-size:2.7rem'>$finish</td>
                        <td style='font-size:2.7rem'>$start</td>
                        <td style='font-size:2.7rem'>$date</td>
                    </tr>
                ";
            $total_time_worked += $worked_time;
            $total_bounes += $row['bounes'];
            $total_subtract += $row['subtract'];
        }
    }
    $sql = "SELECT loan_value 
                FROM loan
                WHERE `date` LIKE '$year-$month%' AND `employee_id` = $id";
    $result = $conn->query($sql);
    if ($result->num_rows >= 0) {
        while ($row = $result->fetch_assoc()) {
            $loan = $row['loan_value'];
            $total_loan += $loan;
        }
    }
    closeConnection($conn);
    $data = [$total_time_worked, $total_bounes, $total_subtract, $total_loan];
    return $data;
}

function closeConnection($conn)
{
    $conn->close();
}

function make_time_num($time)
{
    $x = 0;
    for ($i = 0; $i < 5; $i++) {
        if ($time[$i] != ":") {
            // echo "time: ".$i." number: ".$x;
            $x = $x * 10;
            switch ($time[$i]) {
                case '1':
                    $x = $x + 1;
                    break;
                case '2':
                    $x = $x + 2;
                    break;
                case '3':
                    $x = $x + 3;
                    break;
                case '4':
                    $x = $x + 4;
                    break;
                case '5':
                    $x = $x + 5;
                    break;
                case '6':
                    $x = $x + 6;
                    break;
                case '7':
                    $x = $x + 7;
                    break;
                case '8':
                    $x = $x + 8;
                    break;
                case '9':
                    $x = $x + 9;
                    break;
            }
        }
    }
    $hours = floor($x / 100);
    $mins = (abs($x / 100) - $hours) * (5 / 3);
    return ($hours + $mins);
}

function app_handler($admin_only = null)
{
    $host = $_SERVER['SERVER_NAME'];
    if (!isset($_SESSION['username'])) redirect("http://$host:8080/hr/login.php?r=access_denied");
    if (isset($admin_only)) if ($_SESSION['role'] != 'admin') die(redirect("http://$host:8080/hr/app/man-panel.php"));
}

function redirect($url)
{
    echo ('<script>window.location.replace("' . $url . '");</script>');
}

function test_input($conn, $data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

if (isset($_POST['login'])) {
    login($_POST['username'], $_POST['password']);
}
if (isset($_POST['add-emp'])) {
    add_emp($_POST['first-name'], $_POST['last-name'], $_POST['start-time'], $_POST['end-time'], $_POST['salary'], $_POST['day-off'], $_POST['pranch']);
}
if (isset($_POST['update-emp'])) {
    update_emp($_POST['id'], $_POST['first-name'], $_POST['last-name'], $_POST['start-time'], $_POST['end-time'], $_POST['salary'], $_POST['day-off'], $_POST['pranch']);
}
if (isset($_POST['add-man'])) {
    add_man($_POST['first-name'], $_POST['last-name'], $_POST['username'], $_POST['password'], $_POST['start-time'], $_POST['end-time'], $_POST['salary'], $_POST['day-off'], $_POST['pranch']);
}
if (isset($_POST['update-man'])) {
    update_man($_POST['id'], $_POST['first-name'], $_POST['last-name'], $_POST['username'], $_POST['password'], $_POST['start-time'], $_POST['end-time'], $_POST['salary'], $_POST['day-off'], $_POST['pranch']);
}
if (isset($_GET['action']) && $_GET['action'] == 'emp_list') {
    update_man($_POST['id'], $_POST['first-name'], $_POST['last-name'], $_POST['username'], $_POST['password'], $_POST['start-time'], $_POST['end-time'], $_POST['salary'], $_POST['day-off'], $_POST['pranch']);
}
if (isset($_POST['start']) || isset($_POST['end'])) {
    att($_POST['id'], $_POST['stat']);
}
