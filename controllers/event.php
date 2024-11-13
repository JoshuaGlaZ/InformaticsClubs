<?php
require_once __DIR__ . '/../config/db.php';

class Event extends Database
{
  public function __construct()
  {
    parent::__construct();
  }

  public function addEvent($eventname, $eventdate, $description)
  {
    $sql = "INSERT INTO event (name, date, description) values(?,?,?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sss", $eventname, $eventdate, $description);

    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      throw new Exception("Error inserting event: " . $stmt->error);
    }
  }

  public function getEvents()
  {
    $sql = "SELECT * from event";
    $result = $this->conn->query($sql);
    return $result;
  }

  public function updateEvent($eventname, $eventdate, $eventdesc, $idevent)
  {
    $sql = "UPDATE event set name = ?, date = ?, description = ? where idevent =?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_Param("sssi", $eventname, $eventdate, $eventdesc, $idevent);

    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      throw new Exception("Error updating event: " . $stmt->error);
    }
  }

  public function deleteEvent($idevent)
  {
    $sql = "DELETE FROM event WHERE idevent = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $idevent);

    if ($stmt->execute()) {
      $stmt->close();
      return true;
    } else {
      $stmt->close();
      throw new Exception("Error deleting event: " . $stmt->error);
    }
  }
}
