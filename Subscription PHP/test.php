<?php
$db_connecztion = pg_connect("host=localhost port=5432 dbname=simplelogin user=postgres password=postgres");

// print_r($db_connecztion);
if ($db_connecztion) {
    print('connection established <br>');
}

$query = 'SELECT * FROM accounts';
$resultpg = pg_query($db_connecztion, $query);
if ($resultpg) {
    while ($row = pg_fetch_row($resultpg)) {
        echo "ID = " . $row[0] . "\n";
        echo "NAME = " . $row[1] . "\n";
    }
} else {
    echo 'not done';
}
print_r($resultpg);
