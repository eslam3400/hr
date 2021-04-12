<?php

include('../Model/Model.php');

class Employee
{
  public $id;
  public $fname;
  public $lname;
  public $shiftStart;
  public $shiftEnd;
  public $salary;
  public $pranch;

  function get($_employeeId)
  {
    $employ = new Model('users');
    return $employ->get()->where('id', $_employeeId)->result;
  }

  function add($_employeeData)
  {
    $employ = new Model('users');

    $employ->set($_employeeData);
  }
}

// $emp = new Employee();
// // $emp->add(["firstName" => "emp", "lastName" => "emp"]);
// print_r($emp->get(10));
