
    

    </body>
	<script src="../assets/js/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>

    <script src="../assets/js/icons/feather-icon/feather.min.js"></script>
    <script src="../assets/js/icons/feather-icon/feather-icon.js"></script>
    <script src="../assets/js/scrollbar/simplebar.js"></script>
    <script src="../assets/js/scrollbar/custom.js"></script>
    <script src="../assets/js/clipboard/clipboard.min.js"></script>
    <script src="../assets/js/prism/prism.min.js"></script>

    <script src="../assets/js/config.js"></script>
    <script src="../assets/js/sidebar-menu.js"></script>

    <script src="../assets/js/typeahead/handlebars.js"></script>
    <script src="../assets/js/typeahead/typeahead.bundle.js"></script>
    <script src="../assets/js/typeahead-search/typeahead-custom.js"></script>

    <script src="../assets/js/datepicker/date-picker/datepicker.js"></script>
    <script src="../assets/js/datepicker/date-picker/datepicker.en.js"></script>
    <script src="../assets/js/datepicker/date-picker/datepicker.custom.js"></script>
    <script src="../assets/js/counter/jquery.waypoints.min.js"></script>
    <script src="../assets/js/counter/jquery.counterup.min.js"></script>
    <script src="../assets/js/counter/counter-custom.js"></script>

    <script src="../assets/js/notify/bootstrap-notify.min.js"></script>
    <script src="../assets/js/notify/index.js"></script>
    <script src="../assets/js/slick-slider/slick.min.js"></script>
    <script src="../assets/js/slick-slider/slick-theme.js"></script>

    <script src="../assets/js/chart/knob/knob.min.js"></script>
    <script src="../assets/js/chart/knob/knob-chart.js"></script>
    <script src="../assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/js/datatable/datatables/datatable.custom.js"></script>
    
    <script src="../assets/js/custom-card/custom-card.js"></script>
    <script src="../assets/js/height-equal.js"></script>
    <script src="../assets/js/tooltip-init.js"></script>
    <script src="../assets/js/script.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
 
    // ==========================================================
    // 3. ALERT "AKAN DATANG" (SWEETALERT2)
    // ==========================================================
    $('.nav-akan-datang').on('click', function(e) {
        e.preventDefault(); // Elak jump page
        Swal.fire({
            title: 'Akan Datang!',
            text: 'Modul ini sedang dibangunkan. Sebarang cadangan sila whatsapp',
            icon: 'info',
            iconColor: '#2563eb',
            confirmButtonColor: '#1e40af',
            confirmButtonText: 'Sabar eh!',
            background: '#ffffff',
            customClass: {
                popup: 'rounded-4 shadow-lg',
                confirmButton: 'px-4 py-2 fw-bold'
            }
        });
    });


});
</script>