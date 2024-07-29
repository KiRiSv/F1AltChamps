<html>
<body>

    Hello
    <?php 
    $query = "SELECT driver_id";
    for ($x = 0; $x < 34; $x++){
        $query .= ", " . $x . " * " . $points[$x];
    }
    $query .= " FROM bantable";
    ?>
</body>
</html>