<?php
require_once("../controllers/event.php");

session_start();
try {
  $event = new Event();

  if (isset($_POST['submit'])) {
    if (isset($_POST['insert'])) {
      $name = $_POST['name'];
      $date = $_POST['date'];
      $description = $_POST['description'];

      $event->addEvent($name, $date, $description);
      $_SESSION['success'] = "Event inserted successfully!";
      header("Location: ../admin_homepage.php?table=event");
    } else if (isset($_POST['update'])) {
      $idevent = $_POST['idevent'];
      $eventname = $_POST['name'];
      $eventdate = $_POST['date'];
      $eventdesc = $_POST['description'];

      $event->updateEvent($eventname, $eventdate, $eventdesc, $idevent);
      $_SESSION['success'] = "Event updated successfully!";
      header("Location: ../admin_homepage.php?table=event");
    }
  } else if (isset($_GET['id'])) {
    $idevent = $_GET['id'];

    $event->deleteEvent($idevent);
    $_SESSION['success'] = "Event " . $idevent . " deleted successfully!";
    header("Location: ../admin_homepage.php?table=event");
    exit();
  }
} catch (Exception $e) {
  $_SESSION['error'] = $e->getMessage();
  header("Location: ../admin_homepage.php?table=event");
  exit();
}
