<?php

// =====================================
// STAFF
// =====================================

// Mei masih ada Yaana
$masterListMei = [
    'Hafiz',
    'Yaana',
    'Fareisya',
    'Iffa'
];

// Mulai Jun Yaana dah tiada
$masterListJun = [
    'Hafiz',
    'Fareisya',
    'Iffa'
];


// Minimum orang perlu ada di office
$minimumOfficeStaff = 3;


// =====================================
// OOO RECORD
// =====================================

$rekodOOO = [

    ['nama'=>'Iffa','tarikh'=>'2026-05-28','jenis'=>'Cuti'],
    ['nama'=>'Iffa','tarikh'=>'2026-05-29','jenis'=>'Cuti'],

    ['nama'=>'Hafiz','tarikh'=>'2026-05-13','jenis'=>'WFH'],

    ['nama'=>'Fareisya','tarikh'=>'2026-05-26','jenis'=>'Cuti'],
    ['nama'=>'Fareisya','tarikh'=>'2026-05-28','jenis'=>'Cuti'],

    ['nama'=>'Fareisya','tarikh'=>'2026-05-18','jenis'=>'WFH'],

    ['nama'=>'Fareisya','tarikh'=>'2026-05-25','jenis'=>'Cuti Ganti'],

    ['nama'=>'Iffa','tarikh'=>'2026-05-20','jenis'=>'WFH'],

    ['nama'=>'Hafiz','tarikh'=>'2026-05-21','jenis'=>'WFH'],
];


// =====================================
// BACKUP STAFF
// =====================================

$backupStaff = [

    '2026-05-28' => 'Raja'
];


// =====================================
// CUTI UMUM
// =====================================

$senaraiCuti = [

    '2026-05-01' => 'Hari Pekerja',
    '2026-05-27' => 'Hari Raya Haji',
    '2026-05-31' => 'Hari Wesak',

    '2026-06-01' => 'Keputeraan Agong',
    '2026-06-17' => 'Awal Muharram',
];


// =====================================
// AHAD DUTY AUTO ROTATION
// =====================================

$rotationDuty = [
    'Hafiz',
    'Fareisya',
    'Iffa'
];

$startDutyDate = '2026-05-17';

$ahadDuty = [];

$currentIndex = 0;

$currentDate = new DateTime($startDutyDate);

$endDate = new DateTime('2026-12-31');

while($currentDate <= $endDate){

    $tarikh =
        $currentDate->format('Y-m-d');

    // Kalau cuti umum, skip minggu ni
    if(
        !array_key_exists(
            $tarikh,
            $senaraiCuti
        )
    ){

        $ahadDuty[$tarikh] =
            $rotationDuty[$currentIndex];

        // next person
        $currentIndex++;

        if(
            $currentIndex
            >=
            count($rotationDuty)
        ){

            $currentIndex = 0;
        }
    }

    // next week
    $currentDate->modify('+7 days');
}


// =====================================
// SORT
// =====================================

usort($rekodOOO, function($a, $b){

    return strtotime($a['tarikh']) - strtotime($b['tarikh']);
});


// =====================================
// FUNCTION
// =====================================

function dapatkanStatusHariIni(
    $tarikh,
    $masterList,
    $rekodOOO
){

    $hadir = [];
    $ooo = [];
    $statusOrang = [];

    foreach($rekodOOO as $r){

        if($r['tarikh'] == $tarikh){

            $statusOrang[$r['nama']] = $r['jenis'];
        }
    }

    foreach($masterList as $nama){

        if(array_key_exists($nama, $statusOrang)){

            $ooo[] = [

                'nama' => $nama,
                'jenis' => $statusOrang[$nama]
            ];

        } else {

            $hadir[] = $nama;
        }
    }

    return [

        'hadir' => $hadir,
        'ooo' => $ooo
    ];
}

?>

<!DOCTYPE html>
<html lang="ms">

<head>

<meta charset="UTF-8">

<title>Office Calendar</title>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

<style>

    body{

        background:#f5f7fb;

        font-family:
            Inter,
            "Segoe UI",
            sans-serif;

        color:#1e293b;
    }

    .page-title{

        font-size:1.8rem;
        font-weight:700;

        letter-spacing:-0.5px;
    }

    .sub-title{

        font-size:0.92rem;
        color:#64748b;
    }

    .calendar-container{

        display:grid;
        grid-template-columns:repeat(7,1fr);
        gap:12px;
    }

    .day-label{

        text-align:center;

        font-size:0.82rem;
        font-weight:600;

        color:#94a3b8;

        padding:8px 0;
    }

    .calendar-day{

        background:white;

        border:1px solid #e2e8f0;

        border-radius:16px;

        min-height:185px;

        padding:14px;

        transition:0.2s ease;
    }

    .calendar-day:hover{

        transform:translateY(-2px);

        box-shadow:
            0 10px 25px rgba(0,0,0,0.05);
    }

    .today{

        border:2px solid #2563eb;
    }

    .cuti-umum{

        background:#fef9c3;
        border-color:#fde047;
    }

    .weekend{

        background:#fafafa;
    }

    .critical{

        border-top:6px solid #dc2626;
        background:#fef2f2;
    }

    .day-top{

        display:flex;
        justify-content:space-between;
        align-items:center;

        margin-bottom:12px;
    }

    .day-number{

        font-size:1.35rem;
        font-weight:700;
    }

    .headcount{

        font-size:0.72rem;

        background:#eff6ff;
        color:#2563eb;

        padding:4px 8px;

        border-radius:999px;

        font-weight:600;
    }

    .person{

        font-size:0.84rem;

        padding:7px 10px;

        border-radius:10px;

        margin-bottom:6px;

        line-height:1.35;
    }

    .hadir{

        color:#334155;
    }

    .cuti{

        background:#7e22ce;
        color:white;
    }

    .wfh{

        background:#ea580c;
        color:white;
    }

    .ganti{

        background:#4f46e5;
        color:white;
    }

    .warning{

        margin-top:12px;

        background:#dc2626;
        color:white;

        border-radius:10px;

        padding:8px 10px;

        text-align:center;

        font-size:0.75rem;
        font-weight:700;

        letter-spacing:0.3px;
    }

    .backup-box{

        margin-top:10px;

        background:#0f766e;
        color:white;

        border-radius:10px;

        padding:8px 10px;

        font-size:0.75rem;
        font-weight:700;
    }

    .month-header{

        display:flex;
        justify-content:space-between;
        align-items:center;

        margin-bottom:20px;
    }

    .month-title{

        font-size:1.15rem;
        font-weight:700;
    }

    .month-note{

        font-size:0.78rem;

        background:#e0f2fe;
        color:#0369a1;

        padding:5px 10px;

        border-radius:999px;
    }

    .cuti-title{

        font-size:0.95rem;
        font-weight:700;

        margin-top:25px;
    }

    .cuti-name{

        margin-top:5px;

        font-size:0.85rem;
        color:#854d0e;
    }

    .duty-box{

        margin-top:35px;

        padding:10px;

        border-radius:12px;

        background:#eff6ff;

        text-align:center;
    }

    .duty-label{

        font-size:0.7rem;
        font-weight:700;

        color:#2563eb;

        letter-spacing:0.5px;
    }

    .duty-name{

        font-size:0.95rem;
        font-weight:700;

        margin-top:3px;
    }

    .off-day{

        margin-top:50px;

        text-align:center;

        font-size:0.85rem;
        font-weight:600;

        color:#94a3b8;
    }

    .ooo-log{

        background:white;

        border-radius:16px;

        border:1px solid #e2e8f0;

        padding:18px;
    }

    .ooo-title{

        font-size:0.95rem;
        font-weight:700;

        margin-bottom:15px;
    }

    .ooo-item{

        border-radius:12px;

        padding:10px 12px;

        margin-bottom:10px;

        font-size:0.82rem;
    }

    .ooo-date{

        opacity:0.8;
        font-size:0.74rem;

        margin-top:2px;
    }

</style>

</head>

<body>

<div class="container-fluid py-4 px-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="page-title">

                Office Calendar

            </div>

            <div class="sub-title">

                Monitor kehadiran staf harian

            </div>

        </div>

    </div>


    <div class="row g-4">

        <!-- LEFT -->
        <div class="col-md-2">

            <div class="ooo-log">

                <div class="ooo-title">

                    Rekod OOO

                </div>

                <?php foreach($rekodOOO as $r):

                    $class = 'cuti';

                    if(
                        stripos($r['jenis'],'WFH')
                        !== false
                    ){

                        $class = 'wfh';

                    } elseif(
                        stripos($r['jenis'],'Ganti')
                        !== false
                    ){

                        $class = 'ganti';
                    }

                ?>

                <div class="ooo-item <?= $class ?>">

                    <div>

                        <?= $r['nama'] ?>

                    </div>

                    <div class="ooo-date">

                        <?= date(
                            'd/m/Y',
                            strtotime($r['tarikh'])
                        ) ?>

                        ·

                        <?= $r['jenis'] ?>

                    </div>

                </div>

                <?php endforeach; ?>

            </div>

        </div>


        <!-- RIGHT -->
        <div class="col-md-10">

            <?php

            foreach(
                [
                    5 => 'Mei',
                    6 => 'Jun',
                    7 => 'Julai'
                ]
                as $bulan_n => $nama_b
            ):

                $objek =
                    new DateTime("2026-$bulan_n-01");

                if($bulan_n == 5){

                    $currentMasterList =
                        $masterListMei;

                } else {

                    $currentMasterList =
                        $masterListJun;
                }

            ?>

            <div class="mb-5">

                <!-- MONTH -->
                <div class="month-header">

                    <div class="month-title">

                        <?= $nama_b ?> 2026

                    </div>

                    <?php if($bulan_n >= 6): ?>

                    <div class="month-note">

                        3 staf aktif

                    </div>

                    <?php endif; ?>

                </div>


                <!-- DAYS -->
                <div class="calendar-container mb-2">

                    <?php

                    foreach(
                        [
                            'Isnin',
                            'Selasa',
                            'Rabu',
                            'Khamis',
                            'Jumaat',
                            'Sabtu',
                            'Ahad'
                        ]
                        as $hari
                    ):

                    ?>

                    <div class="day-label">

                        <?= $hari ?>

                    </div>

                    <?php endforeach; ?>

                </div>


                <!-- GRID -->
                <div class="calendar-container">

                    <?php

                    for(
                        $k=1;
                        $k<$objek->format('N');
                        $k++
                    ){

                        echo '
                        <div class="calendar-day weekend"></div>
                        ';
                    }

                    for(
                        $h=1;
                        $h<=$objek->format('t');
                        $h++
                    ){

                        $tarikh =
                            "2026-"
                            .str_pad(
                                $bulan_n,
                                2,
                                '0',
                                STR_PAD_LEFT
                            )
                            ."-"
                            .str_pad(
                                $h,
                                2,
                                '0',
                                STR_PAD_LEFT
                            );

                        $status =
                            dapatkanStatusHariIni(
                                $tarikh,
                                $currentMasterList,
                                $rekodOOO
                            );

                        $dn =
                            (
                                new DateTime($tarikh)
                            )->format('N');

                        $jumlahHadir =
                            count($status['hadir']);

                        // WFH masih working
                        $jumlahOffice =
                            $jumlahHadir;

                        foreach(
                            $status['ooo']
                            as $o
                        ){

                            if(
                                stripos(
                                    $o['jenis'],
                                    'WFH'
                                ) !== false
                            ){

                                $jumlahOffice++;
                            }
                        }

                        // Backup dikira masuk office
                        if(
                            array_key_exists(
                                $tarikh,
                                $backupStaff
                            )
                        ){

                            $jumlahOffice++;
                        }

                        $today = date('Y-m-d');

                        $kelas = '';

                        $is_cuti_umum =
                            array_key_exists(
                                $tarikh,
                                $senaraiCuti
                            );

                        if($dn == 6){

                            // Sabtu
                            $kelas .= ' weekend';

                        } elseif($dn == 7){

                            // Ahad
                            $kelas .= ' weekend';
                        }

                        if($is_cuti_umum){

                            $kelas .= ' cuti-umum';
                        }

                        if(
                            !$is_cuti_umum
                            &&
                            $dn < 6
                            &&
                            $jumlahOffice
                            < $minimumOfficeStaff
                        ){

                            $kelas .= ' critical';
                        }

                        if($tarikh == $today){

                            $kelas .= ' today';
                        }

                    ?>

                    <div class="calendar-day <?= $kelas ?>">

                        <!-- TOP -->
                        <div class="day-top">

                            <div class="day-number">

                                <?= $h ?>

                            </div>

                            <?php
                            if(
                                !$is_cuti_umum
                                &&
                                $dn < 6
                            ):
                            ?>

                            <div class="headcount">

                                <?= $jumlahOffice ?>
                                /
                                <?= count($currentMasterList) ?>

                            </div>

                            <?php endif; ?>

                        </div>


                        <!-- CUTI -->
                        <?php if($is_cuti_umum): ?>

                        <div class="cuti-title">

                            CUTI UMUM

                        </div>

                        <div class="cuti-name">

                            <?= $senaraiCuti[$tarikh] ?>

                        </div>


                        <!-- SABTU -->
                        <?php elseif($dn == 6): ?>

                        <div class="off-day">

                            Tiada Operasi

                        </div>


                        <!-- AHAD -->
                        <?php elseif($dn == 7): ?>

                        <div class="duty-box">

                            <div class="duty-label">

                                BERTUGAS

                            </div>

                            <div class="duty-name">

                                <?= $ahadDuty[$tarikh] ?? '-' ?>

                            </div>

                        </div>


                        <!-- WEEKDAY -->
                        <?php else: ?>

                            <?php
                            foreach(
                                $status['hadir']
                                as $nama
                            ):
                            ?>

                            <div class="person hadir">

                                <?= $nama ?>

                            </div>

                            <?php endforeach; ?>


                            <?php

                            foreach(
                                $status['ooo']
                                as $o
                            ):

                                $class = 'cuti';

                                if(
                                    stripos(
                                        $o['jenis'],
                                        'WFH'
                                    ) !== false
                                ){

                                    $class = 'wfh';

                                } elseif(
                                    stripos(
                                        $o['jenis'],
                                        'Ganti'
                                    ) !== false
                                ){

                                    $class = 'ganti';
                                }

                            ?>

                            <div class="person <?= $class ?>">

                                <?= $o['nama'] ?>

                                <span
                                    style="
                                        opacity:0.85;
                                        font-size:0.72rem;
                                        margin-left:4px;
                                    "
                                >
                                    (<?= $o['jenis'] ?>)
                                </span>

                            </div>

                            <?php endforeach; ?>


                            <?php
                            if(
                                $jumlahOffice
                                <
                                $minimumOfficeStaff
                            ):
                            ?>

                            <div class="warning">

                                ⚠️ KURANG TENAGA
                                <br>

                                <?= $jumlahOffice ?>
                                /
                                <?= $minimumOfficeStaff ?>

                                staf tersedia

                            </div>

                            <?php endif; ?>


                            <?php
                            if(
                                array_key_exists(
                                    $tarikh,
                                    $backupStaff
                                )
                            ):
                            ?>

                            <div class="backup-box">

                                BACKUP:
                                <?= $backupStaff[$tarikh] ?>

                            </div>

                            <?php endif; ?>

                        <?php endif; ?>

                    </div>

                    <?php } ?>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

    </div>

</div>

</body>
</html>