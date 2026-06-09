<?php
require_once '../config/conn.php';
require_once 'includes/session.php';
$rekodOOO = [];
$query = "
SELECT
    id,
    nama,
    tarikh,
    jenis
FROM eroses.rekod_ooo
";
$result = $conn->query($query);

while($row = $result->fetch_assoc()) {
    $rekodOOO[] = $row;
}
?>

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
    'Iffa',
    'Raja'
];


// Minimum orang perlu ada di office
$minimumOfficeStaff = 3;


// =====================================
// OOO RECORD
// =====================================



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
    '2026-06-02' => 'Cuti Ganti Hari Wesak',
    '2026-06-17' => 'Awal Muharram',

    '2026-08-25' => 'Maulidur Rasul',
    '2026-08-31' => 'Hari Kebangsaan',

    '2026-09-16' => 'Hari Malaysia',

    '2026-11-08' => 'Hari Deepavali',

    '2026-12-25' => 'Hari Krismas',
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

            $statusOrang[$r['nama']] = [

                'id'    => $r['id'],
                'jenis' => $r['jenis']
            ];
        }
    }

    foreach($masterList as $nama){

        if(array_key_exists($nama, $statusOrang)){

            $ooo[] = [

                'id'    => $statusOrang[$nama]['id'],
                'nama'  => $nama,
                'jenis' => $statusOrang[$nama]['jenis']
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
include("includes/header.php");
?>

<div class="page-body">
<div class="container-fluid py-4 px-4">

  
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="duty-hero">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="duty-label">
                            <i class="bi bi-calendar-check me-1"></i>
                            BERTUGAS AHAD INI
                        </div>

                        <div class="duty-date">
                            <?= date('d F Y', strtotime('next sunday')) ?>
                        </div>

                        <div class="duty-name">
                            <?= $ahadDuty[date('Y-m-d', strtotime('next sunday'))] ?? 'Tiada' ?>
                        </div>

                    </div>

                    <div class="duty-icon">
                        👨‍💻
                    </div>

                </div>

            </div>
        </div>
        
        <div class="col-md-4 d-flex align-items-start justify-content-md-end gap-2 month-nav">
           <button
                class="btn btn-primary btn-lg rounded-pill px-4 py-2 shadow-sm"
                id="btnToday">

                <i class="bi bi-crosshair me-2"></i>
                Hari Ini

            </button>
            <a href="#mei" class="nav-link-custom">Mei</a>
            <a href="#jun" class="nav-link-custom">Jun</a>
            <a href="#julai" class="nav-link-custom">Julai</a>
        </div>
    </div>
    <?php

        $totalWFH = 0;
        $totalCuti = 0;

        foreach($rekodOOO as $r){

            if(stripos($r['jenis'],'WFH') !== false){
                $totalWFH++;
            }else{
                $totalCuti++;
            }
        }

        $totalStaff = count($masterListJun);

    ?>
    
    <div class="row g-4">

        <!-- LEFT -->
        <div class="col-md-3">
           <div class="sidebar-card">

            <div class="sidebar-title">
                Pengurusan OOO
            </div>

            <button
                 id="btnNewOOO"
                class="btn btn-primary btn-ooo w-100"
                data-bs-toggle="modal"
                data-bs-target="#modalOOO">

                <i class="bi bi-plus-circle me-2"></i>
                Rekod Baharu

            </button>

        </div>


        <div class="sidebar-card">

            <div class="sidebar-title">
                Ringkasan
            </div>

            <div class="stat-row">

                <span>🏠 WFH</span>

                <span class="stat-value text-warning">
                    <?= $totalWFH ?>
                </span>

            </div>

            <div class="stat-row">

                <span>🏖️ Cuti</span>

                <span class="stat-value text-purple">
                    <?= $totalCuti ?>
                </span>

            </div>

            <div class="stat-row">

                <span>👥 Staf</span>

                <span class="stat-value text-primary">
                    <?= count($masterListJun) ?>
                </span>

            </div>

        </div>


        <div class="sidebar-card">

            <div class="sidebar-title">
                Aktiviti Terkini
            </div>

            <?php foreach(array_reverse($rekodOOO) as $r): ?>

                <?php

                $icon =
                    stripos($r['jenis'],'WFH') !== false
                    ? '🏠'
                    : '🏖️';

                ?>

                <div class="activity-item">

                    <div class="activity-icon">

                        <?= $icon ?>

                    </div>

                    <div>

                        <div
                            style="
                            font-weight:600;
                            font-size:.85rem;">

                            <?= $r['nama'] ?>

                        </div>

                        <div
                            style="
                            font-size:.75rem;
                            color:#64748b;">

                            <?= date('d M', strtotime($r['tarikh'])) ?>

                            •

                            <?= $r['jenis'] ?>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>
            

        </div>


        <!-- RIGHT -->
        <div class="col-md-9">

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body py-2">

                    <div class="d-flex flex-wrap gap-2">

                        <span class="badge bg-secondary">
                            Hadir
                        </span>

                        <span class="badge text-dark"
                            style="background:#ffedd5;">
                            WFH
                        </span>

                        <span class="badge text-dark"
                            style="background:#ede9fe;">
                            Cuti
                        </span>

                        <span class="badge text-dark"
                            style="background:#e0e7ff;">
                            Ganti
                        </span>

                        <span class="badge bg-success">
                            Backup
                        </span>

                    </div>

                </div>
            </div>        
            <div class="col-md-12">

            <?php
            foreach(
                [
                    5 => 'Mei',
                    6 => 'Jun',
                    7 => 'Julai'
                ]
                as $bulan_n => $nama_b
            ):
                $objek = new DateTime("2026-$bulan_n-01");
                $currentMasterList = ($bulan_n == 5) ? $masterListMei : $masterListJun;
            ?>
            
            <div class="mb-5" id="<?= strtolower($nama_b) ?>">
                <div class="month-header">
                    <div class="month-title"><?= $nama_b ?> 2026</div>
                    <?php if($bulan_n >= 6): ?>
                        <div class="month-note">3 staf aktif</div>
                    <?php endif; ?>
                </div>

                <div class="calendar-container mb-2">
                    <?php foreach(['Isnin','Selasa','Rabu','Khamis','Jumaat','Sabtu','Ahad'] as $hari): ?>
                        <div class="day-label"><?= $hari ?></div>
                    <?php endforeach; ?>
                </div>

                <div class="calendar-container">
                    <?php
                    $minimumOfficeStaff = 3;
                    $availableThreshold = 4;

                    for($k=1; $k<$objek->format('N'); $k++){
                        echo '<div class="calendar-day weekend"></div>';
                    }

                    for($h=1; $h<=$objek->format('t'); $h++){
                        $tarikh = "2026-".str_pad($bulan_n, 2, '0', STR_PAD_LEFT)."-".str_pad($h, 2, '0', STR_PAD_LEFT);
                        $status = dapatkanStatusHariIni($tarikh, $currentMasterList, $rekodOOO);
                        $dn = (new DateTime($tarikh))->format('N');
                        
                        // ======================================================
                        // KIRA JUMLAH STAF DI PEJABAT
                        // ======================================================

                        // Hanya staf HADIR dikira berada di pejabat
                        $jumlahOffice = count($status['hadir']);

                        // Backup staff dikira berada di pejabat
                        if(array_key_exists($tarikh, $backupStaff)){
                            $jumlahOffice++;
                        }

                        $today = date('Y-m-d');
                        $is_cuti_umum = array_key_exists($tarikh, $senaraiCuti);

                        // ======================================================
                        // STATUS HARI
                        // ======================================================

                        $kelas = '';

                        if($dn == 6 || $dn == 7){
                            $kelas .= ' weekend';
                        }

                        if($is_cuti_umum){
                            $kelas .= ' cuti-umum';
                        }

                        // Merah
                        if(
                            !$is_cuti_umum
                            &&
                            $dn < 6
                            &&
                            $jumlahOffice < $minimumOfficeStaff
                        ){
                            $kelas .= ' critical';
                        }

                        // Hijau
                        if(
                            !$is_cuti_umum
                            &&
                            $dn < 6
                            &&
                            $jumlahOffice >= $availableThreshold
                        ){
                            $kelas .= ' overstaffed';
                        }

                        // Kuning
                        if(
                            !$is_cuti_umum
                            &&
                            $dn < 6
                            &&
                            $jumlahOffice >= $minimumOfficeStaff
                            &&
                            $jumlahOffice < $availableThreshold
                        ){
                            $kelas .= '  normal';
                        }

                        if($tarikh == $today){
                            $kelas .= ' today';
                        }?>

                   <div
                    id="<?= ($tarikh == $today) ? 'today-card' : '' ?>"
                    class="calendar-day <?= $kelas ?>"
                    data-tarikh="<?= $tarikh ?>"
                    style="cursor:pointer;">
                        <div class="quick-add">
                            <i class="bi bi-plus-lg"></i>
                        </div>
                        <div class="day-top">
                            <div class="day-number"><?= $h ?></div>
                            
                         <?php if(!$is_cuti_umum && $dn < 6): ?>

                                    <?php if($jumlahOffice >= $availableThreshold): ?>

                                        <span
                                            class="badge rounded-pill bg-success"
                                            style="font-size:0.6rem;">
                                            Available
                                        </span>

                                    <?php elseif($jumlahOffice >= $minimumOfficeStaff): ?>

                                        <span
                                            class="badge rounded-pill bg-info text-dark"
                                            style="font-size:0.6rem;">
                                            Cukup
                                        </span>

                                    <?php else: ?>
                                   <?php

                                        $peratus =
                                        (
                                            $jumlahOffice /
                                            count($currentMasterList)
                                        )
                                        *
                                        100;

                                    ?>
                                    <div class="mt-1">

                                        <small class="fw-bold">
                                            <?= $jumlahOffice ?>/<?= count($currentMasterList) ?>
                                        </small>

                                        <div class="availability-bar">

                                            <div
                                                class="availability-fill"
                                                style="width:<?= $peratus ?>%">
                                            </div>

                                        </div>

                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <?php if($is_cuti_umum): ?>
                            <div class="cuti-title"><i class="bi bi-balloon-fill"></i> CUTI UMUM</div>
                            <div class="cuti-name"><?= $senaraiCuti[$tarikh] ?></div>
                        <?php elseif($dn == 6): ?>
                            <div class="off-day">YEAY SABTU!!</div>
                        <?php elseif($dn == 7): ?>
                            <div class="duty-box">
                                <div class="duty-label">BERTUGAS</div>
                                <div class="duty-name"><?= $ahadDuty[$tarikh] ?? '-' ?></div>
                            </div>
                        <?php else: ?>
                            <?php foreach($status['hadir'] as $nama): ?>
                                <div class="person hadir">
                                    <span>
                                       
                                        <?= $nama ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                            <?php foreach($status['ooo'] as $o): 
                                $class = (stripos($o['jenis'],'WFH') !== false) ? 'wfh' : ((stripos($o['jenis'],'Ganti') !== false) ? 'ganti' : 'cuti');
                            ?>
                                <div class="person <?= $class ?> person-click" data-id="<?= $o['id'] ?>" data-nama="<?= $o['nama'] ?>" data-jenis="<?= $o['jenis'] ?>" data-tarikh="<?= $tarikh ?>">
                                    <?= $o['nama'] ?> <span style="opacity:0.85; font-size:0.72rem; margin-left:4px;">(<?= $o['jenis'] ?>)</span>
                                </div>
                            <?php endforeach; ?>
                            <?php if($jumlahOffice < $minimumOfficeStaff): ?>
                                <div class="warning">⚠️ KURANG TENAGA<br><?= $jumlahOffice ?>/<?= $minimumOfficeStaff ?> staf</div>
                            <?php endif; ?>
                            <?php if(array_key_exists($tarikh, $backupStaff)): ?>
                                <div class="backup-box">BACKUP: <?= $backupStaff[$tarikh] ?></div>
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

</div>
</div>
<div
    class="modal fade"
    id="modalOOO"
    tabindex="-1">
                            
    <div class="modal-dialog">

        <div class="modal-content border-0 shadow">

            <div class="modal-header">

                <h5 id="modal_title">
                    Rekod OOO
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="mb-3">

                    <label class="form-label">
                        Tarikh
                    </label>

                    <input
                        type="date"
                        id="modal_tarikh"
                        class="form-control">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Nama
                    </label>

                    <select
                        class="form-select"
                        id="modal_nama">

                        <?php foreach($masterListJun as $staff): ?>

                            <option>
                                <?= $staff ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Jenis
                    </label>

                    <select
                        class="form-select"
                        id="modal_jenis">

                        <option value="WFH">
                            WFH
                        </option>

                        <option value="Cuti">
                            Cuti
                        </option>

                        <option value="Cuti Ganti">
                            Cuti Ganti
                        </option>

                    </select>

                </div>

            </div>

            <div class="modal-footer">
                <input type="hidden" id="modal_id">                   
               <button
                    id="btnDelete"
                    class="btn btn-danger me-auto d-none">

                    Padam

                </button>

                <button
                    id="btnSave"
                    class="btn btn-primary">

                    Simpan

                </button>
            </div>

        </div>

    </div>

</div>

<?php
include("includes/footer.php");
?>