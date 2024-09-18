<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Event</title>
</head>
<body>
    <form method="POST" enctype="multipart/form-data" action="insertevent_proses.php">
        <label>Event Name</label>
        <input type="text" name="eventName"><br>
        <label>Event Date</label>
        <input type="date" name="eventDate"><br>
        <label>Event Description</label>
        <input type="text" name="eventDescription"><br>
        <input type="submit" name="submit" value="Insert">  

    </form>
</body>
</html>