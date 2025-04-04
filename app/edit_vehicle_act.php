<?php
include "includes/session.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_id = $_POST['vehicle_id'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $vehicle_type = $_POST['vehicle_type_id'];
    $fuel_type = $_POST['fuel_type'];
    $transmission = $_POST['transmission'];
    $engine_capacity = $_POST['engine_capacity'];
    $license_plate = $_POST['license_plate'];
    $insurance_provider = $_POST['insurance_provider'];

    try {
        // Get existing photos from DB
        $stmt = $conn->prepare("SELECT photos FROM vehicles WHERE id = ?");
        $stmt->execute([$vehicle_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $existing_photos = json_decode($row['photos'], true) ?? [];

        // Handle photo deletions
        $photos_to_keep = $existing_photos;
        if (!empty($_POST['delete_photos'])) {
            foreach ($_POST['delete_photos'] as $delete_photo) {
                $photo_path = "../images/vehicles/" . $delete_photo;
                if (file_exists($photo_path)) {
                    unlink($photo_path); // Delete file from server
                }
                $photos_to_keep = array_diff($photos_to_keep, [$delete_photo]); // Remove from array
            }
        }

        // Handle new photo uploads
        $upload_dir = "../images/vehicles/";
        if (!empty($_FILES['new_photos']['name'][0])) {
            foreach ($_FILES['new_photos']['name'] as $key => $name) {
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $new_filename = uniqid("vehicle_") . "." . $ext;
                
                if (move_uploaded_file($_FILES['new_photos']['tmp_name'][$key], $upload_dir . $new_filename)) {
                    $photos_to_keep[] = $new_filename; // Add to list of photos
                }
            }
        }

        // Convert updated photo list to JSON
        $photos_json = json_encode($photos_to_keep);

        // Update vehicle details in the database
        $stmt = $conn->prepare("UPDATE vehicles SET 
            make = ?, model = ?, year = ?, vehicle_type = ?, fuel_type = ?, 
            transmission = ?, engine_capacity = ?, license_plate = ?, 
            insurance_provider = ?, photos = ? WHERE id = ?");

        $stmt->execute([
            $make, $model, $year, $vehicle_type, $fuel_type, 
            $transmission, $engine_capacity, $license_plate, 
            $insurance_provider, $photos_json, $vehicle_id
        ]);

        $_SESSION['success'] = "Vehicle updated successfully!";
        header("Location: edit_vehicle.php?id=$vehicle_id");
        exit;

    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: edit_vehicle.php?id=$vehicle_id");
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid request!";
    header("Location: vehicles.php");
    exit;
}
?>