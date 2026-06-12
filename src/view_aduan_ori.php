<?php
// ==========================================================
// 1. TANGKAP GET REQUEST DARI URL
// ==========================================================
// Contoh URL yang betul: view_aduan.php?id=57961
$target_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 2. JIKA TIADA ID ATAU ID KOSONG, TENDANG KELUAR
if ($target_id === 0) {
    // Bos boleh tukar 'senarai_aduan.php' ke page jadual utama bos
    echo "<script>
            alert('Makluman: Sila pilih aduan dari senarai terlebih dahulu.'); 
            window.location.href = 'senarai_aduan.php.php'; 
          </script>";
    exit(); // Wajib exit supaya kod HTML kat bawah tak di-render
}

// ==========================================================
// 3. PANGGIL CONTROLLER UNTUK TARIK DATA DARI DATABASE
// ==========================================================
// Controller akan guna $_GET['id'] ni untuk cari data
require_once '../controller/view_aduan_controller.php';

// ==========================================================
// 4. SETTING TOPBAR & HEADER
// ==========================================================
$page_active = 'pengurusan'; 
include 'includes/header.php';



// Kamus Terjemahan Status (Sama seperti Enum frontend)
$statusEnum = [
    "-1" => "PADAM",
    "0" => "-",
    "1" => "BELUM DIHANTAR",
    "2" => "MENUNGGU KEPUTUSAN",
    "3" => "LULUS",
    "4" => "TOLAK",
    "5" => "MENUNGGU BAYARAN KAUNTER",
    "6" => "MENUNGGU BAYARAN ONLINE",
    "7" => "MAKLUMAT TIDAK LENGKAP",
    "8" => "LUPUT",
    "9" => "DIBENARKAN",
    "10" => "TIDAK DIBENARKAN",
    "11" => "AKTIF",
    "12" => "MENUNGGU PENGAKTIFAN CAWANGAN",
    "13" => "TIADA MAKLUMAT MIGRASI",
    "14" => "TAMAT TEMPOH CARIAN",
    "15" => "SETIAUSAHA BUKAN WARGA",
    "16" => "BUBAR",
    "17" => "SELESAI",
    "18" => "DISYORKAN",
    "19" => "TIDAK DISYORKAN",
    "20" => "BATAL",
    "21" => "MENUNGGU SYOR",
    "22" => "TEST DATA",
    "23" => "MENUNGGU MAKLUMBALAS",
    "25" => "PERTELINGKAHAN",
    "26" => "TARIK BALIK",
    "27" => "BATAL KHAS",
    "28" => "MENUNGGU PENGESAHAN JAWATANKUASA INDUK",
    "29" => "LULUS BERSYARAT",
    "30" => "DALAM TINDAKAN KIV",
    "36" => "KUIRI",
    "37" => "PINDAH",
    "38" => "MENUNGGU PENGESAHAN BAYARAN",
    "39" => "MENUNGGU KEPUTUSAN MENTERI",
    "40" => "MENUNGGU ULASAN",
    "41" => "MENUNGGU ULASAN AGENSI LUAR",
    "42" => "NOTIS MESYUARAT DIHANTAR",
    "43" => "INAKTIF",
    "44" => "BAYARAN GAGAL",
    "45" => "MENUNGGU BAYARAN",
    "-001" => "PADAM",
    "001" => "AKTIF",
    "002" => "TOLAK",
    "003" => "BATAL",
    "004" => "TARIK BALIK",
    "005" => "BUBAR",
    "006" => "BUBAR SEMENTARA",
    "007" => "BARU",
    "008" => "TIDAK AKTIF",
    "009" => "DIBENARKAN",
    "010" => "DILUPUTKAN",
    "011" => "PINDA NAMA",
    "101" => "PERTELINGKAHAN SIASATAN",
    "102" => "BATAL KHAS",
    "103" => "TIDAK AKTIF PINDAH",
    "104" => "DALAM PERHATIAN",
    "306" => "TIDAK AKTIF",
    "678" => "TEST",
    "998" => "DIGUGURKAN",
    "999" => "TIADA MAKLUMAT"
];

// Logik untuk menterjemah kod kepada teks (Fallback kepada kod asal jika tiada dalam kamus)
$kod_status = (string)($pertubuhan['status_code'] ?? '');
$teks_status = $statusEnum[$kod_status] ?? $kod_status;

$kod_substatus = (string)($pertubuhan['sub_status_code'] ?? '');
$teks_substatus = $statusEnum[$kod_substatus] ?? $kod_substatus;

$kod_application = (string)($pertubuhan['application_status_code'] ?? '');
$teks_application = $statusEnum[$kod_application] ?? $kod_application;


?>
    

    <div class="container-fluid px-4 py-3 main-workspace">
        <div class="row g-4 h-100">
            
			<div class="col-xl-4 col-lg-4 left-scroll flex-shrink-0">
                
				<div class="d-flex align-items-center justify-content-between mb-3 pt-3">
					<a class="d-flex align-items-center gap-2 text-decoration-none" href="senarai_aduan.php">
						<div class="text-white rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px; background-color: var(--accent-blue); transition: all 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
							<i class="bi bi-chevron-left" style="font-size: 0.9rem;"></i>
						</div>
						<span class="fw-bold text-dark" style="font-size: 0.95rem;">Kembali</span>
					</a>
					<div class="d-flex align-items-center bg-warning bg-opacity-10 border border-warning-subtle rounded-pill px-3 py-1 shadow-sm">
						<div class="spinner-grow text-warning me-2" role="status" style="width: 8px; height: 8px; animation-duration: 1.5s;"></div>
						<span class="text-warning-emphasis fw-bold" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
							<i class="bi bi-person-fill me-1"></i>Tindakan: <?php echo !empty($complaint['PIC_name']) ? htmlspecialchars($complaint['PIC_name']) : 'Tiada'; ?>
						</span>
					</div>
				</div>

				<div class="card">
					<div class="card-header d-flex justify-content-between align-items-center">
						<?php 
							$status_tech_display = 'Telah Dialir';
							$status_badge_class = 'badge-soft-warning';
							
							if (isset($complaint['status_technical']) && $complaint['status_technical'] == 'ANSWERED') { 
								$status_tech_display = 'Telah Dijawab'; 
								$status_badge_class = 'badge-soft-success';
							}
						?>
						<h6 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Maklumat Aduan</h6>
						<span class="badge rounded-pill <?php echo $status_badge_class; ?>"><?php echo $status_tech_display; ?></span>
					</div>
					<div class="card-body">
						<div class="mb-3 border-bottom pb-2">
							<label class="form-label">No. Aduan (ID)</label>
							<div class="form-control-plaintext text-primary fs-5"><?php echo htmlspecialchars($complaint['id'] ?? ''); ?></div>
						</div>
						<div class="mb-3 border-bottom pb-2">
							<label class="form-label">Tarikh Aduan</label>
							<div class="form-control-plaintext text-dark">
								<?php echo !empty($complaint['created_date']) ? date('d/m/Y, h:i A', strtotime($complaint['created_date'])) : '-'; ?>
							</div>
						</div>
						<div class="mb-3 border-bottom pb-2">
							<label class="form-label">Tajuk Aduan</label>
							<div class="form-control-plaintext fw-bold text-dark" style="font-size: 0.95rem; line-height: 1.4;">
								<?php echo htmlspecialchars($complaint['title'] ?? ''); ?>
							</div>
						</div>
						<div class="mb-3 border-bottom pb-2">
							<label class="form-label">Perihal Aduan</label>
							<div class="form-control-plaintext fw-normal text-muted" style="font-size: 0.85rem; white-space: pre-line; line-height: 1.5;">
								<?php echo htmlspecialchars($complaint['details'] ?? ''); ?>
							</div>
						</div>
						<div class="mb-3 border-bottom pb-2">
							<label class="form-label">No Pertubuhan</label>
							<div class="form-control-plaintext fw-bold text-dark" style="font-size: 0.95rem; line-height: 1.4;">
								PPM-012-13-01121998
							</div>
						</div>
						<div class="mb-0">
							<label class="form-label">Keutamaan (Severity)</label>
							<?php 
								$sev = $complaint['severity'] ?? '';
								$severity_class = 'bg-secondary';
								if ($sev == 'HIGH' || $sev == 'SEVERE') { $severity_class = 'bg-danger'; }
								else if ($sev == 'MEDIUM') { $severity_class = 'bg-warning text-dark'; }
							?>
							<div class="form-control-plaintext">
								<span class="badge <?php echo $severity_class; ?> px-3 py-1"><?php echo htmlspecialchars($sev); ?></span>
							</div>
						</div>
					</div>
				</div>

				<div class="card">
					<div class="card-header"><h6 class="mb-0"><i class="bi bi-gear-wide-connected me-2"></i>Pengurusan Teknikal</h6></div>
					<div class="card-body">
						<div class="mb-3 border-bottom pb-2">
							<label class="form-label">Tarikh Alir</label>
							<div class="form-control-plaintext">
								<?php echo !empty($complaint['assign_date']) ? date('d/m/Y, h:i A', strtotime($complaint['assign_date'])) : '-'; ?>
							</div>
						</div>
						<div class="mb-3 border-bottom pb-2">
							<label class="form-label">Pegawai Bertanggungjawab (PIC)</label>
							<div class="form-control-plaintext text-dark fw-bold">
								<?php echo htmlspecialchars($complaint['PIC'] ?? '') . ' - ' . htmlspecialchars($complaint['PIC_name'] ?? ''); ?>
							</div>
						</div>
						<div class="mb-0">
							<label class="form-label">Catatan Pegawai</label>
							<div class="form-control-plaintext fw-normal text-muted" style="font-size: 0.85rem; white-space: pre-line;">
								<?php echo !empty($complaint['note']) ? htmlspecialchars($complaint['note']) : '<span class="text-muted opacity-50"><i>Tiada catatan.</i></span>'; ?>
							</div>
						</div>
					</div>
				</div>

				<div class="card">
					<div class="card-header"><h6 class="mb-0"><i class="bi bi-chat-left-text me-2"></i>Jawapan Teknikal</h6></div>
					<div class="card-body">
						<div class="mb-3 border-bottom pb-2">
							<label class="form-label">Tarikh Selesai</label>
							<div class="form-control-plaintext text-success fw-bold">
								<?php 
									echo !empty($complaint['finish_date']) && $complaint['finish_date'] != '0000-00-00 00:00:00'
										? date('d/m/Y, h:i A', strtotime($complaint['finish_date'])) 
										: '<span class="text-warning fw-normal"><i>Belum Selesai</i></span>'; 
								?>
							</div>
						</div>
						<div class="mb-0">
							<label class="form-label">Jawapan / Maklumbalas Teknikal</label>
							<div class="bg-light p-3 rounded-3 mt-1 text-dark" style="font-size: 0.88rem; white-space: pre-line; border-left: 4px solid var(--accent-blue);">
								<?php echo !empty($complaint['tech_feedback']) ? htmlspecialchars($complaint['tech_feedback']) : '<span class="text-muted"><i>Menunggu jawapan teknikal...</i></span>'; ?>
							</div>
						</div>
					</div>
				</div>

			</div>
			<?php
					// =========================================================================
					// DATA DUMMY PERLEMBAGAAN PENUH (FASAL 1 - 19)
					// =========================================================================
					$senarai_perlembagaan = [
						[
							'no' => '1', 
							'nama' => 'NAMA', 
							'desc' => "(1) Pertubuhan ini dikenali dengan nama\n\nDEWAN PERNIAGAAN DAN PERINDUSTRIAN PUNJABI MALAYSIA (MALAYSIAN PUNJABI CHAMBERS OF COMMERCE AND INDUSTRIES)\n\nselepas ini disebut Pertubuhan.\n\n(2) Takrif Nama :\n\n(3) Bertaraf : KEBANGSAAN"
						],
						[
							'no' => '2', 
							'nama' => 'ALAMAT', 
							'desc' => "(1) Alamat berdaftar dan tempat urusan pertubuhan ialah\n\nSUITE C-09-02, PLAZA MONT' KIARA,\nNO.2, JALAN KIARA, MONT' KIARA,\n50480 KUALA LUMPUR\nWILAYAH PERSEKUTUAN KUALA LUMPUR\n\natau di tempat lain atau tempat-tempat yang akan ditetapkan dari semasa ke semasa oleh Jawatankuasa Pusat; dan alamat untuk surat-menyurat adalah\n\nSUITE C-09-02, PLAZA MONT' KIARA,\nNO.2, JALAN KIARA, MONT' KIARA,\n50480 KUALA LUMPUR\nWILAYAH PERSEKUTUAN KUALA LUMPUR\n\n(2) Tempat urusan berdaftar dan alamat surat-menyurat Pertubuhan tidak boleh diubah tanpa kebenaran Pendaftar Pertubuhan terlebih dahulu."
						],
						[
							'no' => '3', 
							'nama' => 'MATLAMAT', 
							'desc' => "TUJUAN PERTUBUHAN ADALAH SEPERTI BERIKUT:\n\n(1) ADALAH UNTUK MEMPROMOSI, MEMBERI PERLINDUNGAN, DAN MEMAJUKAN SEMUA PERKARA BERHUBUNG DENGAN PERNIAGAAN, PERINDUSTRIAN DAN KEBUDAYAAN KAUM PUNJABI DI MALAYSIA. SELARAS DENGAN OBJEKTIF TERSEBUT, PERTUBUHAN INI ANTARA LAIN BOLEH:\n\nA. BERKERJASAMA DENGAN KERAJAAN MALAYSIA, KERAJAAN NEGERI, AGENSI-AGENSI KERAJAAN, BADAN-BADAN BERKANUN...\n\nB. MEMPROMOSIKAN DAN MEMUDAHKAN KERJASAMA DUA HALA DIANTARA PERNIAGAAN DAN INDUSTRI DI MALAYSIA DENGAN PERNIAGAAN DAN INDUSTRI MASYARAKAT PUNJABI DI SELURUH DUNIA.\n\nC. MENGUMPUL, MENGEMASKINI DAN MENYIARKAN STATISTIK, SERTA LAIN-LAIN INFORMASI."
						],
						[
							'no' => '4', 
							'nama' => 'KEAHLIAN', 
							'desc' => "(1) Keahlian Pertubuhan adalah seperti berikut :\n\nA. Ahli Biasa\n   i. Kewarganegaraan : Malaysia\n   ii. Umur Minima : 21 Tahun\n   iii. Keturunan/Bangsa : PUNJABI\n\nB. Ahli Bersekutu\n   Tiada\n\n(2) Tiap-tiap permohonan menjadi ahli hendaklah dicadangkan dan disokong oleh ahli dan dihantar kepada Setiausaha.\n\n(3) Tiap-tiap pemohon yang permohonannya telah diluluskan hendaklah membayar bayaran masuk dan yuran pertama."
						],
						[
							'no' => '5', 
							'nama' => 'PEMBERHENTIAN DAN PEMECATAN AHLI', 
							'desc' => "(1) Ahli yang hendak berhenti daripada menjadi ahli Pertubuhan hendaklah memberi kenyataan bertulis 2 minggu terlebih dahulu kepada Setiausaha dan menjelaskan segala hutangnya.\n\n(2) Mana-mana ahli yang gagal mematuhi undang-undang Pertubuhan atau bertindak dengan cara yang akan mencemarkan nama baik Pertubuhan boleh dipecat atau digantung keahliannya."
						],
						[
							'no' => '6', 
							'nama' => 'SUMBER KEWANGAN', 
							'desc' => "Sumber kewangan pertubuhan ini adalah daripada:\n\n(1) Bayaran Masuk (Ahli Biasa dan Ahli Seumur Hidup)\n    a) RM 10.00 (Ringgit Malaysia Sepuluh Sahaja)\n    b) Yuran bulanan (Ahli Biasa) RM 50.00\n    c) Yuran keahlian Seumur Hidup RM 600.00\n\n(2) Yuran bulanan hendaklah dijelaskan kepada Bendahari terlebih dahulu dalam tempoh 30 hari."
						],
						[
							'no' => '7', 
							'nama' => 'MESYUARAT AGUNG', 
							'desc' => "(1) Pengelolaan Pertubuhan ini terserah kepada mesyuarat agung pertubuhan. Sekurang-kurangnya satu perdua (1/2) daripada jumlah ahli yang berhak mengundi hendaklah hadir.\n\n(2) Jika korum tidak cukup selepas setengah jam, maka mesyuarat itu hendaklah ditangguhkan.\n\n(3) Mesyuarat Agung Tahunan hendaklah diadakan tidak lewat daripada 31hb.Mac."
						],
						[
							'no' => '8', 
							'nama' => 'JAWATANKUASA', 
							'desc' => "(1) Satu Jawatankuasa seperti berikut yang dinamakan Pegawai Pertubuhan hendaklah dipilih:\n- Seorang Presiden\n- Seorang Naib Presiden\n- Seorang Setiausaha\n- Seorang Penolong Setiausaha\n- Seorang Bendahari\n- 6 Orang Ahli Jawatankuasa Biasa\n\n(2) Pemegang-pemegang jawatan hendaklah terdiri dari Warganegara Malaysia."
						],
						[
							'no' => '9', 
							'nama' => 'KEWAJIPAN-KEWAJIPAN PEGAWAI', 
							'desc' => "(1) Presiden hendaklah menjadi Pengerusi semua mesyuarat agung dan semua mesyuarat Jawatankuasa.\n\n(2) Naib Presiden hendaklah memangku jawatan Presiden semasa ketiadaannya.\n\n(3) Setiausaha hendaklah menjalankan kerja pentadbiran Pertubuhan mengikut undang-undang."
						],
						[
							'no' => '10', 
							'nama' => 'KEWANGAN', 
							'desc' => "(1) Wang Pertubuhan ini boleh digunakan untuk perkara yang berfaedah bagi menjalankan tujuan-tujuan Pertubuhan.\n\n(2) Bendahari dibenarkan menyimpan wang runcit tidak lebih daripada RM 3000.00.\n\n(3) Segala cek hendaklah ditandatangani bersama oleh Presiden dan Setiausaha atau Bendahari."
						],
						[
							'no' => '11', 
							'nama' => 'JURUAUDIT', 
							'desc' => "(1) Dua orang yang bukannya Pegawai Pertubuhan hendaklah dilantik di dalam Mesyuarat Agung Tahunan sebagai Juruaudit.\n\n(2) Juruaudit adalah dikehendaki memeriksa kira-kira Pertubuhan bagi setahun dan membuat laporan."
						],
						[
							'no' => '12', 
							'nama' => 'PENTADBIR HARTA', 
							'desc' => "1) Tiga orang Pemegang Amanah yang berumur lebih daripada 21 tahun hendaklah dilantik di dalam Mesyuarat Agung.\n\n2) Pemegang Amanah tidak boleh menjual, menarik balik atau menukar milik harta kepunyaan Pertubuhan dengan tidak mendapat kelulusan."
						],
						[
							'no' => '13', 
							'nama' => 'TAFSIRAN PERLEMBAGAAN PERTUBUHAN', 
							'desc' => "(1) Di antara berlangsungnya Mesyuarat Agung, Jawatankuasa boleh memberikan tafsirannya kepada undang-undang ini.\n\n(2) Keputusan Jawatankuasa terhadap ahli-ahli adalah muktamad jika tidak diubah oleh keputusan Mesyuarat Agung."
						],
						[
							'no' => '14', 
							'nama' => 'PENASIHAT/PENAUNG', 
							'desc' => "Jawatankuasa boleh, jika difikirkan perlu, melantik orang-orang yang layak menjadi Penasihat/Penaung bagi Pertubuhan ini dengan syarat orang yang dilantik itu menyatakan persetujuannya secara bertulis."
						],
						[
							'no' => '15', 
							'nama' => 'LARANGAN', 
							'desc' => "(1) Permainan yang disebutkan di bawah ini tidak boleh dimainkan di dalam ruang Pertubuhan: Roulette, Lotte, Fan Tan, Pob, Peh Bin, Belangkai, Pai Kau, Tau Ngau, Tien Kow, Chap Ji Kee, Sam Cheong, Dua Puluh Satu, dan semua permainan dadu.\n\n(2) Pertubuhan tidak boleh menjalankan loteri tanpa kelulusan."
						],
						[
							'no' => '16', 
							'nama' => 'PINDAAN PERLEMBAGAAN', 
							'desc' => "Undang-undang ini tidak boleh diubah atau dipinda kecuali dengan keputusan Mesyuarat Agung. Permohonan untuk perubahan atau pindaan hendaklah dibuat kepada Pendaftar Pertubuhan dalam masa 60 hari dari tarikh keputusan Mesyuarat Agung."
						],
						[
							'no' => '17', 
							'nama' => 'PEMBUBARAN', 
							'desc' => "(1) Pertubuhan ini boleh dibubarkan secara sukarela dengan persetujuan tidak kurang daripada tiga perlima (3/5) daripada jumlah ahli yang menghadiri dalam suatu Mesyuarat Agung.\n\n(2) Segala hutang dan tanggungan Pertubuhan hendaklah dijelaskan."
						],
						[
							'no' => '18', 
							'nama' => 'BENDERA, LAMBANG DAN LENCANA', 
							'desc' => "(a) Lambang Pertubuhan merupakan satu bulatan biru yang diikuti oleh satu lilitan oren dengan lambing \"Khanda\" yang merupakan lambang kaum Punjabi ditengah-tengahnya. Perkataan \"Sarbaat Ka Bhalla\" adalah perkataan Punjabi yang bermaksud \"Demi kebaikan, kesejahteraan dan keharmonian alam sejagat\"."
						],
						[
							'no' => '19', 
							'nama' => 'LAMPIRAN', 
							'desc' => "1. Bendera<br /><br/><img width=\"200\" src=\"https://doc.ros.gov.my/bendera/-\" class=\"border shadow-sm bg-white p-1\" alt=\"Tiada Bendera\" /><br /><br/>Keterangan :<br /><br/>-<br /><br/>2. Lambang<br /><br/><img width=\"200\" src=\"https://doc.ros.gov.my/lambang/1741568983.jpg\" class=\"border shadow-sm bg-white p-1\" alt=\"Lambang Pertubuhan\" /><br /><br/>Keterangan:<br /><br/>-<br /><br/>3. Lencana<br /><br/><img width=\"200\" src=\"https://doc.ros.gov.my/lencana/-\" class=\"border shadow-sm bg-white p-1\" alt=\"Tiada Lencana\" />"
						]
					];
					
				?>
				<div class="col-xl-8 col-lg-8 right-scroll">

					<ul class="nav nav-tabs custom-teal-tabs mb-4" id="rightPanelTabs" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active fw-bold py-3 px-4" style="font-size: 1.05rem;" id="pertubuhan-tab" data-bs-toggle="tab" data-bs-target="#tab-pertubuhan" type="button" role="tab" aria-controls="tab-pertubuhan" aria-selected="true">
								<i class="bi bi-building me-2 fs-5"></i>Maklumat Pertubuhan
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link fw-bold text-muted py-3 px-4" style="font-size: 1.05rem;" id="fixissue-tab" data-bs-toggle="tab" data-bs-target="#tab-fixissue" type="button" role="tab" aria-controls="tab-fixissue" aria-selected="false">
								<i class="bi bi-tools me-2 fs-5"></i>Fix Issue
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link fw-bold text-muted py-3 px-4" style="font-size: 1.05rem;" id="tba-tab" data-bs-toggle="tab" data-bs-target="#tab-tba" type="button" role="tab" aria-controls="tab-tba" aria-selected="false">
								<i class="bi bi-clock-history me-2 fs-5"></i>TBA
							</button>
						</li>
					</ul>

					<div class="tab-content" id="rightPanelTabsContent">
						
						<div class="tab-pane fade show active" id="tab-pertubuhan" role="tabpanel" aria-labelledby="pertubuhan-tab">
							<div class="card bg-primary bg-opacity-10 border-primary border-opacity-25 mb-4 w-100" style="z-index: 100;">
								<div class="card-body p-3 p-md-4">
									<label class="fw-bold text-primary mb-2">Carian Pangkalan Data Pertubuhan</label>
									
									<form method="GET" action="view_aduan.php" class="position-relative w-100">
										<input type="hidden" name="id" value="<?php echo htmlspecialchars($aduan_id); ?>">
										
										<div class="input-group shadow-sm w-100" style="height: 50px;">
											<span class="input-group-text bg-white border-end-0 px-3"><i class="bi bi-search text-muted"></i></span>
											<input type="text" name="society_no" class="form-control border-start-0 flex-grow-1" placeholder="Masukkan No. Pendaftaran Pertubuhan (Cth: PPM-001...)" value="<?php echo htmlspecialchars($search_society_no ?? ''); ?>" autocomplete="off" style="height: 50px; font-size: 0.95rem; box-shadow: none; min-width: 0;" required>
											<button type="submit" class="btn btn-primary px-4 fw-bold flex-shrink-0" style="height: 50px;">Cari</button>
										</div>
									</form>
									
								</div>
							</div>
							<?php if (!empty($pertubuhan)): ?>
								
									<?php else: ?>
									
									<div class="card shadow-sm border-0 bg-light text-center" style="min-height: 300px;">
										<div class="card-body d-flex flex-column align-items-center justify-content-center p-5">
											<i class="bi bi-buildings text-muted opacity-25 mb-3" style="font-size: 5rem;"></i>
											<?php if (!empty($search_society_no)): ?>
												<h5 class="fw-bold text-dark">Rekod Tidak Dijumpai</h5>
												<p class="text-muted">Tiada pertubuhan padan dengan nombor: <b class="text-danger"><?php echo htmlspecialchars($search_society_no); ?></b></p>
											<?php else: ?>
												<h5 class="fw-bold text-dark">Pangkalan Data Pertubuhan</h5>
												<p class="text-muted">Sila buat carian menggunakan No. Pendaftaran Pertubuhan untuk memaparkan rekod lengkap di ruangan ini.</p>
											<?php endif; ?>
										</div>
									</div>
									
								<?php endif; ?>

							<div class="card shadow-sm border-0 mb-4">
								<div class="card-header bg-white border-bottom">
									<h6 class="mb-0 text-primary fw-bold"><i class="bi bi-building me-2"></i>Maklumat Pertubuhan</h6>
								</div>
								<div class="card-body">
									
									<div class="row mb-3 pb-2 border-bottom">
										<div class="col-md-4">
											<label class="form-label">No. Pertubuhan</label>
											<div class="form-control-plaintext text-dark fw-bold">
												<?php echo htmlspecialchars($pertubuhan['society_no'] ?? '-'); ?>
											</div>
										</div>
										<div class="col-md-8">
											<label class="form-label">Nama Pertubuhan</label>
											<div class="form-control-plaintext text-dark fw-bold">
												<?php echo htmlspecialchars($pertubuhan['society_name'] ?? '-'); ?>
											</div>
										</div>
									</div>
									
									<div class="row mb-3 pb-2 border-bottom">
										<div class="col-md-4">
											<label class="form-label">Kategori</label>
											<div class="form-control-plaintext text-muted">
												<?php echo htmlspecialchars($pertubuhan['nama_kategori'] ?? '-'); ?>
											</div>
										</div>
										<div class="col-md-4">
											<label class="form-label">Subkategori</label>
											<div class="form-control-plaintext text-muted">
												<?php echo htmlspecialchars($pertubuhan['nama_subkategori'] ?? '-'); ?>
											</div>
										</div>
										<div class="col-md-4">
											<label class="form-label">Taraf</label>
											<div class="form-control-plaintext">
												<span class="badge bg-success px-3 py-1">
													<?php echo htmlspecialchars($pertubuhan['taraf_pertubuhan'] ?? '-'); ?>
												</span>
											</div>
										</div>
									</div>

									<div class="row mb-4 pb-2 border-bottom">
										<div class="col-md-4">
											<label class="form-label">Status Pertubuhan</label>
											<div class="form-control-plaintext text-dark fw-bold mb-0 pb-0">
												<?php 
													// Papar Status (Contoh output: "AKTIF" dari kod "001")
													echo empty($teks_status) ? '-' : htmlspecialchars($teks_status); 
												?>
											</div>
											
											<?php 
											// Hanya paparkan sub-status kalau ia wujud (bukan kosong atau NULL)
											if(isset($kod_substatus) && $kod_substatus !== ''): 
											?>
											<div class="text-danger mt-0" style="font-size: 0.75rem; font-weight: 600;">
												(<?php echo htmlspecialchars($teks_substatus); ?>)
											</div>
											<?php endif; ?>
										</div>
										
										<div class="col-md-8">
											<label class="form-label">Status Permohonan</label>
											<div class="form-control-plaintext">
												<?php 
												// Tentukan warna lencana berdasarkan status (Opsional: bos boleh tambah logik warna di sini)
												$badge_class = "bg-warning text-dark"; 
												if ($kod_application == '3' || $kod_application == '11' || $kod_application == '17') {
													$badge_class = "bg-success"; // Lulus / Aktif / Selesai
												} elseif ($kod_application == '4' || $kod_application == '20') {
													$badge_class = "bg-danger"; // Tolak / Batal
												}
												?>
												<span class="badge <?php echo $badge_class; ?> px-3 py-1 shadow-sm">
													<i class="bi bi-info-circle me-1"></i>
													<?php 
														// Papar Status Permohonan (Contoh output: "MENUNGGU KEPUTUSAN" dari kod "2")
														echo empty($teks_application) ? '-' : htmlspecialchars($teks_application); 
													?>
												</span>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6 border-end pe-md-4 mb-3 mb-md-0">
											<label class="form-label text-primary">Alamat Tempat Urusan</label>
											<div class="form-control-plaintext text-dark mt-1" style="font-size: 0.88rem; line-height: 1.6;">
												<?php echo htmlspecialchars($pertubuhan['address'] ?? '-'); ?><br>
												<?php echo htmlspecialchars($pertubuhan['postcode'] ?? '') . " " . htmlspecialchars($pertubuhan['city'] ?? ''); ?><br>
												<?php echo htmlspecialchars($pertubuhan['daerah_urusan'] ?? '-'); ?><br>
												<span class="fw-bold"><?php echo htmlspecialchars($pertubuhan['negeri_urusan'] ?? '-'); ?></span>
											</div>
										</div>
										
										<div class="col-md-6 ps-md-4">
											<label class="form-label text-primary">Alamat Surat-menyurat</label>
											<div class="form-control-plaintext text-dark mt-1" style="font-size: 0.88rem; line-height: 1.6;">
												<?php echo htmlspecialchars($pertubuhan['mailing_address'] ?? '-'); ?><br>
												<?php echo htmlspecialchars($pertubuhan['mailing_postcode'] ?? '') . " " . htmlspecialchars($pertubuhan['mailing_city'] ?? ''); ?><br>
												<?php echo htmlspecialchars($pertubuhan['daerah_surat'] ?? '-'); ?><br>
												<span class="fw-bold"><?php echo htmlspecialchars($pertubuhan['negeri_surat'] ?? '-'); ?></span>
											</div>
										</div>
									</div>

								</div>
							</div>


							<div class="row mb-4 align-items-stretch">
								
								<div class="col-md-6 mb-3 mb-md-0">
									<div class="card shadow-sm border-0 h-100">
										<div class="card-header bg-white border-bottom">
											<h6 class="mb-0 text-primary fw-bold"><i class="bi bi-journal-text me-2"></i>Senarai Pindaan</h6>
										</div>
										<div class="card-body p-0">
											<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
												<table class="table table-hover table-sm mb-0" style="font-size: 0.85rem;">
													<thead class="table-light text-muted sticky-top">
														<tr>
															<th class="text-center" width="5%">Bil.</th>
															<th>Tujuan Pindaan</th>
															<th class="text-center">Tarikh</th>
															<th class="text-center">Status</th>
														</tr>
													</thead>
													<tbody>
														<?php if(!empty($senarai_pindaan)): ?>
															<?php foreach($senarai_pindaan as $index => $pindaan): 
																
																$tarikh_pindaan = !empty($pindaan['payment_date']) ? date('d/m/Y', strtotime($pindaan['payment_date'])) : '-';
																
																$kod_status_pindaan = (string)($pindaan['application_status_code'] ?? '');
																$teks_status_pindaan = $statusEnum[$kod_status_pindaan] ?? $kod_status_pindaan;
																
																// Logik Warna Badge
																$badge_class = "bg-warning text-dark";
																if (in_array($kod_status_pindaan, ['3', '9', '11', '17'])) { 
																	$badge_class = "bg-success";
																} elseif (in_array($kod_status_pindaan, ['4', '10', '16', '20', '002', '003', '005'])) {
																	$badge_class = "bg-danger";
																}
																
																// Logik Highlight: Semak adakah baris ini yang sedang dipaparkan perlembagaannya
																$is_active_row = ($pindaan['id'] == $amendment_id) ? 'table-active border-start border-primary border-4' : '';
															?>
															
															<tr class="<?php echo $is_active_row; ?>" 
																style="cursor: pointer;" 
																onclick="window.location.href='view_aduan.php?id=<?php echo $aduan_id; ?>&society_no=<?php echo urlencode($search_society_no); ?>&amendment_id=<?php echo $pindaan['id']; ?>'">
																
																<td class="text-center fw-bold text-muted align-middle"><?php echo $index + 1; ?></td>
																<td class="fw-medium text-dark align-middle">
																	<?php echo htmlspecialchars($pindaan['goal'] ?? '-'); ?>
																</td>
																<td class="text-center align-middle"><?php echo $tarikh_pindaan; ?></td>
																<td class="text-center align-middle">
																	<span class="badge <?php echo $badge_class; ?> px-2 py-1 shadow-sm">
																		<?php echo htmlspecialchars($teks_status_pindaan ?: '-'); ?>
																	</span>
																</td>
															</tr>
															
															<?php endforeach; ?>
														<?php else: ?>
															<tr>
																<td colspan="4" class="text-center text-muted py-4">
																	<i class="bi bi-inbox fs-4 d-block mb-2 opacity-50"></i>Tiada rekod pindaan dijumpai.
																</td>
															</tr>
														<?php endif; ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>

								<div class="col-md-6 mb-3 mb-md-0">
									<div class="card shadow-sm border-0 h-100">
										<div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-2">
											<div class="d-flex align-items-center gap-2">
												<h6 class="mb-0 text-primary fw-bold"><i class="bi bi-book-half me-2"></i>Perlembagaan</h6>
												
												<span class="badge bg-info rounded-pill" style="font-size: 0.72rem; font-weight: 600;">
													 Perlembagaan induk NGO
												</span>
												<span class="badge bg-secondary rounded-pill" style="font-size: 0.72rem; font-weight: 600;">
													<?php echo count($senarai_perlembagaan); ?> Fasal
												</span>
											</div>
											<button type="button" class="btn btn-xs btn-outline-primary fw-bold" id="btnToggleAllFasal" onclick="toggleAllFasal()" style="font-size: 0.75rem; padding: 3px 10px; border-radius: 6px;">
												<i class="bi bi-eye me-1"></i>Buka Semua
											</button>
										</div>
										<div class="card-body p-0">
											<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
												<table class="table table-hover mb-0" style="font-size: 0.85rem;">
													<tbody>
														<?php if(!empty($senarai_perlembagaan)): ?>
															<?php foreach($senarai_perlembagaan as $index => $fasal): 
																
																// 1. LOGIK KEBAL (Fallback jika column DB kosong atau terbaca data dummy lama)
																$no_fasal = $fasal['clause_no'] ?? $fasal['no'] ?? ($index + 1);
																$nama_fasal = $fasal['clause_name'] ?? $fasal['nama'] ?? '-';
																$desc_fasal = $fasal['description'] ?? $fasal['desc'] ?? 'Tiada keterangan disediakan.';
																
																// 2. Pastikan nilai ditukar kepada string untuk elak ralat preg_replace
																$id_bersih = preg_replace('/[^a-zA-Z0-9]/', '', (string)$no_fasal);
																$collapseId = 'collapseFasal' . $id_bersih;
															?>
															
															<tr class="bg-light" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapseId; ?>" style="cursor: pointer;">
																<td width="20%" class="text-center fw-bold text-primary align-middle border-bottom-0">
																	Fasal <?php echo htmlspecialchars($no_fasal); ?>
																</td>
																<td class="fw-bold text-dark align-middle border-bottom-0">
																	<div class="d-flex justify-content-between align-items-center">
																		<span><?php echo htmlspecialchars($nama_fasal); ?></span>
																		<i class="bi bi-chevron-down text-muted chevron-icon" style="font-size: 0.8rem; transition: transform 0.2s;"></i>
																	</div>
																</td>
															</tr>
															
															<tr>
																<td colspan="2" class="p-0 border-0">
																	<div id="<?php echo $collapseId; ?>" class="collapse fasal-collapse bg-white">
																		<div class="text-muted pb-4 px-4 pt-3 border-bottom border-2" style="white-space: pre-line; text-align: justify; line-height: 1.6;">
																			<?php echo nl2br($desc_fasal); ?>
																		</div>
																	</div>
																</td>
															</tr>

															<?php endforeach; ?>
														<?php else: ?>
															<tr>
																<td colspan="2" class="text-center text-muted py-4">
																	<i class="bi bi-book fs-4 d-block mb-2 opacity-50"></i>
																	Tiada rekod perlembagaan dijumpai.
																</td>
															</tr>
														<?php endif; ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>

							</div>


							<div class="card shadow-sm border-0 mb-4">
								<div class="card-header bg-white border-bottom">
									<h6 class="mb-0 text-primary fw-bold"><i class="bi bi-calendar-event me-2"></i>Pengurusan Tarikh Lantikan</h6>
								</div>
								<div class="card-body">
									<div class="row align-items-center">
										<div class="col-md-6 mb-3 mb-md-0 border-end">
											<label class="form-label text-muted">Tarikh Lantikan Asal (Rekod Sistem)</label>
											<input type="text" class="form-control bg-light text-muted fw-bold border-0" value="10/05/2026" disabled readonly>
										</div>
										<div class="col-md-6 ps-md-4">
											<label class="form-label text-dark fw-bold">Pilih Tarikh Untuk Semakan AJK</label>
											<select class="form-select border-primary shadow-sm fw-bold text-primary" id="selectTarikhAJK" aria-label="Pilih Tarikh Lantikan">
												<option value="10 Mei 2026" selected>10 Mei 2026 (Rekod Asal)</option>
												<option value="15 Jun 2026">15 Jun 2026 (Mesyuarat Agung)</option>
												<option value="20 Julai 2026">20 Julai 2026 (Mesyuarat Khas)</option>
											</select>
										</div>
									</div>
								</div>
							</div>


							<div class="row mb-5 align-items-stretch" id="kawasanJadualAJK">
								
								<div class="col-md-6 mb-3 mb-md-0">
									<div class="card shadow-sm border-0 h-100">
										<div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
											<h6 class="mb-0 text-primary fw-bold"><i class="bi bi-people-fill me-2"></i>Senarai AJK</h6>
											<span class="badge bg-primary px-3 py-2 shadow-sm" style="font-size: 0.75rem;">10 Mei 2026</span>
										</div>
										<div class="card-body p-0">
											<div class="table-responsive">
												<table class="table table-hover table-sm mb-0" style="font-size: 0.85rem;">
													<thead class="table-light text-muted">
														<tr>
															<th class="text-center" width="5%">Bil.</th>
															<th>Nama & No Pengenalan</th>
															<th>Jawatan</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td class="text-center fw-bold text-muted">1</td>
															<td>
																<div class="fw-bold text-dark">Ahmad bin Ali</div>
																<div class="text-muted" style="font-size: 0.75rem;">780101-05-5555</div>
															</td>
															<td><span class="badge bg-primary">Pengerusi</span></td>
														</tr>
														<tr>
															<td class="text-center fw-bold text-muted">2</td>
															<td>
																<div class="fw-bold text-dark">Muthusamy a/l Rajan</div>
																<div class="text-muted" style="font-size: 0.75rem;">750909-08-7777</div>
															</td>
															<td><span class="badge bg-info text-dark">Timbalan Pengerusi</span></td>
														</tr>
														<tr>
															<td class="text-center fw-bold text-muted">3</td>
															<td>
																<div class="fw-bold text-dark">Siti Aminah binti Kassim</div>
																<div class="text-muted" style="font-size: 0.75rem;">820512-10-6666</div>
															</td>
															<td><span class="badge bg-success">Setiausaha</span></td>
														</tr>
														<tr>
															<td class="text-center fw-bold text-muted">4</td>
															<td>
																<div class="fw-bold text-dark">Wong Kah Fai</div>
																<div class="text-muted" style="font-size: 0.75rem;">801122-14-5543</div>
															</td>
															<td><span class="badge bg-warning text-dark">Bendahari</span></td>
														</tr>
														<tr>
															<td class="text-center fw-bold text-muted">5</td>
															<td>
																<div class="fw-bold text-dark">Nurul Huda binti Othman</div>
																<div class="text-muted" style="font-size: 0.75rem;">900315-01-2334</div>
															</td>
															<td><span class="badge bg-secondary">AJK Biasa</span></td>
														</tr>
														<tr>
															<td class="text-center fw-bold text-muted">6</td>
															<td>
																<div class="fw-bold text-dark">Chandran a/l Velu</div>
																<div class="text-muted" style="font-size: 0.75rem;">850718-08-1123</div>
															</td>
															<td><span class="badge bg-secondary">AJK Biasa</span></td>
														</tr>
														<tr>
															<td class="text-center fw-bold text-muted">7</td>
															<td>
																<div class="fw-bold text-dark">Sarah Jane</div>
																<div class="text-muted" style="font-size: 0.75rem;">880912-13-4456</div>
															</td>
															<td><span class="badge bg-secondary">AJK Biasa</span></td>
														</tr>
														<tr>
															<td class="text-center fw-bold text-muted">8</td>
															<td>
																<div class="fw-bold text-dark">Mohd Faizal bin Rahmat</div>
																<div class="text-muted" style="font-size: 0.75rem;">920101-03-8899</div>
															</td>
															<td><span class="badge bg-secondary">AJK Biasa</span></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="card bg-warning bg-opacity-10 border border-warning border-opacity-50 h-100 shadow-sm">
										<div class="card-header border-bottom border-warning border-opacity-50 d-flex justify-content-between align-items-center" style="background-color: transparent;">
											<h6 class="mb-0 text-dark fw-bold"><i class="bi bi-archive-fill text-warning me-2"></i>Draf AJK</h6>
											<span class="badge bg-warning text-dark px-3 py-2 shadow-sm label-tarikh-ajk border border-warning label-tarikh-ajk" style="font-size: 0.75rem;">10 Mei 2026</span>
										</div>
										<div class="card-body p-0">
											<div class="table-responsive">
												<table class="table table-hover table-sm mb-0 table-ajk-dinamik" style="font-size: 0.85rem; background-color: transparent;">
													<thead class="text-muted border-bottom border-warning border-opacity-50">
														<tr>
															<th class="text-center" width="5%">Bil.</th>
															<th>Nama & No Pengenalan</th>
															<th>Jawatan Lama</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td class="text-center fw-bold text-muted">1</td>
															<td>
																<div class="fw-bold text-dark">Ghazali bin Abu</div>
																<div class="text-muted" style="font-size: 0.75rem;">650202-01-1111</div>
															</td>
															<td class="text-muted">Pengerusi</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>

							</div>
							

						</div> 
						<div class="tab-pane fade" id="tab-fixissue" role="tabpanel" aria-labelledby="fixissue-tab">
            
							<div class="input-group mb-4 shadow-sm">
								<span class="input-group-text bg-white border-end-0 py-3"><i class="bi bi-search text-muted"></i></span>
								<input type="text" id="searchInputFixIssue" onkeyup="searchAndHighlight(event)" class="form-control border-start-0 form-control-lg" style="font-size: 1rem; box-shadow: none;" placeholder="Cari fungsi cth: 'AJK', 'Nama', 'Perlembagaan'...">
								<button class="btn btn-primary px-4 fw-bold" onclick="searchAndHighlight(event)">Cari</button>
							</div>

							<h5 class="fw-bold text-dark mb-3 mt-2 pb-2 border-bottom">
								<i class="bi bi-people-fill text-primary me-2"></i>Pengurusan Jawatankuasa
							</h5>
							<div class="row g-3 mb-5">
								<div class="col-md-6">
									<div class="card border-0 shadow-sm h-100">
										<div class="card-body d-flex align-items-center p-3">
											<div class="bg-primary bg-opacity-10 text-primary rounded p-3 me-3">
												<i class="bi bi-people-fill fs-4"></i>
											</div>
											<div class="flex-grow-1">
												<h6 class="fw-bold mb-1" style="font-size: 0.95rem;">Nyahaktif AJK Berganda</h6>
												<p class="text-muted mb-0" style="font-size: 0.8rem;">Auto-nyahaktif AJK duplikasi berdasarkan tarikh lantikan terawal.</p>
											</div>
											<button class="btn btn-sm btn-outline-primary fw-bold ms-2 px-3 flex-shrink-0" data-bs-toggle="modal" data-bs-target="#modalAjkDouble">Proceed</button>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card border-0 shadow-sm h-100">
										<div class="card-body d-flex align-items-center p-3">
											<div class="bg-primary bg-opacity-10 text-primary rounded p-3 me-3">
												<i class="bi bi-person-badge-fill fs-4"></i>
											</div>
											<div class="flex-grow-1">
												<h6 class="fw-bold mb-1" style="font-size: 0.95rem;">Padam SU Berganda</h6>
												<p class="text-muted mb-0" style="font-size: 0.8rem;">Mengekalkan hanya satu Setiausaha berdasarkan kelulusan rasmi terakhir.</p>
											</div>
											<button class="btn btn-sm btn-outline-primary fw-bold ms-2 px-3 flex-shrink-0" data-bs-toggle="modal" data-bs-target="#modalSuDouble">Proceed</button>
										</div>
									</div>
								</div>
							</div>

							<h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
								<i class="bi bi-file-earmark-text-fill text-success me-2"></i>Pengurusan Dokumen & Identiti
							</h5>
							<div class="row g-3 mb-5">
								<div class="col-md-6">
									<div class="card border-0 shadow-sm h-100">
										<div class="card-body d-flex align-items-center p-3">
											<div class="bg-success bg-opacity-10 text-success rounded p-3 me-3">
												<i class="bi bi-file-earmark-pdf-fill fs-4"></i>
											</div>
											<div class="flex-grow-1">
												<h6 class="fw-bold mb-1" style="font-size: 0.95rem;">Jana Semula Dokumen</h6>
												<p class="text-muted mb-0" style="font-size: 0.8rem;">Bina semula fail Sijil/Maklumat Pertubuhan yang rosak atau hilang.</p>
											</div>
											<button class="btn btn-sm btn-outline-success fw-bold ms-2 px-3 flex-shrink-0" data-bs-toggle="modal" data-bs-target="#modalRegenDocs">Proceed</button>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="card border-0 shadow-sm h-100">
										<div class="card-body d-flex align-items-center p-3">
											<div class="bg-success bg-opacity-10 text-success rounded p-3 me-3">
												<i class="bi bi-pencil-square fs-4"></i>
											</div>
											<div class="flex-grow-1">
												<h6 class="fw-bold mb-1" style="font-size: 0.95rem;">Kemaskini Nama Pertubuhan</h6>
												<p class="text-muted mb-0" style="font-size: 0.8rem;">Tukar society_name & origin_name serta jana semula sijil baru.</p>
											</div>
											<button class="btn btn-sm btn-outline-success fw-bold ms-2 px-3 flex-shrink-0" data-bs-toggle="modal" data-bs-target="#modalRenameSociety">Proceed</button>
										</div>
									</div>
								</div>
							</div>

							<h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
								<i class="bi bi-book-half text-info me-2"></i>Penyelenggaraan Perlembagaan
							</h5>
							<div class="row g-3 mb-5">
								<div class="col-md-12">
									<div class="card border-0 shadow-sm h-100">
										<div class="card-body d-flex align-items-center p-3">
											<div class="bg-info bg-opacity-10 text-info rounded p-3 me-3">
												<i class="bi bi-wrench-adjustable fs-4"></i>
											</div>
											<div class="flex-grow-1">
												<h6 class="fw-bold mb-1" style="font-size: 0.95rem;">Pulih Paparan Perlembagaan</h6>
												<p class="text-muted mb-0" style="font-size: 0.8rem;">Paksa tukar status setiap fasal pertubuhan yang sangkut kepada status Aktif (Kod 11).</p>
											</div>
											<button class="btn btn-sm btn-outline-info fw-bold ms-2 px-3 flex-shrink-0" data-bs-toggle="modal" data-bs-target="#modalFixConstitution">Proceed</button>
										</div>
									</div>
								</div>
							</div>

							<h5 class="fw-bold text-danger mb-3 pb-2 border-bottom border-danger opacity-75">
								<i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Tindakan Kritikal
							</h5>
							<div class="row g-3 mb-4">
								<div class="col-md-12">
									<div class="card border-0 shadow-sm h-100" style="background-color: #fff5f5;">
										<div class="card-body d-flex align-items-center p-3">
											<div class="bg-danger bg-opacity-10 text-danger rounded p-3 me-3">
												<i class="bi bi-arrow-counterclockwise fs-4"></i>
											</div>
											<div class="flex-grow-1">
												<h6 class="fw-bold text-danger mb-1" style="font-size: 0.95rem;">Tarik Balik Keputusan (Revert)</h6>
												<p class="text-muted mb-0" style="font-size: 0.8rem;">Menukar status Lulus ke Menunggu, menyembunyikan dokumen, dan memadam log kelulusan.</p>
											</div>
											<button class="btn btn-sm btn-danger fw-bold ms-2 px-3 shadow-sm flex-shrink-0" data-bs-toggle="modal" data-bs-target="#modalRevertStatus">Proceed</button>
										</div>
									</div>
								</div>
							</div>

						</div>

						
						<div class="tab-pane fade" id="tab-tba" role="tabpanel" aria-labelledby="tba-tab">
							<div class="card shadow-sm border-0 bg-light text-center" style="min-height: 400px;">
								<div class="card-body d-flex flex-column align-items-center justify-content-center p-5">
									<i class="bi bi-clock-history text-muted opacity-25 mb-3" style="font-size: 5rem;"></i>
									<h5 class="fw-bold text-dark">TBA</h5>
									<p class="text-muted">Kalau ada idea nak letak apa sini</p>
								</div>
							</div>
						</div> 
						
					</div> 
				</div>

				
		</div>
    </div>
	
	
	<div class="modal fade" id="modalAjkDouble" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content border-0 shadow">
				<div class="modal-header bg-primary text-white py-3">
					<h6 class="modal-title fw-bold"><i class="bi bi-people-fill me-2"></i>Sahkan Tindakan AJK Berganda</h6>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4 text-center">
					<i class="bi bi-question-circle text-primary opacity-50" style="font-size: 3.5rem;"></i>
					<h5 class="fw-bold text-dark mt-3">Nyahaktifkan Rekod Duplikasi?</h5>
					<p class="text-muted small">Sistem akan menyemak rekod Jawatankuasa bagi pertubuhan ini dan mengekalkan rekod terkini sahaja. Tindakan ini akan mengubah status data.</p>
				</div>
				<div class="modal-footer bg-light border-top-0 py-2">
					<button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-sm btn-primary fw-bold px-3">Ya, Teruskan</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalSuDouble" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content border-0 shadow">
				<div class="modal-header bg-primary text-white py-3">
					<h6 class="modal-title fw-bold"><i class="bi bi-person-badge-fill me-2"></i>Sahkan Tindakan SU Berganda</h6>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4 text-center">
					<i class="bi bi-exclamation-circle text-primary opacity-50" style="font-size: 3.5rem;"></i>
					<h5 class="fw-bold text-dark mt-3">Padam Setiausaha Berganda?</h5>
					<p class="text-muted small">Tindakan ini akan mengekalkan nama Setiausaha yang terakhir diluluskan dan memadamkan rekod pertindihan yang lain.</p>
				</div>
				<div class="modal-footer bg-light border-top-0 py-2">
					<button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-sm btn-primary fw-bold px-3">Ya, Teruskan</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalRegenDocs" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content border-0 shadow">
				<div class="modal-header bg-success text-white py-3">
					<h6 class="modal-title fw-bold"><i class="bi bi-file-earmark-pdf-fill me-2"></i>Sahkan Penjanaan Dokumen</h6>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4 text-center">
					<i class="bi bi-files text-success opacity-50" style="font-size: 3.5rem;"></i>
					<h5 class="fw-bold text-dark mt-3">Jana Semula Dokumen Sijil?</h5>
					<p class="text-muted small">Sistem akan memadam fail cache lama dan membina semula dokumen PDF rasmi berdasarkan maklumat terkini dalam pangkalan data.</p>
				</div>
				<div class="modal-footer bg-light border-top-0 py-2">
					<button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-sm btn-success fw-bold px-3">Jana Sekarang</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalRenameSociety" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content border-0 shadow">
				<div class="modal-header bg-success text-white py-3">
					<h6 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Borang Kemaskini Nama Pertubuhan</h6>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form action="proses_fix.php" method="POST">
					<div class="modal-body p-4">
						<div class="mb-3">
							<label class="form-label fw-bold small text-muted">Nama Pertubuhan Baharu (BM)</label>
							<input type="text" class="form-control" name="new_society_name" placeholder="Masukkan nama penuh huruf besar" required>
						</div>
						<div class="mb-3">
							<label class="form-label fw-bold small text-muted">Nama Asal Baharu (Origin Name)</label>
							<input type="text" class="form-control" name="new_origin_name" placeholder="Masukkan nama asal pertubuhan">
						</div>
						<div class="alert alert-info py-2 m-0 small" style="font-size: 0.78rem;">
							<i class="bi bi-info-circle me-1"></i> Sistem akan mengemas kini nama dalam database dan menjana semula dokumen sijil secara automatik.
						</div>
					</div>
					<div class="modal-footer bg-light border-top-0 py-2">
						<button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
						<button type="submit" class="btn btn-sm btn-success fw-bold px-3">Simpan & Cetak</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalFixConstitution" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content border-0 shadow">
				<div class="modal-header bg-info text-white py-3">
					<h6 class="modal-title fw-bold"><i class="bi bi-book-half me-2"></i>Sahkan Pemulihan Perlembagaan</h6>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4 text-center">
					<i class="bi bi-wrench text-info opacity-50" style="font-size: 3.5rem;"></i>
					<h5 class="fw-bold text-dark mt-3">Pulihkan Status Fasal?</h5>
					<p class="text-muted small">Semua status fasal perlembagaan bagi pertubuhan ini akan ditukar secara paksa kepada <b>Kod 11 (Aktif)</b>. Pastikan anda telah menyemak rekod sebelum meneruskan.</p>
				</div>
				<div class="modal-footer bg-light border-top-0 py-2">
					<button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-sm btn-info fw-bold px-3 text-white">Pulihkan Data</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalRevertStatus" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content border-0 shadow">
				<div class="modal-header bg-danger text-white py-3">
					<h6 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>PENGESAHAN: Tarik Balik Kelulusan</h6>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4 text-center">
					<div class="text-danger mb-3">
						<i class="bi bi-shield-slash" style="font-size: 4rem;"></i>
					</div>
					<h5 class="fw-bold text-danger">Tindakan Ini Sangat Kritikal!</h5>
					<p class="text-muted small">Status kelulusan pertubuhan ini akan ditarik balik dan ditukar kepada <b>"Menunggu Keputusan"</b>. Sijil digital yang dijana sebelum ini akan disembunyikan daripada portal awam.</p>
					
					<div class="mt-3 text-start">
						<label class="form-label small fw-bold text-dark">Taip perkataan <span class="text-danger">"SAH"</span> untuk meneruskan:</label>
						<input type="text" class="form-control form-control-sm text-center fw-bold" placeholder="SAH" autocomplete="off">
					</div>
				</div>
				<div class="modal-footer bg-light border-top-0 py-2">
					<button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-sm btn-danger fw-bold px-3">Sahkan Penarikan</button>
				</div>
			</div>
		</div>
	</div> 
	
	<?php
		include ("includes/footer.php");
		
		
		/*
	
		*/
	?>
	<script>
		function showResults() {
			var input = document.getElementById('searchPertubuhan').value;
			var box = document.getElementById('searchResultsBox');
			if(input.length > 0) { box.style.display = 'block'; } 
			else { box.style.display = 'none'; }
		}
		function hideResults() { document.getElementById('searchResultsBox').style.display = 'none'; }
	</script>
	<script>
				let isAllExpanded = false;

				function toggleAllFasal() {
					isAllExpanded = !isAllExpanded;
					const btn = $('#btnToggleAllFasal');
					
					if (isAllExpanded) {
						// Paksa buka semua div collapse yang ada class .fasal-collapse
						$('.fasal-collapse').collapse('show');
						
						// Tukar rupa butang jadi Tutup Semua
						btn.html('<i class="bi bi-eye-slash me-1"></i>Tutup Semua');
						btn.removeClass('btn-outline-primary').addClass('btn-outline-secondary');
						
						// Pusingkan ikon chevron ke atas
						$('.chevron-icon').style.transform = 'rotate(180deg)';
					} else {
						// Paksa tutup semua
						$('.fasal-collapse').collapse('hide');
						
						// Tukar rupa butang balik ke asal
						btn.html('<i class="bi bi-eye me-1"></i>Buka Semua');
						btn.removeClass('btn-outline-secondary').addClass('btn-outline-primary');
						
						// Kembalikan ikon chevron ke bawah
						$('.chevron-icon').style.transform = 'rotate(0deg)';
					}
				}

				// Logika tambahan: Sikit kod untuk pusingkan individu chevron bila diklik satu-satu
				$(document).ready(function(){
					$('.fasal-collapse').on('shown.bs.collapse', function () {
						$(this).closest('tr').prev('tr').find('.chevron-icon').css('transform', 'rotate(180deg)');
					});
					$('.fasal-collapse').on('hidden.bs.collapse', function () {
						$(this).closest('tr').prev('tr').find('.chevron-icon').css('transform', 'rotate(0deg)');
					});
				});
				</script>
				<script>
				$(document).ready(function() {
					$('#selectTarikhAJK').on('change', function() {
						// Ambil nilai tarikh dari dropdown yang dipilih
						var tarikhPilihan = $(this).val();
						
						// Tukar teks pada kedua-dua lencana kad
						$('.label-tarikh-ajk').text(tarikhPilihan);
						
						// Buat efek 'loading/fade' sikit untuk tunjuk data kononnya sedang dikemaskini dari DB
						$('.table-ajk-dinamik tbody').css('opacity', '0.2');
						setTimeout(function() {
							$('.table-ajk-dinamik tbody').animate({ opacity: 1 }, 300);
						}, 400); // simulasi loading 400ms
					});
				});
				</script>
				<script>
					// FUNGSI JUMP & HIGHLIGHT UNTUK TAB FIX ISSUE
					function searchAndHighlight(event) {
						// Kalau user taip tapi belum tekan Enter, kita abaikan (kecuali dia klik butang Cari)
						if (event && event.type === 'keyup' && event.key !== 'Enter') return;

						let inputElement = document.getElementById('searchInputFixIssue');
						let query = inputElement.value.toLowerCase().trim();
						if (!query) return;

						let found = false;
						
						// Cari semua butang yang buka modal (Ini mewakili semua kad tindakan Fix Issue)
						let buttons = document.querySelectorAll('#tab-fixissue button[data-bs-target^="#modal"]');

						// Reset highlight lama (bersihkan skrin kalau dia cari benda lain)
						document.querySelectorAll('.highlight-pulse').forEach(el => el.classList.remove('highlight-pulse'));

						for (let btn of buttons) {
							let card = btn.closest('.card'); // Ambil kotak kad tempat butang tu duduk
							if (!card) continue;

							let cardText = card.innerText.toLowerCase();

							// Semak kalau ada perkataan yang dicari dalam kad tu
							if (cardText.includes(query)) {
								// Berjaya jumpa!
								
								// 1. Lompat ke kad tu secara smooth (ke tengah skrin)
								card.scrollIntoView({ behavior: 'smooth', block: 'center' });
								
								// 2. Tambah class untuk bagi dia menyala/berkelip
								card.classList.add('highlight-pulse');
								found = true;

								// 3. Padam kesan menyala tu lepas 3 saat (supaya tak kekal hodoh)
								setTimeout(() => card.classList.remove('highlight-pulse'), 3000);
								
								break; // Stop loop! Kita cuma nak highlight 1 kad yang paling tepat je
							}
						}

						// Kalau carian langsung tak wujud
						if (!found) {
							inputElement.classList.add('is-invalid');
							let oldPlaceholder = inputElement.placeholder;
							inputElement.value = '';
							inputElement.placeholder = 'Tiada kad padan! Cuba kata kunci lain...';
							
							// Patah balik ke asal lepas 2 saat
							setTimeout(() => {
								inputElement.classList.remove('is-invalid');
								inputElement.placeholder = oldPlaceholder;
							}, 2000);
						}
					}
			</script>
				
 </body>
</html>