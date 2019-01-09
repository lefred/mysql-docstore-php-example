<?php
include_once("includes/header.php");
if (isset($_GET['delete'])) {
    if ($session) {
        $schema = $session->getSchema("docstore");
        $collection = $schema->getCollection("restaurants");
        $collection->remove("_id='${_GET['delete']}'")->execute();
        echo "Document deleted";
    }
}
else {
?>
<form method="post">
 <p>name : <input type="text" name="restaurant_name" /></p>
 <p>borough:  <input type="text" name="restaurant_borough" /></p>
 <p>cuisine: <input type="text" name="restaurant_cuisine" /></p>
 <input type="submit" name="submit" value="Submit" />
</form>
<?php
 if (isset($_POST['submit']))
 {
    if (isset($_POST['restaurant_name']) and strlen($_POST['restaurant_name'])>0)
    {
       $search = 'name like "%' . $_POST['restaurant_name'] . '%"';
    }
    if (isset($_POST['restaurant_borough']) and strlen($_POST['restaurant_borough'])>0)
    {
        if (isset($search))
        {
            $search = $search . " and ";
        }
        $search = $search . 'borough like "%' . $_POST['restaurant_borough'] . '%"';
    }
    if (isset($_POST['restaurant_cuisine']) and strlen($_POST['restaurant_cuisine'])>0)
    {
        if (isset($search))
        {
            $search = $search . " and ";
        }
        $search = $search . 'cuisine like "%' . $_POST['restaurant_cuisine'] . '%"';
    }
    if ($session) {
        $schema = $session->getSchema("docstore");
        $collection = $schema->getCollection("restaurants");
        $results = $collection->find($search)->execute()->fetchAll();
        if (is_array($results[0])) { $tot=count($results); } else {$tot=0;}
        echo "got $tot restaurants matching <strong><code>restaurants.find('$search')</code></strong>";
        echo "<hr><table><tr><th align='left'>Name</th><th align='left'>Borough</th>";
        echo "<th align='right'>Cuisine</th></tr>";
        foreach ($results as $doc) {
            echo "<tr><td><a href='?id=${doc[_id]}'>${doc[name]}</a></td>";
            echo "<td>${doc[borough]}</td><td align='right'>${doc[cuisine]}</td></tr>";
        } 
        echo "</table>";
    }
  }
  if (isset($_GET['id'])) {
    if ($session) {
        $schema = $session->getSchema("docstore");
        $collection = $schema->getCollection("restaurants");
        echo "<hr><table width='100%'><tr><td><pre>";
        $doc = $collection->getOne($_GET['id']);
        print_r(json_encode($doc, JSON_PRETTY_PRINT));
        echo "</pre></td><td align='center' valign='top'>";
       echo "<h2>" . $doc{'name'} ."</h2>";

        echo "<h3>(" . $doc{'borough'} . ")</h3>";
        echo "<h3>" . $doc{'cuisine'} . "</h3>";
        $result = $session->sql('select avg(rating) AS score, doc->>\'$.address.coord\' AS coord from docstore.restaurants, JSON_TABLE(doc, "$" columns(nested path "$.grades[*]" columns (rating int path "$.score"))) as jt where _id="'. $_GET['id'] .'";')->execute();
        foreach ($result as $doc) {
            echo "<h1>Average: " . number_format((float)$doc['score'], 2, '.', '') . "</h1>";
        }
        #echo '<iframe width="420" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://www.openlinkmap.org/small.php?lon=-73&lat=40&zoom=8" style="border: 1px solid black"></iframe>';
        $coords = explode(',', $doc['coord']);
        $lon=substr($coords[0],1);
        $lat=trim(rtrim($coords[1],"]"));
        echo '<a href="http://www.openstreetmap.org/export/embed.html?bbox='.$lon.'%2C'.$lat.'%2C'.$lon.'%2C'.$lat.'&layer=mapnik&marker='.$lat.'%2C'.$lon.'">link to map</a><br>';
        echo '<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://www.openstreetmap.org/export/embed.html?bbox='.$lon.'%2C'.$lat.'%2C'.$lon.'%2C'.$lat.'&layer=mapnik&marker='.$lat.'%2C'.$lon.'" style="border: 1px solid black"></iframe>';
        echo "</td><tr>";
        echo "<tr><td colspan='2' align='center'><a href='?delete=".$_GET['id']."'>remove</a></td></tr>";
        echo "</table>";
    }
  }
}
include_once("includes/footer.php");
?>
