<?php

require_once '../../config/conn.php';

$id =
$_POST['id'] ?? '';

$nama =
$_POST['nama'];

$tarikh =
$_POST['tarikh'];

$jenis =
$_POST['jenis'];

if($id){

    $sql = "
    UPDATE rekod_ooo
    SET
        nama=?,
        tarikh=?,
        jenis=?
    WHERE id=?
    ";

}else{

    $sql = "
    INSERT INTO rekod_ooo
    (
        nama,
        tarikh,
        jenis
    )
    VALUES
    (
        ?,
        ?,
        ?
    )
    ";
}