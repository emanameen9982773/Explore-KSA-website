<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../database_connection.php");

if (!isset($_POST['region_id']) || !is_numeric($_POST['region_id'])) {
    header("location: dashboard.php");
    exit();
}

$region_id   = (int) $_POST['region_id'];
$region_name = mysqli_real_escape_string($conn, $_POST['region_name']);
$headline    = mysqli_real_escape_string($conn, $_POST['headline']);
$nature      = mysqli_real_escape_string($conn, $_POST['nature']);
$location    = mysqli_real_escape_string($conn, $_POST['location']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$target_dir  = "../image/";

$icon_name = null; // null = keep existing

if (!empty($_FILES['icon']['name'])) {
    $icon_name = basename($_FILES['icon']['name']);
    move_uploaded_file($_FILES['icon']['tmp_name'], $target_dir . $icon_name);
}

if ($icon_name) {
    $query = "UPDATE Regions
              SET region_name = '$region_name',
                  headline    = '$headline',
                  nature      = '$nature',
                  location    = '$location',
                  description = '$description',
                  icon_path   = '$icon_name'
              WHERE region_id = $region_id";
} else {
    $query = "UPDATE Regions
              SET region_name = '$region_name',
                  headline    = '$headline',
                  nature      = '$nature',
                  location    = '$location',
                  description = '$description'
              WHERE region_id = $region_id";
}

try {
    $result = mysqli_query($conn, $query);
    if (!$result) throw new Exception(mysqli_error($conn));
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        header("location: editRegion.php?region_id=$region_id&error=duplicate");
        exit();
    }
    header("location: editRegion.php?region_id=$region_id&success=0");
    exit();
} catch (Exception $e) {
    header("location: editRegion.php?region_id=$region_id&success=0");
    exit();
}

mysqli_query($conn, "DELETE FROM Landmarks WHERE region_id = $region_id");

if (!empty($_POST['landmarks'])) {
    foreach ($_POST['landmarks'] as $landmark) {
        $landmark = mysqli_real_escape_string($conn, trim($landmark));
        if ($landmark !== '') {
            mysqli_query($conn, "INSERT INTO Landmarks (landmark, region_id)
                                 VALUES ('$landmark', $region_id)");
        }
    }
}

mysqli_query($conn, "DELETE FROM Activities WHERE region_id = $region_id");

if (!empty($_POST['activities'])) {
    foreach ($_POST['activities'] as $activity) {
        $activity = mysqli_real_escape_string($conn, trim($activity));
        if ($activity !== '') {
            mysqli_query($conn, "INSERT INTO Activities (activity, region_id)
                                 VALUES ('$activity', $region_id)");
        }
    }
}

if (!empty($_POST['delete_images'])) {
    foreach ($_POST['delete_images'] as $image_id) {
        $image_id = (int) $image_id;

        $img_result = mysqli_query($conn, "SELECT image_path FROM Images
                                           WHERE image_id = $image_id
                                             AND region_id = $region_id");
        if ($img_result && mysqli_num_rows($img_result) > 0) {
            $img_row   = mysqli_fetch_assoc($img_result);
            $file_path = $target_dir . $img_row['image_path'];
            if (file_exists($file_path) && $img_row['image_path'] !== 'defaultIcon.webp') {
                unlink($file_path);
            }
            mysqli_query($conn, "DELETE FROM Images
                                 WHERE image_id = $image_id AND region_id = $region_id");
        }
    }
}

if (!empty($_FILES['images']['name'][0])) {
    foreach ($_FILES['images']['name'] as $key => $name) {
        if (!empty($name)) {
            $tmp_name  = $_FILES['images']['tmp_name'][$key];
            $file_name = basename($name);
            $target_file = $target_dir . $file_name;

            if (move_uploaded_file($tmp_name, $target_file)) {
                $safe_file = mysqli_real_escape_string($conn, $file_name);
                mysqli_query($conn, "INSERT INTO Images (image_path, region_id)
                                     VALUES ('$safe_file', $region_id)");
            }
        }
    }
}

header("location: dashboard.php?success=2");
exit();
?>