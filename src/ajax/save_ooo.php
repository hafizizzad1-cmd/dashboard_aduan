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