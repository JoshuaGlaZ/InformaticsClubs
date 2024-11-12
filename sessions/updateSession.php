<?php
session_start(); 

$fields = $_SESSION['fields'] ?? []; 

if (isset($_POST['values'])) {
    $values = $_POST['values'];
    $_SESSION['values'] = $_POST['values']; 
    $response = [];

    // Validate that the number of values matches the number of fields
    if (count($values) === count($fields)) {
        foreach ($fields as $index => $field) {
            $response[$field] = $values[$index]; 
        }
        echo json_encode(['status' => 'success', 'data' => $response]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Mismatch between fields and values']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No values data received']);
}
?>
