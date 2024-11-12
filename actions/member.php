<?php
require_once("../controllers/member.php");

session_start();
try {
  $member = new Member();
  if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Sign Up') {
      $firstname = $_POST['firstname'];
      $lastname = $_POST['lastname'];
      $username = $_POST['username'];
      $password = $_POST['password'];
      $confirmpassword = $_POST['confirmpassword'];
      if ($password != $confirmpassword) {
        throw new Exception("Passwords do not match!");
      }      

      $member->register($firstname,$lastname, $username, $password);
    } else if ($_POST['submit'] == 'Sign In') {
      $username = $_POST['username'];
      $password = $_POST['password'];
      $rememberMe = isset($_POST['remember_me']);

      $member->login($username, $password, $rememberMe);
    }
  } else {
    throw new Exception("POST Error");
  }
} catch (Exception $e) {
  $_SESSION['error'] = $e->getMessage();
  header("Location: ../login.php");
  exit();
}
