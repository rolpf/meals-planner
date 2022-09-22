<?php
//  Starting the session
    session_start();
if (!isset($_SESSION['mealsList'])) {
    $_SESSION["mealsList"]= [];
}

if(isset($_POST['reset'])) {
    session_destroy();
    header("Refresh:0");
}
?>

<!doctype html>
<hmtl lang="fr">
<head>
<meta charset="utf-8">
<title>Weekly Meal Planner</title>

</head>

<body>
<h1>Weekly Meal Planner</h1>
<h3>Let the app suggest meals for you to eat this week.</h3>

<form method="POST">
  <label for="meal">Add a meal to the list :</label><br>
  <input type="text" name="meal" value=""><br><br>
  <input type="submit" value="Add this meal">
  <input type="submit" name="reset" value="Reset"> 
</form>



<?php
if(isset($_POST["meal"])) {
    if (strlen($_POST["meal"]) < 40) {

    $savedData = $_SESSION["mealsList"];
    $savedData[] = $_POST["meal"];
    $_SESSION["mealsList"] = $savedData;       
    }
    else {
        echo "Va te faire foutre (Meal length should be shorter)";
    }
}
?>


<!-- RANDOMIZE THE MEALS LIST -->

<?php

$days = ["Mon","Tue","We","Thu","Fri","Sat","Sun"];
$randomizedMeals = []; // secure 

$randomizedMeals = $_SESSION["mealsList"];


// Check if there are enough meals for the week (14 because 2 per day) or not
if (count($randomizedMeals) >= 14) {
    $slicedMeals = array_slice($randomizedMeals, 0, 14);
} else {
    $missing = 14 - count($randomizedMeals);
    $slicedMeals = array_slice($randomizedMeals, 0, $missing);
    $slicedMeals = array_merge($slicedMeals,$randomizedMeals);
    
}
 
shuffle($slicedMeals);

$week = [];

foreach($days as $day) {
    for($i=0;$i<2;$i++) {
    $week[$day][] = array_shift($slicedMeals);
    }
}
?>


<!-- Affichage du tableau  -->
<h2>Meal Planning</h2>

<table>
    <thead>
    <?php
    foreach($week as $day => $mealsTab) {
        echo "<th>" . $day . "</th>";
        $lines = [];
        $line1[] = $mealsTab[0];
        $line2[] = $mealsTab[1];
        $lines[] = $line1;
        $lines[] = $line2;
    }
        ?>
    </thead>
<?php
foreach($lines as $line) {
       echo "<tr>";
        foreach($line as $element){
            echo "<td>". $element . "</td>";
        }
       echo "</tr>";
}



// foreach($week as $day => $mealTabs) {
//     echo "<tr>
//     <td> echo". $day.";</td>
//     <td><?php echo $val['name']; </td>
//     <td>echo $val['age']; </td>
//   </tr>";
  
// }

?>
</table>



<h2>My meal list</h2>

<?php
echo "<li>";
foreach($_SESSION["mealsList"] as $meal) {
    echo "<ul>". $meal . "</ul>";
}
echo "</li>";

?>


</body>
</html>