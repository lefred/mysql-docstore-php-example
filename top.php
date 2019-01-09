<?php
include_once("includes/header.php");
?>
<hr>
<h3>Top of the best restaurant for each type of cuisine</h3>
<?php
if ($session) {
    $results = $session->sql('WITH cte1 AS (SELECT doc->>"$._id" AS _id, doc->>"$.name" AS name,
        doc->>"$.cuisine" AS cuisine,
        (SELECT AVG(score) FROM JSON_TABLE(doc, "$.grades[*]" COLUMNS (score INT
         PATH "$.score")) AS r) AS avg_score FROM docstore.restaurants) SELECT *, RANK()
         OVER ( PARTITION BY cuisine ORDER BY avg_score DESC) AS `rank`
         FROM cte1 ORDER BY `rank`, avg_score DESC LIMIT 10;')->execute();
    echo "<hr><table><tr><th>Name</th><th>Cuisine</th><th>Average Score</th></tr>";
    foreach ($results as $doc) {
            echo "<tr><td><a href='index.php?id=${doc[_id]}'>${doc[name]}</a></td>";
            echo "<td>${doc[cuisine]}</td><td align='right'>${doc[avg_score]}</td></tr>";
    } 
    echo "</table>";
}
include_once("includes/footer.php");
?>
