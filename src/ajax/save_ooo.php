<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../../config/conn.php';

$id     = trim($_POST['id'] ?? '');
$nama   = trim($_POST['nama'] ?? '');
$tarikh = trim($_POST['tarikh'] ?? '');
$jenis  = trim($_POST['jenis'] ?? '');


// =====================================
// VALIDATION
// =====================================

if(
    $nama === ''
    ||
    $tarikh === ''
    ||
    $jenis === ''
){
    http_response_code(422);

    echo json_encode([
        'success' => false,
        'message' => 'Sila lengkapkan nama, tarikh dan jenis rekod.'
    ]);

    exit;
}


$jenisDibenarkan = [
    'WFH',
    'Cuti',
    'Cuti Ganti'
];

if(!in_array($jenis, $jenisDibenarkan, true)){

    http_response_code(422);

    echo json_encode([
        'success' => false,
        'message' => 'Jenis rekod tidak sah.'
    ]);

    exit;
}


// =====================================
// UPDATE
// =====================================

if($id !== ''){

    $sql = "
        UPDATE eroses.rekod_ooo
        SET
            nama   = ?,
            tarikh = ?,
            jenis  = ?
        WHERE id = ?
    ";

    $stmt = $conn->prepare($sql);

    if(!$stmt){

        http_response_code(500);

        echo json_encode([
            'success' => false,
            'message' => 'Gagal menyediakan query UPDATE: ' . $conn->error
        ]);

        exit;
    }

    $id = (int) $id;

    $stmt->bind_param(
        'sssi',
        $nama,
        $tarikh,
        $jenis,
        $id
    );


// =====================================
// INSERT
// =====================================

}else{

    $sql = "
        INSERT INTO eroses.rekod_ooo
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

    $stmt = $conn->prepare($sql);

    if(!$stmt){

        http_response_code(500);

        echo json_encode([
            'success' => false,
            'message' => 'Gagal menyediakan query INSERT: ' . $conn->error
        ]);

        exit;
    }

    $stmt->bind_param(
        'sss',
        $nama,
        $tarikh,
        $jenis
    );
}


// =====================================
// EXECUTE
// =====================================

if(!$stmt->execute()){

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan rekod: ' . $stmt->error
    ]);

    exit;
}


echo json_encode([
    'success' => true,
    'message' => ($id !== '')
        ? 'Rekod berjaya dikemas kini.'
        : 'Rekod baharu berjaya disimpan.'
]);

$stmt->close();
$conn->close();