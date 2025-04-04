<?php 
include "includes/session.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $vehicle_type = $_POST['vehicle_type_id'];
    $fuel_type = $_POST['fuel_type'];
    $transmission = $_POST['transmission'];
    $engine_capacity = $_POST['engine_capacity'];
    $body_type = $_POST['body_type'];
    $number_of_doors = $_POST['number_of_doors'];
    $number_of_seats = $_POST['number_of_seats'];
    $abs = isset($_POST['abs']) ? 1 : 0;
    $traction_control = isset($_POST['traction_control']) ? 1 : 0;
    $license_plate = $_POST['license_plate'];
    $insurance_provider = $_POST['insurance_provider'];
    $added_by = $_SESSION['SESS_USER_ID'];

    // Handle multiple image uploads
    $upload_dir = '../images/vehicles/';
    $photo_filenames = [];

    if (!empty($_FILES['photos']['name'][0])) {
        foreach ($_FILES['photos']['name'] as $key => $name) {
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $new_filename = uniqid('vehicle_') . '.' . $ext;
            if (move_uploaded_file($_FILES['photos']['tmp_name'][$key], $upload_dir . $new_filename)) {
                $photo_filenames[] = $new_filename;
            }
        }
    }

    // Convert images array to JSON for storing in DB
    $photos_json = !empty($photo_filenames) ? json_encode($photo_filenames) : null;

    try {
        $stmt = $conn->prepare("INSERT INTO vehicles 
            (make, model, year, vehicle_type, fuel_type, transmission, engine_capacity, 
            body_type, number_of_doors, number_of_seats, abs, traction_control, 
            license_plate, insurance_provider, photos, added_by) 
            VALUES 
            (:make, :model, :year, :vehicle_type, :fuel_type, :transmission, :engine_capacity, 
            :body_type, :number_of_doors, :number_of_seats, :abs, :traction_control, 
            :license_plate, :insurance_provider, :photos, :added_by)");
            
        $stmt->execute([
            'make' => $make,
            'model' => $model,
            'year' => $year,
            'vehicle_type' => $vehicle_type,
            'fuel_type' => $fuel_type,
            'transmission' => $transmission,
            'engine_capacity' => $engine_capacity,
            'body_type' => $body_type,
            'number_of_doors' => $number_of_doors,
            'number_of_seats' => $number_of_seats,
            'abs' => $abs,
            'traction_control' => $traction_control,
            'license_plate' => $license_plate,
            'insurance_provider' => $insurance_provider,
            'photos' => $photos_json,
            'added_by' => $added_by
        ]);

        $_SESSION['success'] = "Vehicle added successfully!";
        header("Location: add_vehicle.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: add_vehicle.php");
        exit;
    }
}

?>