<?php
require_once("../controller/aduan_harian_controller.php");
$page_active = 'aduan_harian';
require_once 'includes/session.php';
include("includes/header.php");
?>


    <div class="top-title-row d-none d-md-block">
        <div class="container-fluid px-4 d-flex justify-content-between align-items-center">
            <div><i class="bi bi-calendar3 me-2"></i> Arkib Laporan Prestasi</div>
            <div><i class="bi bi-people-fill me-1"></i> Data Sokongan Teknikal</div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg modern-topbar sticky-top py-3">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="index2.php" style="color: var(--primary-blue);">
                <div class="text-white rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 35px; height: 35px; background-color: var(--accent-blue);">
                    <i class="bi bi-chevron-left"></i>
                </div>
                <span class="fs-5 text-dark">Dashboard Utama</span>
            </a>
        </div>
    </nav>

    <main class="container-fluid px-4 py-4">
        
        <div class="mb-5">
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-award-fill fs-3 text-warning me-2" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));"></i>
                <div>
                    <h4 class="fw-bold text-dark mb-0">Prestasi Terkumpul Keseluruhan</h4>
                    <p class="text-muted small mb-0">Jumlah aduan diselesaikan oleh setiap staf sepanjang rekod 50 hari.</p>
                </div>
            </div>

            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
                <?php 
                $rank = 1;
                foreach($total_terkumpul as $staf => $total) {
                    if ($total == 0) continue; // Jangan tunjuk jika staf 0 tiket
                    
                    $is_top = ($rank == 1);
                    $card_class = $is_top ? 'summary-card rank-1' : 'summary-card';
                    $icon_rank = $is_top ? '<i class="bi bi-trophy-fill text-warning me-1"></i>' : '';
                    $name_color = $is_top ? 'text-dark' : 'text-muted';
                ?>
                    <div class="col">
                        <div class="<?php echo $card_class; ?> h-100 shadow-sm p-3 text-center">
                            <div class="small fw-bold text-uppercase mb-1 <?php echo $name_color; ?>" style="letter-spacing: 0.5px;">
                                <?php echo $icon_rank . $staf; ?>
                            </div>
                            <h3 class="fw-bold mb-0 text-dark"><?php echo number_format($total); ?></h3>
                        </div>
                    </div>
                <?php 
                    $rank++;
                } 
                ?>
            </div>
        </div>

        <hr class="border-secondary opacity-25 mb-4">

        <div class="mb-4">
            <h2 class="fw-bold text-dark">Log Pencapaian Harian</h2>
            <p class="text-muted">Senarai semak aduan selesai bagi setiap team bertugas. (Target Individu: 15 aduan/hari)</p>
        </div>

        <?php
        // ==============================================================================
        // 4. LOGIK LOOPING KAD HARIAN (DARI ARRAY $data_harian)
        // ==============================================================================
        $current_month = '';
        $is_first_row = true;

        if(!empty($data_harian)) {
            foreach($data_harian as $row) {
                
                $tarikh_db = $row['tarikh'];
                
                $jumlah_selesai = 0;
                $highest_score = 0;

                foreach ($senarai_staf as $staf) {
                    $score = (int)$row[$staf];
                    $jumlah_selesai += $score;
                    if ($score > $highest_score) {
                        $highest_score = $score;
                    }
                }

                if ($jumlah_selesai == 0) continue; 

                $hari = dapatkanHariMelayu($tarikh_db);
                $tarikh_format = formatTarikhPendek($tarikh_db);
                $month_year = dapatkanBulanTahunMelayu($tarikh_db);

                if ($month_year != $current_month) {
                    if (!$is_first_row) echo '</div>'; 
                    
                    $margin_top = $is_first_row ? '' : 'mt-5';
                    echo '<div class="month-divider '.$margin_top.'">';
                    echo '<h4 class="fw-bold text-primary mb-0"><i class="bi bi-calendar-check me-2"></i>' . $month_year . '</h4>';
                    echo '</div>';
                    echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xxl-5 g-4">';
                    
                    $current_month = $month_year;
                    $is_first_row = false;
                }

                // LOGIK HIGHLIGHT KAD (GLOW UP): JIKA TOTAL TIKET HARIAN >= 60
                $class_glow_card = '';
                $html_lencana_achieve = '';
                $warna_total_aduan = ''; 
                
                if ($jumlah_selesai >= 60) {
                    $class_glow_card = 'card-glow-success';
                    $warna_total_aduan = 'text-success fw-bold';
                    $html_lencana_achieve = '<div class="achievement-badge"><i class="bi bi-trophy-fill text-white"></i> TARGET DICAPAI</div>';
                }

                $is_weekend = ($hari == 'Sabtu' || $hari == 'Ahad');
                $class_card_base = $is_weekend ? 'card-gold' : 'bg-white';
                $class_badge = $is_weekend ? 'badge rounded-pill' : 'badge bg-secondary rounded-pill';
                ?>

                <div class="col">
                    <div class="card day-card h-100 shadow-sm border-0 <?php echo $class_card_base . ' ' . $class_glow_card; ?>">
                        
                        <?php echo $html_lencana_achieve; ?>

                        <div class="card-header border-0 bg-transparent pt-3 pb-0 d-flex justify-content-between align-items-center">
                            <span class="<?php echo $class_badge; ?>"><?php echo $hari; ?></span>
                            <div class="text-end">
                                <div class="fw-bold text-dark small"><?php echo $tarikh_format; ?></div>
                                <div class="total-count <?php echo $warna_total_aduan; ?>">
                                    <?php echo $jumlah_selesai; ?> Aduan Selesai
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <?php 
                            $target_harian = 15; 
                            // 1. Senaraikan staf yang perlu duduk di bawah
                            $kumpulan_bawah = ['Syida', 'Hani', 'Fiza', 'Affi', 'Laili'];
                            
                            $html_atas = '';
                            $html_bawah = '';

                            foreach ($senarai_staf as $nama_staf) { 
                                $jumlah_siap = (int)$row[$nama_staf];

                                if ($jumlah_siap == 0) continue;
                                
                                $peratus_visual = min(($jumlah_siap / $target_harian) * 100, 100);
                                $is_top_scorer = ($jumlah_siap == $highest_score && $highest_score > 0);
                                
                                // 2. Logik Warna Baru
                                if ($is_top_scorer) {
                                    $warna_bar = "bg-purple";
                                    $warna_teks = "text-purple";
                                } elseif ($jumlah_siap >= $target_harian) {
                                    $warna_bar = "bg-success";
                                    $warna_teks = "text-success";
                                } elseif ($jumlah_siap >= ($target_harian / 2)) {
                                    $warna_bar = "bg-primary";
                                    $warna_teks = "text-dark";
                                } else {
                                    $warna_bar = "bg-warning";
                                    $warna_teks = "text-dark"; 
                                }

                                $class_nama = $is_top_scorer ? "top-scorer-name" : "text-muted";
                                $class_skor = $is_top_scorer ? "top-scorer-score $warna_teks" : "$warna_teks";
                                $ikon_top = $is_top_scorer ? '<i class="bi bi-star-fill text-warning me-1" style="font-size: 0.7rem;"></i>' : '';
                                
                                // Bina blok HTML untuk staf ini
                                $staf_html = '
                                <div class="staff-mini-stat">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="' . $class_nama . '">
                                            ' . $ikon_top . $nama_staf . '
                                        </span> 
                                        <span class="' . $class_skor . '">
                                            ' . $jumlah_siap . '/' . $target_harian . '
                                        </span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar ' . $warna_bar . '" style="width: ' . $peratus_visual . '%"></div>
                                    </div>
                                </div>';

                                // Asingkan mengikut kumpulan
                                if (in_array($nama_staf, $kumpulan_bawah)) {
                                    $html_bawah .= $staf_html;
                                } else {
                                    $html_atas .= $staf_html;
                                }
                            } 
                            
                            // Paparkan kumpulan atas (staf biasa)
                            echo $html_atas;
                            
                            // Tambah garisan pembahagi jika kedua-dua kumpulan ada rekod pada hari tersebut
                            if (!empty($html_atas) && !empty($html_bawah)) {
                                echo '<hr class="my-3 border-secondary opacity-25">';
                            }
                            
                            // Paparkan kumpulan bawah (Syida, Hani, Fiza, Affi, Laili)
                            echo $html_bawah;
                            ?>
                        </div>

                        <div class="card-footer bg-transparent border-0 pb-3">
                            <button type="button" class="btn btn-sm btn-outline-primary w-100 rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#modalSenaraiAduan">
                                Lihat Senarai Aduan <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>

        <?php
            } // Penutup loop

            if (!$is_first_row) echo '</div>'; 

        } else {
             echo "<div class='text-center mt-5 text-muted'><h5><i class='bi bi-folder-x fs-1 d-block mb-3'></i>Tiada Rekod Dijumpai</h5></div>";
        }
        ?>

    </main>
	<?php
	include("includes/footer.php");
	?>
	
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="../assets/js/script.js"></script>
    <!-- login js-->
    <!-- Plugin used-->
	
	
</html>
