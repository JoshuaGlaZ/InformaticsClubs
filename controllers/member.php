<?php
require_once __DIR__ . '/../config/db.php';

class Member extends Database
{

  public function __construct()
  {
    parent::__construct();
  }

  public function login($username, $password, $rememberMe = false)
  {
    $stmt = $this->conn->prepare(query: "SELECT idmember, password, profile FROM member WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $stmt->bind_result($idmember, $hash_pass, $profile);
      $stmt->fetch();

      if (password_verify($password, $hash_pass)) {
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['idmember'] = $idmember;

        if ($rememberMe) {
          $token = bin2hex(random_bytes(16));
          $hashedToken = hash('sha256', $token);
          setcookie('remember_me', $hashedToken, time() + (86400 * 30), "/");
          $_SESSION['remember_me_token'] = $hashedToken;
        }

        $redirect = ($profile === 'member') ? "index.php" : "admin_homepage.php";
        header("Location: ../$redirect");

        $stmt->close();
        return true;
      } else {
        throw new Exception('Invalid username or password');
      }
    } else {
      throw new Exception('Invalid username or password');
    }
  }
  public function register($firstname, $lastname, $username, $password)
  {
    $stmt = $this->conn->prepare("SELECT idmember FROM member WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows <= 0) {
      $hash_pass = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $this->conn->prepare("INSERT INTO member (fname, lname, username, password, profile) VALUES (?, ?, ?, ?, 'member')");
      $stmt->bind_param("ssss", $firstname, $lastname, $username, $hash_pass);

      if ($stmt->execute()) {
        header("Location: ../admin_homepage.php");
        $stmt->close();
        return true;
      } else {
        throw new Exception('Insert Failed: ' . $stmt->error);
      }
    } else {
      throw new Exception('Username already taken \n' . $stmt->error);
    }
  }

  public function isAdmin($username)
  {
      $sql = "SELECT profile FROM member WHERE username = ?";
      $stmt = $this->conn->prepare($sql);
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $stmt->bind_result($profile);
      $stmt->fetch();
      $stmt->close();

      return $profile === 'admin';
  }
}
