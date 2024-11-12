<?php
require_once("data.php");


class Database
{
  protected $conn;

  public function __construct()
  {
    $this->conn = new mysqli(SERVER, USER, PASS, DATABASE);

			if ($this->conn->connect_errno) {
				echo "Koneksi Failed: ".$this->conn->connect_error;
			}
  }
}
?>