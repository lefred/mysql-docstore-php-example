<?php
include_once("includes/header.php");
?>
<form method="post">
 <p>name : <input type="text" name="restaurant_name" /></p>
 <p>borough:  <input type="text" name="restaurant_borough" /></p>
 <p>cuisine: <input type="text" name="restaurant_cuisine" /></p>
 <input type="submit" name="submit" value="Add" />
</form>
<?php
$ok=True;
if (isset($_POST['submit']))
{
    if (isset($_POST['restaurant_name']) and strlen($_POST['restaurant_name'])>0)
    {
       $restaurant_name=$_POST['restaurant_name'];
    }
    else { echo "<p><strong>Restaurant's name is empty!</strong></p>"; $ok=False; }
    if (isset($_POST['restaurant_borough']) and strlen($_POST['restaurant_borough'])>0)
    {
       $restaurant_borough=$_POST['restaurant_borough'];
    }
    else { echo "<p><strong>Restaurant's borough is empty!</strong></p>"; $ok=False; }
    if (isset($_POST['restaurant_cuisine']) and strlen($_POST['restaurant_cuisine'])>0)
    {
       $restaurant_cuisine=$_POST['restaurant_cuisine'];
    }
    else { echo "<p><strong>Restaurant's cuisine is empty!</strong></p>"; $ok=False; }
    if ($session and $ok) {
        $schema = $session->getSchema("docstore");
        $collection = $schema->getCollection("restaurants");
        $document = "{\"name\": \"$restaurant_name\", \"borough\": \"$restaurant_borough\",
                     \"cuisine\": \"$restaurant_cuisine\"}";
        $result = $collection->add($document)->execute();
        $id = $result->getGeneratedIds();
        echo "New document added with id <strong>${id[0]}</strong>";
    }
}
include_once("includes/footer.php");
?>
