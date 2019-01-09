<html>
<header>
  <title>NoSQL + SQL = MySQL 8.0</title>
</header>
<body>
<center><h1>NoSQL + SQL = MySQL 8.0</h1></center>
<hr>
<center>
<h3>
<a href="index.php">search</a> - <a href="add.php">add</a> - <a href="top.php">top 10</a>
</h3>
</center>
<?php
$session = mysql_xdevapi\getSession("mysqlx://root:fred@localhost?ssl-mode=disabled");
if ($session === NULL) {
    echo ("Connection could not be established to MySQL");
}
?>
