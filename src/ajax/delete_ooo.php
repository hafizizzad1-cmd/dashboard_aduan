<?php

require_once '../../config/conn.php';

$id = $_POST['id'];

$conn->query(
"
DELETE
FROM rekod_ooo
WHERE id='$id'
"
);