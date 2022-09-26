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
<link href="output.css" rel="stylesheet">
<title>Weekly Meal Planner</title>

</head>

<body class="">
<header class="m-4">
<h1 class="text-pink-300 font-bold text-center">Weekly Meal Planner</h1>
<h3 class="text-center italic text-sm">Let the app organize your meals for you to eat this week.</h3>
</header>
<!-- <input type="submit" name="launch" value="Plan your meals">  -->

<div class="w-full h-full grid md:grid-cols-3 ">
    <div class="h-full bg-pink-200 centered md:h-screen">
        <div>
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 m-4" method="POST">
            <label for="meal">Add a meal to the list :</label><br>
            <input  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" name="meal" value=""><br><br>
            <input class="bg-pink-300 hover:bg-pink-200 text-white font-semibold py-2     px-4 rounded" type="submit" value="Add this meal">
            <input class="bg-pink-300 hover:bg-pink-200 text-white font-semibold py-2 px-4 rounded" type="submit" name="reset" value="Reset">

            <?php
                if(isset($_POST["meal"])) {
                    if (strlen($_POST["meal"]) < 40) {
                        if (in_array(strtolower($_POST["meal"]), $_SESSION["mealsList"])) { // checks if the  value is in array
                            echo "<p class='font-thin text-sm'>This meal is already in the list.</p>";
                        } else {
                            $savedData = $_SESSION["mealsList"];
                            $savedData[] = strtolower($_POST["meal"]);
                            $_SESSION["mealsList"] = $savedData;
                        }
                    } else {
                        echo "Va te faire foutre (Meal length should be shorter)";
                    }
                }
            ?>
            </form>




        </div>


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
                $week[$day][] = array_shift($slicedMeals); // puts 2 meals for a day
            }
        }
    ?>



<!-- List of meals of user -->
<div class="text-center">
    <h2 class="font-semibold">My meals list</h2>
    <?php
        echo "<li>";
        foreach($_SESSION["mealsList"] as $meal) {
            echo "<ul class='m-2 bg-white hover:bg-gray-100 text-gray-800 rounded-full'>". ucfirst($meal) . "</ul>"; // Prints entered meals w First capital letter
        }
        echo "</li>";
    ?>
</div>
</div>

<!-- Table print -->
<div class="col-span-2 bg-yellow-100 centered md:h-screen">
    <h2 class="text-center font-semibold my-3">Meal Planning</h2>
    <table class="table-auto bg-white shadow-md rounded m-auto">
        <thead>
            <?php
                foreach($week as $day => $mealsTab) {
                    echo "<th class='px-5 py-3'>" . $day . "</th>";
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
                foreach($line as $element) {
                    echo "<td  class='border px-5 py-3 text-sm text-center hover:bg-gray-100'>". ucfirst($element) . "</td>"; // echo the meals in the array                   
                }
                echo "</tr>";
            }
        ?>
    </table>
</div>
</div>


</body>
</html>