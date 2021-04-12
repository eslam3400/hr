<?php

include('../Model/Model.php');

class Auth
{
  function login($_user)
  {
    $userModel = new Model("users");
    $user = $userModel->get()->where('username', $_user['username'])->where('password', $_user['password'])->result;
    if (count($user) == 1) print_r($user);
    else echo "wrong details";
  }
}

// $auth = new Auth();
// $auth->login(["username" => "eslam", "password" => "eslam"]);
