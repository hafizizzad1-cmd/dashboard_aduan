<?php
require_once("../controller/meja_bantuan_controller.php");
$page_active = 'meja_bantuan';
include("includes/header.php");
?>


    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <div class="page-body-wrapper">
			<div class="page-body">
			  <!-- Container-fluid starts-->
				<div class="container-fluid default-dash">
					
					<div class='row align-items-stretch g-3 mt-2'>
						<div class='col-md-5 theme-green' id="widget-wrapper"> 
							<div class="card card-padat h-100 p-4 d-flex flex-column text-center justify-content-center">
    
								<div class="mb-3">
									<span class="badge rounded-pill perc-color perc-bg px-4 py-2 text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">
										Peratusan Meja Bantuan
									</span>
								</div>
								
								<div class="fw-bolder perc-color mb-2" style="font-size: 5rem; line-height: 1; letter-spacing: -2px;">
									<span id="perc-val">0.00</span><span style="font-size: 2.5rem; vertical-align: super;">%</span>
								</div>
								
								<div class="text-muted fw-medium mb-4" style="font-size: 0.9rem;">
									Tahap Penyelesaian: <span class="perc-color fw-bold text-uppercase" id="tahap-teks">Memuaskan</span>
								</div>
								
								<div class="progress mb-4 mx-auto" style="height: 8px; width: 85%; border-radius: 10px;">
									<div class="progress-bar perc-bg perc-color" id="perc-bar" style="width: 0%; opacity: 0.9;"></div>
								</div>
								
								<div class="row g-2 mb-2 w-100 mx-auto text-center">
									<div class="col-6">
										<div class="border border-2 perc-border px-2 py-3 h-100 d-flex flex-column justify-content-center shadow-sm bg-white" style="border-radius: 12px !important;">
											<div class="perc-color fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Selesai Sistem</div>
											<div class="perc-color fw-bolder" style="font-size: 1.8rem; line-height: 1;" id="db-breakdown-answered">
												<?php echo number_format($db_total_answered); ?>
											</div>
										</div>
									</div>
									
									<div class="col-6">
										<div class="border border-2 border-warning px-2 py-3 h-100 d-flex flex-column justify-content-center shadow-sm bg-white" style="border-radius: 12px !important;">
											<div class="text-warning fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Dalam Tindakan Sistem</div>
											<div class="text-warning fw-bolder" style="font-size: 1.8rem; line-height: 1;" id="db-breakdown-open">
												<?php echo number_format($db_total_semua - $db_total_answered); ?>
											</div>
										</div>
									</div>
									
									<div class="col-6">
										<div class="border border-2 border-primary px-2 py-3 h-100 d-flex flex-column justify-content-center shadow-sm bg-white" style="border-radius: 12px !important;">
											<div class="text-primary fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Selesai Emel</div>
											<div class="text-primary fw-bolder" style="font-size: 1.8rem; line-height: 1;" id="email-breakdown-answered">0</div>
										</div>
									</div>
									
									<div class="col-6">
										<div class="border border-2 border-warning px-2 py-3 h-100 d-flex flex-column justify-content-center shadow-sm bg-white" style="border-radius: 12px !important;">
											<div class="text-warning fw-bold mb-1" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Dalam Tindakan Emel</div>
											<div class="text-warning fw-bolder" style="font-size: 1.8rem; line-height: 1;" id="email-breakdown-open">0</div>
										</div>
									</div>
								</div>
								
								<div class="mt-4 text-muted fw-bold d-flex align-items-center justify-content-center gap-1" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">
									<i class="bi bi-clock-history"></i> 
									<h4>Data setakat: <?php date_default_timezone_set('Asia/Kuala_Lumpur'); echo date('d M Y, h:i a'); ?></h4>
								</div>
								
							</div>
						</div>
						<div class="col-md-3">
							<div class="card card-padat h-100 p-4 d-flex flex-column text-center justify-content-center">
								<h5 class="fw-bolder text-dark mb-3">Rekod Emel</h5>
								
								<div class="bg-light rounded p-3 mb-4 text-muted" style="font-size: 0.8rem;">
									Masukkan aduan emel manual. Jumlah akan dikira automatik.
								</div>
								
								<form id="form-kemaskini-emel" class="d-flex flex-column mb-0 h-100">
									<div class="mb-3">
										<label class="fw-bold text-warning text-uppercase mb-2" style="font-size: 0.75rem;">Terbuka (Belum Selesai)</label>
										<input type="number" id="input-email-open" name="emel_open" class="form-control form-control-lg input-padat" min="0" placeholder="0" value="<?php echo $data_emel_open;?>">
									</div>
									
									<div class="mb-3">
										<label class="fw-bold text-success text-uppercase mb-2" style="font-size: 0.75rem;">Ditutup (Selesai)</label>
										<input type="number" id="input-email-answered" name="emel_close" class="form-control form-control-lg input-padat" min="0" placeholder="0" value="<?php echo $data_emel_close;?>">
									</div>

									<div class="mb-4">
										<label class="fw-bold text-muted text-uppercase mb-2" style="font-size: 0.75rem;">Jumlah (Keseluruhan)</label>
										<input type="number" id="input-email-total" class="form-control form-control-lg input-padat bg-secondary bg-opacity-10 text-muted" readonly value="0" style="cursor: not-allowed;">
									</div>
									
									<button type="submit" id="btn-update-emel" class="btn btn-primary py-3 fw-bold mt-auto w-100" style="border-radius: 8px; font-size: 1rem; transition: all 0.3s;">
										Kemaskini Statistik
									</button>
								</form>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="card card-padat h-100 p-4 d-flex flex-column justify-content-between">
    
								<div class="text-center mb-2">
									<div class="d-inline-flex align-items-center bg-light border rounded-pill px-4 py-2 shadow-sm">
										<i class="bi bi-inbox-fill text-secondary me-2 fs-6"></i>
										<span class="fw-bold text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Status Tiket Negeri</span>
									</div>
								</div>
								
								<div class="d-flex flex-column justify-content-center flex-grow-1 gap-3 py-4">
									
									<div class="bg-light rounded-4 p-4 border-start border-5 border-danger shadow-sm d-flex align-items-center justify-content-between" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateX(8px)'" onmouseout="this.style.transform='translateX(0)'">
										<div>
											<div class="fw-bolder text-dark text-uppercase mb-1" style="font-size: 0.95rem;">Belum Dialir (Baru)</div>
											<div class="text-muted fw-medium" style="font-size: 0.75rem;">Tindakan alir diperlukan</div>
										</div>
										<div class="fw-bolder text-danger" style="font-size: 3rem; line-height: 1;"> 
											<?php echo number_format($total_baru); ?>
										</div>
									</div>

									<div class="bg-light rounded-4 p-4 border-start border-5 border-info shadow-sm d-flex align-items-center justify-content-between" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateX(8px)'" onmouseout="this.style.transform='translateX(0)'">
										<div>
											<div class="fw-bolder text-dark text-uppercase mb-1" style="font-size: 0.95rem;">Telah Dijawab</div>
											<div class="text-muted fw-medium" style="font-size: 0.75rem;">Menunggu tindakan penutupan</div>
										</div>
										<div class="fw-bolder text-info" style="font-size: 3rem; line-height: 1;"> 
											<?php echo number_format($total_dijawab); ?>
										</div>
									</div>
									
								</div>
								
								<div class="text-center border-top pt-4">
									<span class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Jumlah Keseluruhan Belum Alir</span>
									<div class="fw-bolder text-dark mt-2" style="font-size: 2rem; line-height: 1;">
										<?php echo number_format($grand_total_semua); ?> <span class="fs-6 text-muted fw-normal">Tiket</span>
									</div>
								</div>
								
							</div>
						</div>

						
						
					</div>
					
					<div class="row mt-5">
						
						<div class='col-md-8'>
							<div class="card shadow-sm border-0 rounded-4 h-100">
								<div class="card-body">
									<div class="table-responsive">
										<table class="display table table-hover align-middle" id="tbl_senarai_meja_bantuan" style="width:100%;">
											<thead class="table-light text-muted" style="font-size: 0.85rem;">
												<tr>
													<th>ID</th>
													<th>Type</th>
													<th>Title</th>
													<th>Details</th>
													<th>Name</th>
													<th>Email</th>
													<th>Assign Date</th>
													<th>Severity</th>
													<th>Status Technical</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card card-padat h-100 p-3 d-flex flex-column">
								
								<div class="text-center mb-3 mt-2" style="flex-shrink: 0;">
									<h5 class="fw-bolder text-dark mb-0">Aduan Belum Dialir</h5>
								</div>
								
								<div class="border rounded-3 flex-grow-1 mx-auto" style="width: 80%; min-height: 0; position: relative; overflow: hidden;">
									
									<div class="table-responsive h-100" style="overflow-y: auto; overflow-x: hidden;">
										<table class="table table-negeri table-borderless align-middle mb-0">
											<thead>
												<tr>
													<th class="text-start ps-3">Negeri</th>
													<th class="text-center">Status Baru + Dalam Tindakan</th>
													<th class="text-center">Dijawab <br><span style="font-size:0.6rem;">(Belum Selesai)</span></th>
													<th class="text-center pe-3">Jumlah</th>
												</tr>
											</thead>
											<tbody>
												<?php
												if (!empty($data_statistik_negeri)) {
													foreach ($data_statistik_negeri as $stat) {
														$negeri = $stat['negeri'];
														$negeri_display = str_replace('WILAYAH PERSEKUTUAN ', 'W.P. ', $negeri);
														
														$baru = (int)$stat['belum_teknikal'];
														$dijawab = (int)$stat['answered'];
														$total = $stat['total'];
														
														$disp_baru = ($baru > 0) ? $baru : '<span class="text-muted opacity-25">-</span>';
														$disp_dijawab = ($dijawab > 0) ? $dijawab : '<span class="text-muted opacity-25">-</span>';
														$disp_total = ($total > 0) ? $total : '<span class="text-muted opacity-25">-</span>';
												?>
												<tr>
													<td class="text-start ps-3 state-name-cell"><?php echo $negeri_display; ?></td>
													<td class="text-center"><?php echo $disp_baru; ?></td>
													<td class="text-center"><?php echo $disp_dijawab; ?></td>
													<td class="text-center pe-3 fw-bolder text-primary bg-light bg-opacity-50"><?php echo $disp_total; ?></td>
												</tr>
												<?php 
													}
												} else {
													echo "<tr><td colspan='4' class='text-center py-4'>Tiada data statistik.</td></tr>";
												}
												?>
											</tbody>
											<tfoot style="position: sticky; bottom: 0; z-index: 2; background-color: #f1f5f9; box-shadow: 0 -2px 5px rgba(0,0,0,0.05);">
												<tr>
													<td class="text-start ps-3 py-3 fw-bold text-muted" style="font-size: 0.75rem;">JUMLAH</td>
													<td class="text-center fw-bolder text-dark" style="font-size: 0.9rem;"><?php echo $total_baru; ?></td>
													<td class="text-center fw-bolder text-dark" style="font-size: 0.9rem;"><?php echo $total_dijawab; ?></td>
													<td class="text-center pe-3 fw-bolder text-primary" style="font-size: 1rem;"><?php echo $grand_total_semua; ?></td>
												</tr>
											</tfoot>
										</table>
									</div>

								</div>
								
							</div>
						</div>
						
					</div>
					
					
					
				</div>
				  
		   
			</div>
        </div>
    </div>

    <?php
	include("includes/footer.php");
	?>
    
    <!-- login js-->
    <!-- Plugin used-->
	
	
	<!--custom-->
	<script>
		$(document).ready(function() {
			
			// ==========================================================
			// 1. FUNGSI KIRA STATISTIK (REAL-TIME) & TUKAR TEMA WIDGET
			// ==========================================================
			
			// Ambil data asal dari pangkalan data (PHP ke JavaScript)
			const dbTotalSemua = <?php echo $db_total_semua; ?>;
			const dbTotalAnswered = <?php echo $db_total_answered; ?>;
			
			function kiraStatistik() {
				// Ambil nilai key-in dari input (Jika kosong, anggap 0)
				let emailOpen = parseInt($('#input-email-open').val()) || 0;
				let emailAnswered = parseInt($('#input-email-answered').val()) || 0;
				
				// Auto-calculate Jumlah Emel
				let emailTotal = emailOpen + emailAnswered;
				$('#input-email-total').val(emailTotal);
				
				// Cantumkan DB + Emel untuk Widget Peratusan
				let grandTotal = dbTotalSemua + emailTotal;
				let grandAnswered = dbTotalAnswered + emailAnswered;
				let grandOpen = grandTotal - grandAnswered;
				
				// Kira Peratusan
				let percent = (grandTotal > 0) ? (grandAnswered / grandTotal) * 100 : 0;
				let percentFormatted = percent.toFixed(2);
				
				// Kemaskini Nombor Dalam Kad Widget
				$('#perc-val').text(percentFormatted);
				$('#perc-bar').css('width', percentFormatted + '%');
				
				// Kemaskini pecahan emel 
				$('#email-breakdown-answered').text(emailAnswered.toLocaleString());
				$('#email-breakdown-open').text(emailOpen.toLocaleString());
				
				// Tukar Tema Dinamik berdasarkan peratusan
				let widget = $('#widget-wrapper');
				let tahapTeks = $('#tahap-teks');
				
				widget.removeClass('theme-purple theme-green theme-yellow');
				
				if (percent >= 95) {
					widget.addClass('theme-purple');
					tahapTeks.text('Cemerlang');
				} else if (percent >= 90) {
					widget.addClass('theme-green');
					tahapTeks.text('Memuaskan');
				} else {
					widget.addClass('theme-yellow');
					tahapTeks.text('Perlu Perhatian');
				}
			}
			
			// Panggil fungsi secara Real-Time bila user menaip di Terbuka atau Ditutup
			$('#input-email-open, #input-email-answered').on('input', function() {
				kiraStatistik();
			});
			
			// Semasa muka surat mula-mula dibuka, kira siap-siap
			kiraStatistik();
			
			// ==========================================================
			// 2. HANTAR DATA EMEL GUNA AJAX (BORANG KEMASKINI)
			// ==========================================================
			
			$('#form-kemaskini-emel').on('submit', function(e) {
				e.preventDefault(); // Halang page dari refresh bila tekan submit
				
				let btn = $('#btn-update-emel');
				let originalText = btn.html(); // Simpan teks asal butang
				
				// Ambil nilai dari input
				let valOpen = $('#input-email-open').val();
				let valClose = $('#input-email-answered').val();
				
				// Tukar butang jadi mod 'Loading' supaya user tak spam click
				btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');

				// Hantar data guna AJAX
				$.ajax({
					url: '../controller/post/update_emel_meja_bantuan.php',
					type: 'POST',
					data: {
						emel_open: valOpen,
						emel_close: valClose
					},
					success: function(response) {
						// Bila AJAX berjaya sampai ke PHP & patah balik
						btn.removeClass('btn-primary btn-danger').addClass('btn-success')
						   .html('<i class="bi bi-check-circle-fill me-2"></i>Berjaya Disimpan!');
						
						// Kembalikan butang ke asal selepas 2 saat
						setTimeout(function() {
							btn.removeClass('btn-success').addClass('btn-primary').html(originalText).prop('disabled', false);
						}, 2000);
					},
					error: function(xhr, status, error) {
						// Kalau ada masalah (path salah, server error)
						btn.removeClass('btn-primary btn-success').addClass('btn-danger')
						   .html('<i class="bi bi-x-circle-fill me-2"></i>Gagal Menyimpan');
						   
						console.error("Ralat AJAX: ", error);
						
						// Kembalikan butang ke asal selepas 2 saat
						setTimeout(function() {
							btn.removeClass('btn-danger').addClass('btn-primary').html(originalText).prop('disabled', false);
						}, 2000);
					}
				});
			});

			// ==========================================================
			// 3. JADUAL DATATABLES (AJAX LOAD)
			// ==========================================================
			
			$('#tbl_senarai_meja_bantuan').DataTable({
				"processing": true, // Muncul tulisan "Processing..." masa tunggu data
				"deferRender": true, // Render dengan sangat laju
				"ajax": "../controller/senarai_meja_bantuan.php", // Panggil fail PHP
				"columns": [
					{ "data": "id" },
					{ "data": "type" },
					{ "data": "title" },
					{ "data": "details" },
					{ "data": "name" },
					{ "data": "email" },
					{ "data": "assign_date" },
					{ "data": "severity" },
					{ "data": "status_technical" }
				],
				"order": [[0, "desc"]] // Susun ID dari paling baru kalau perlu
			});

		});
		</script>
	
</html>