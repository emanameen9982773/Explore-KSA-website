<?php


include("../database_connection.php");


$region_name= $_POST['region_name'];
$headline=$_POST['headline'];
$nature=$_POST['nature'];
$location=$_POST['location'];
$description=$_POST['description'];
$icon=$_FILES['icon'];
$icon_name=$_FILES['icon']['name'];
$target_dir = "../image/";

if(!empty($icon_name)){
    move_uploaded_file($icon['tmp_name'],$target_dir.$icon_name);
}
else{
    $icon_name="defaultIcon.webp";
 
}
$query = "INSERT INTO Regions (region_name, headline, nature, location, description, icon_path)
          VALUES ('$region_name', '$headline', '$nature', '$location', '$description', '$icon_name')";

try {
    mysqli_query($conn, $query);
} 
catch (mysqli_sql_exception $e) { 
    // if the region already exists
    if($e -> getCode()==1062){
        header("location: addContent.php?error=duplicate");
        exit();
    }
    header("location: addContent.php?success=0");
    exit();
}

$region_id=mysqli_insert_id($conn);

$landmarksArr=$_POST['landmarks'];
foreach ($landmarksArr as $landmark){
    if(!empty($landmark)) {
        $query2 = "INSERT INTO Landmarks (landmark, region_id) VALUES ('$landmark', '$region_id')";
        mysqli_query($conn, $query2);
    }
}


$activities=$_POST['activities'];
foreach ($activities as $activity){
    if(!empty($activity)){
        $query2="INSERT INTO Activities (activity,region_id) VALUES ('$activity','$region_id');";
        mysqli_query($conn,$query2);
    }
}

if (isset($_FILES['images'])) {
    foreach ($_FILES['images']['name'] as $key => $name) {
        if (!empty($name)) {
            $tmp_name = $_FILES['images']['tmp_name'][$key];
            $file_name = basename($name);
            $target_file = $target_dir . $file_name;

            if (move_uploaded_file($tmp_name, $target_file)) {
                $query3= "INSERT INTO Images (image_path, region_id) VALUES ('$file_name', '$region_id')";
                mysqli_query($conn, $query3);
            }
        }
    }
}

header("location: dashboard.php?success=1");
exit();

?>