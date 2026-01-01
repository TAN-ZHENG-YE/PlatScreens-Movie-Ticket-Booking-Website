<?php 

$query = $_GET['productName'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $query ?> - Search</title>
</head>
<body>
    <h1><?php echo $query; ?></h1>
</body>
</html>