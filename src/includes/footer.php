
    

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

$(document).on(
    'click',
    '.calendar-day',
    function(){

        let tarikh =
            $(this).data('tarikh');

        if(!tarikh){
            return;
        }

        $('#modal_title')
            .text('Tambah Rekod');

        $('#modal_id')
            .val('');

        $('#modal_tarikh')
            .val(tarikh);

        $('#modal_nama')
            .val('');

        $('#modal_jenis')
            .val('WFH');

        $('#btnDelete')
            .addClass('d-none');

        $('#modalOOO')
            .modal('show');
    }
);

$(document).on(
    'click',
    '.person-click',
    function(e){

        e.stopPropagation();

        $('#modal_title')
            .text('Edit Rekod');

        $('#modal_id')
            .val($(this).data('id'));

        $('#modal_tarikh')
            .val($(this).data('tarikh'));

        $('#modal_nama')
            .val($(this).data('nama'));

        $('#modal_jenis')
            .val($(this).data('jenis'));

        $('#btnDelete')
            .removeClass('d-none');

        $('#modalOOO')
            .modal('show');
    }
);

$('#btnSave').click(function(){

    $.ajax({

        url:'ajax/save_ooo.php',

        method:'POST',

        data:{

            id:
                $('#modal_id').val(),

            nama:
                $('#modal_nama').val(),

            tarikh:
                $('#modal_tarikh').val(),

            jenis:
                $('#modal_jenis').val()
        },

        success:function(){

            location.reload();
        }
    });
});

$('#btnDelete').click(function(){

    Swal.fire({

        title:'Padam rekod?',

        icon:'warning',

        showCancelButton:true

    }).then((result)=>{

        if(!result.isConfirmed){
            return;
        }

        $.ajax({

            url:'ajax/delete_ooo.php',

            method:'POST',

            data:{

                id:
                $('#modal_id').val()
            },

            success:function(){

                location.reload();
            }
        });

    });

});

document
.getElementById('btnToday')
.addEventListener(
    'click',
    function(){

        const todayCard =
            document.getElementById(
                'today-card'
            );

        if(todayCard){

            todayCard.scrollIntoView({

                behavior:'smooth',

                block:'center'

            });

            todayCard.classList.add(
                'today-focus'
            );

            setTimeout(function(){

                todayCard.classList.remove(
                    'today-focus'
                );

            },2000);
        }
    }
);
</script>

