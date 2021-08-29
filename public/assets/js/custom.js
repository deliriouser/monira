


    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })


    $(document).ready(function(){



        $('body').on("click", ".static", function(e){
            e.preventDefault();
        });

        $('body').on("submit", "#myform", function(e){
                e.preventDefault();
                var form = $("#myform").closest("form");
                var formData = new FormData(form[0]);
                var url = form.attr('action');
                $.blockUI({
                message: 'Updating Data',
                fadeIn: 800,
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 1201,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });

                $.ajax({
                    processing: true,
                    serverSide: true,
                    processData: false,
                    contentType: false,
                    type: "POST",
                    url: url,
                    data: formData, // serializes the form's elements.
                    success: function(response)
                    {
                        $(document).ready(function() {
                            feather.replace();
                        });
                        $.unblockUI();
                        Swal({title: "Berhasil", text: "Data Berhasil di Simpan", type:"success", timer: 100000});
                        $("#card").load("#card");
                        $('.modal').modal('hide');
                        $("#page-all").load(" #page-all");
                        $(".loadData").load(" .loadData");
                        $(".loadSK").load(" .loadSK");
                        $(".loadNotifSK").load(" .loadNotifSK");
                        $("form").trigger("reset");
                        $(".select").select2();
                        $("#loadPejabat").load(" #loadPejabat");
                        $(".loadrpd").load(" .loadrpd");
                    },
                    error: function () {
                        Swal({title: "Warning", text: "Data Gagal Di Simpan", type:"error", timer: 5000});
                        $.unblockUI();
                        $(".select").select2();

                    }
                    });
            });
        });

        $(".select").select2();

        // MODAL UNTUK SEMUA




    // MODAL UNTUK SEMUA
    $(document).ready(function(){


        $('body').on('click','.open-modal-monitoring',function(){
            // alert('tes');
            $.blockUI({
                message: '<svg> ... </svg>',
                fadeIn: 800,
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 1201,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });
            var action     = $(this).attr("action");
            var id         = $(this).attr("id");
            var pagu       = $(this).attr("pagu");
            var dana       = $(this).attr("dana");
            var kegiatan   = $(this).attr("kegiatan");
            var output     = $(this).attr("output");
            var akun       = $(this).attr("akun");
            var sisa       = $(this).attr("sisa");
            $.ajax({
                url    : '/satker/monitoring/modal/'+action+'/'+id,
                method : "GET",
                data   : {action:action,id:id,pagu:pagu,dana:dana,kegiatan:kegiatan,output:output,akun:akun},
                cache  : false,
                success: function(data){
                    $.unblockUI();
                    $('.modal-content-monitoring').html(data);
                    $('.modal-content-monitoring #id').val(id);
                    $('.modal-content-monitoring #action').val(action);
                    $('.modal-content-monitoring #pagu').val(pagu);
                    $('.modal-content-monitoring #akun').val(akun);
                    $('.modal-content-monitoring #sisa').val(sisa);
                    $('.modal-content-monitoring .pagu').val(addCommas(pagu));
                    $('.modal-content-monitoring .kegiatan').html(kegiatan);
                    $('.modal-content-monitoring .output').html(output);
                    $('.modal-content-monitoring .akun').html(akun);
                    $('.modal-content-monitoring .dana').html(dana);
                    $('.modal-content-monitoring .pagu').html(addCommas(pagu));
                    $('#open-modal-monitoring').modal('show');
                    feather.replace();
                    $('.select').select2({
                        dropdownParent: $('#open-modal-monitoring'),
                        tags: true
                    });

                    $('.datepicker-here').datepicker();

                }
            });
        });


        $('body').on('click','.open-modal',function(){
            $.blockUI({
                message: '<svg> ... </svg>',
                fadeIn: 800,
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 1201,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });
            var action     = $(this).attr("action");
            var id         = $(this).attr("id");
            var pagu       = $(this).attr("pagu");
            var dana       = $(this).attr("dana");
            var kegiatan   = $(this).attr("kegiatan");
            var output     = $(this).attr("output");
            var akun       = $(this).attr("akun");
            var sisa       = $(this).attr("sisa");
            // alert(action);
            $.ajax({
                url    : '/satker/prognosa/modal/'+action+'/'+id,
                method : "GET",
                data   : {action:action,id:id,pagu:pagu,dana:dana,kegiatan:kegiatan,output:output,akun:akun,sisa:sisa},
                cache  : false,
                success: function(data){
                    $.unblockUI();
                    $('.modal-content-static').html(data);
                    $('.modal-content-static #id').val(id);
                    $('.modal-content-static #action').val(action);
                    $('.modal-content-static #pagu').val(pagu);
                    $('.modal-content-static .pagu').val(addCommas(pagu));
                    $('.modal-content-static .kegiatan').html(kegiatan);
                    $('.modal-content-static .output').html(output);
                    $('.modal-content-static .akun').html(akun);
                    $('.modal-content-static .dana').html(dana);
                    $('.modal-content-static .pagu').html(addCommas(pagu));
                    $('.modal-content-static #sisa').val(addCommas(sisa));
                    $('.enabled_12').prop('hidden', true);
                    $('.enabled_11').prop('hidden', true);
                    $('.enabled_10').prop('hidden', true);
                    $('.enabled_9').prop('hidden', true);
                    $('.enabled_8').prop('hidden', true);
                    $('.enabled_7').prop('hidden', true);
                    $('.enabled_6').prop('hidden', true);
                    $('.enabled_5').prop('hidden', true);
                    $('.enabled_4').prop('hidden', true);
                    $('.enabled_3').prop('hidden', true);
                    $('.enabled_2').prop('hidden', true);
                    $('.enabled_1').prop('hidden', true);
                    $('#open-modal').modal('show');
                    feather.replace();

                }
            });
        });


        $('body').on('click','.autofill',function(){
            var pagu   = $("#pagu").val();
            var value  = pagu/12;
            var number = Math.trunc(value);
            $('.prognosaValue').val(addCommas(number));

            var sum  = 0;
            $('.prognosaValue').each(function() {
            sum += Number($(this).val().replace(/[^0-9]+/g,""));
            });
            var value = addCommas(sum);
            $("#total").val(value);

            var sisa = (pagu-sum);
            $(".sisa").val(addCommas(sisa));
        });

        $('body').on('click','.autofill-realisasi',function(){
            var JAN   = $("#JAN").val();
            var FEB   = $("#FEB").val();
            var MAR   = $("#MAR").val();
            var APR   = $("#APR").val();
            var MEI   = $("#MEI").val();
            var JUN   = $("#JUN").val();
            var JUL   = $("#JUL").val();
            var AGS   = $("#AGS").val();
            var SEP   = $("#SEP").val();
            var OKT   = $("#OKT").val();
            var NOV   = $("#NOV").val();
            var DES   = $("#DES").val();

            $(".input_1").val(addCommas(JAN));
            $(".input_2").val(addCommas(FEB));
            $(".input_3").val(addCommas(MAR));
            $(".input_4").val(addCommas(APR));
            $(".input_5").val(addCommas(MEI));
            $(".input_6").val(addCommas(JUN));
            $(".input_7").val(addCommas(JUL));
            $(".input_8").val(addCommas(AGS));
            $(".input_9").val(addCommas(SEP));
            $(".input_10").val(addCommas(OKT));
            $(".input_11").val(addCommas(NOV));
            $(".input_12").val(addCommas(DES));


        });

        $('body').on('click','.resetfill',function(){
            var value="";
            // alert(value);
            $('.prognosaValue').val(value);
            $('.number').val(value);
        });

        $('body').on('blur','.prognosaValue',function(){
            var pagu = $("#pagu").val();
            var sum  = 0;
            $('.prognosaValue').each(function() {
            sum += Number($(this).val().replace(/[^0-9]+/g,""));
            });
            var value = addCommas(sum);
            $("#total").val(value);
            var sisa = (pagu-sum);
            $(".sisa").val(addCommas(sisa));
            // alert(sisa)
            if(sisa<0) {
                Swal({title: "Warning", text: "Total Prognosa Melebihi Pagu", type:"error", timer: 5000});
                $('.submitPrognosa').prop('disabled', true);
                $('.justifikasi').prop('disabled', true);
                $('.newSection').prop('hidden', true);
                $('.wajib').prop('required', false);

            } else if(sisa=0 || sisa < 100000) {
                $('.justifikasi').prop('disabled', true);
                $('.newSection').prop('hidden', true);
                $('.submitPrognosa').prop('disabled', false);
                $('.wajib').prop('required', false);
            } else {
                $('.submitPrognosa').prop('disabled', false);
                $('.newSection').prop('hidden', false);
                $('.justifikasi').prop('disabled', false);
                $('.wajib').prop('required', true);
                $(".newSection").html("<div style='font-size:14px;' required class='form-group mb-3 mt-3'><label class='col-form-label'>Justifikasi Prognosa Tidak 100%</label><textarea required class='form-control justifikasi' name='justifikasi' id='justifikasi' rows='3'></textarea></div>");
            }
        });


        $('body').on('click','.validasi',function(){
            var pagu = $("#pagu").val();
            var sum  = 0;
            $('.prognosaValue').each(function() {
            sum += Number($(this).val().replace(/[^0-9]+/g,""));
            });
            var value = addCommas(sum);
            $("#total").val(value);
            var sisa = (pagu-sum);
            $(".sisa").val(addCommas(sisa));
            // alert(sisa)
            if(sisa<0) {
                Swal({title: "Warning", text: "Total Prognosa Melebihi Pagu", type:"error", timer: 5000});
                $('.submitPrognosa').prop('disabled', true);
                $('.justifikasi').prop('disabled', true);
                $('.newSection').prop('hidden', true);
                $('.wajib').prop('required', false);

            } else if(sisa=0 || sisa < 100000) {
                $('.justifikasi').prop('disabled', true);
                $('.newSection').prop('hidden', true);
                $('.submitPrognosa').prop('disabled', false);
                $('.wajib').prop('required', false);
            } else {
                $('.submitPrognosa').prop('disabled', false);
                $('.newSection').prop('hidden', false);
                $('.justifikasi').prop('disabled', false);
                $('.wajib').prop('required', true);
                $(".newSection").html("<div style='font-size:14px;' required class='form-group mb-3 mt-3'><label class='col-form-label'>Justifikasi Prognosa Tidak 100%</label><textarea required class='form-control justifikasi' name='justifikasi' id='justifikasi' rows='3'></textarea></div>");
            }
        });

       //BULAN_1
    $('body').on('click','.week_list_1',function(){
        $('.section_week_1').prop('hidden', false);
        $(".section_week_1").html("<div style='font-size:12px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_1_minggu[]' placeholder='Prognosa Minggu 1' style='text-align:right; font-size:14px;' type='text' class='form-control prognosaValue number week_1'></div><div required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_1_minggu[]' placeholder='Prognosa Minggu 2' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_1'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_1_minggu[]' placeholder='Prognosa Minggu 3' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_1'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_1_minggu[]' placeholder='Prognosa Minggu 4' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_1'></div>");
        $('.input_1').prop('hidden', true);
        $('.start_1 a i').toggleClass("fa-plus-circle").toggleClass("fa-minus-circle out_1");
        $('.input_1').val("");
        $('.enabled_1').prop('hidden', true);
        $('.enabled_1').prop( 'disabled', true);

    });
    $('body').on('click','.out_1',function(){
        $('.input_1').prop('hidden', false);
        $('.section_week_1').prop('hidden', true);
        $('.week_1').prop( 'disabled', true);
        $('.week_1').val("");
        $('.week_1').prop('hidden', true);
        $('.disabled_1').prop('hidden', true);
        $('.disabled_1').prop( 'disabled', false);
        $('.enabled_1').prop('hidden', false);
        $('.enabled_1').prop( 'disabled', false);
        $('.enabled_1').val("");
        $(".section_week_1").html("");
    });

       //BULAN_2
    $('body').on('click','.week_list_2',function(){
        $('.section_week_2').prop('hidden', false);
        $(".section_week_2").html("<div style='font-size:12px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_2_minggu[]' placeholder='Prognosa Minggu 1' style='text-align:right; font-size:14px;' type='text' class='form-control prognosaValue number week_2'></div><div required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_2_minggu[]' placeholder='Prognosa Minggu 2' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_2'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_2_minggu[]' placeholder='Prognosa Minggu 3' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_2'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_2_minggu[]' placeholder='Prognosa Minggu 4' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_2'></div>");
        $('.input_2').prop('hidden', true);
        $('.start_2 a i').toggleClass("fa-plus-circle").toggleClass("fa-minus-circle out_2");
        $('.input_2').val("");
        $('.enabled_2').prop('hidden', true);
        $('.enabled_2').prop('disabled', true);

    });
    $('body').on('click','.out_2',function(){
        $('.input_2').prop('hidden', false);
        $('.section_week_2').prop('hidden', true);
        $('.week_2').prop('disabled', true);
        $('.disabled_2').prop('hidden', true);
        $('.disabled_2').prop('disabled', false);
        $('.enabled_2').prop('hidden', false);
        $('.enabled_2').prop('disabled', false);
        $('.enabled_2').val("");
        $(".section_week_2").html("");

    });

    //BULAN_3
    $('body').on('click','.week_list_3',function(){
        $('.section_week_3').prop('hidden', false);
        $(".section_week_3").html("<div style='font-size:12px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_3_minggu[]' placeholder='Prognosa Minggu 1' style='text-align:right; font-size:14px;' type='text' class='form-control prognosaValue number week_3'></div><div required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_3_minggu[]' placeholder='Prognosa Minggu 2' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_3'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_3_minggu[]' placeholder='Prognosa Minggu 3' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_3'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_3_minggu[]' placeholder='Prognosa Minggu 4' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_3'></div>");
        $('.input_3').prop('hidden', true);
        $('.start_3 a i').toggleClass("fa-plus-circle").toggleClass("fa-minus-circle out_3");
        $('.input_3').val("");
        $('.enabled_3').prop('hidden', true);
        $('.enabled_3').prop('disabled', true);

    });
    $('body').on('click','.out_3',function(){
        $('.input_3').prop('hidden', false);
        $('.section_week_3').prop('hidden', true);
        $('.week_3').prop('disabled', true);
        $('.disabled_3').prop('hidden', true);
        $('.disabled_3').prop('disabled', false);
        $('.enabled_3').prop('hidden', false);
        $('.enabled_3').prop('disabled', false);
        $('.enabled_3').val("");
        $(".section_week_3").html("");

    });
    //BULAN_4
    $('body').on('click','.week_list_4',function(){
        $('.section_week_4').prop('hidden', false);
        $(".section_week_4").html("<div style='font-size:12px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_4_minggu[]' placeholder='Prognosa Minggu 1' style='text-align:right; font-size:14px;' type='text' class='form-control prognosaValue number week_4'></div><div required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_4_minggu[]' placeholder='Prognosa Minggu 2' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_4'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_4_minggu[]' placeholder='Prognosa Minggu 3' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_4'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_4_minggu[]' placeholder='Prognosa Minggu 4' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_4'></div>");
        $('.input_4').prop('hidden', true);
        $('.start_4 a i').toggleClass("fa-plus-circle").toggleClass("fa-minus-circle out_4");
        $('.input_4').val("");
        $('.enabled_4').prop('hidden', true);
        $('.enabled_4').prop('disabled', true);

    });
    $('body').on('click','.out_4',function(){
        $('.input_4').prop('hidden', false);
        $('.section_week_4').prop('hidden', true);
        $('.week_4').prop('disabled', true);
        $('.disabled_4').prop('hidden', true);
        $('.disabled_4').prop('disabled', false);
        $('.enabled_4').prop('hidden', false);
        $('.enabled_4').prop('disabled', false);
        $('.enabled_4').val("");
        $(".section_week_4").html("");

    });

    //BULAN_5
    $('body').on('click','.week_list_5',function(){
        $('.section_week_5').prop('hidden', false);
        $(".section_week_5").html("<div style='font-size:12px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_5_minggu[]' placeholder='Prognosa Minggu 1' style='text-align:right; font-size:14px;' type='text' class='form-control prognosaValue number week_5'></div><div required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_5_minggu[]' placeholder='Prognosa Minggu 2' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_5'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_5_minggu[]' placeholder='Prognosa Minggu 3' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_5'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_5_minggu[]' placeholder='Prognosa Minggu 4' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_5'></div>");
        $('.input_5').prop('hidden', true);
        $('.start_5 a i').toggleClass("fa-plus-circle").toggleClass("fa-minus-circle out_5");
        $('.input_5').val("");
        $('.enabled_5').prop('hidden', true);
        $('.enabled_5').prop('disabled', true);

    });
    $('body').on('click','.out_5',function(){
        $('.input_5').prop('hidden', false);
        $('.section_week_5').prop('hidden', true);
        $('.week_5').prop('disabled', true);
        $('.disabled_5').prop('hidden', true);
        $('.disabled_5').prop('disabled', false);
        $('.enabled_5').prop('hidden', false);
        $('.enabled_5').prop('disabled', false);
        $('.enabled_5').val("");
        $(".section_week_5").html("");

    });

    //BULAN_6
    $('body').on('click','.week_list_6',function(){
        $('.section_week_6').prop('hidden', false);
        $(".section_week_6").html("<div style='font-size:12px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_6_minggu[]' placeholder='Prognosa Minggu 1' style='text-align:right; font-size:14px;' type='text' class='form-control prognosaValue number week_6'></div><div required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_6_minggu[]' placeholder='Prognosa Minggu 2' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_6'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_6_minggu[]' placeholder='Prognosa Minggu 3' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_6'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_6_minggu[]' placeholder='Prognosa Minggu 4' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_6'></div>");
        $('.input_6').prop('hidden', true);
        $('.start_6 a i').toggleClass("fa-plus-circle").toggleClass("fa-minus-circle out_6");
        $('.input_6').val("");
        $('.enabled_6').prop('hidden', true);
        $('.enabled_6').prop('disabled', true);

    });
    $('body').on('click','.out_6',function(){
        $('.input_6').prop('hidden', false);
        $('.section_week_6').prop('hidden', true);
        $('.week_6').prop('disabled', true);
        $('.disabled_6').prop('hidden', true);
        $('.disabled_6').prop('disabled', false);
        $('.enabled_6').prop('hidden', false);
        $('.enabled_6').prop('disabled', false);
        $('.enabled_6').val("");
        $(".section_week_6").html("");

    });


    //BULAN_7
    $('body').on('click','.week_list_7',function(){
        $('.section_week_7').prop('hidden', false);
        $(".section_week_7").html("<div style='font-size:12px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_7_minggu[]' placeholder='Prognosa Minggu 1' style='text-align:right; font-size:14px;' type='text' class='form-control prognosaValue number week_7'></div><div required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_7_minggu[]' placeholder='Prognosa Minggu 2' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_7'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_7_minggu[]' placeholder='Prognosa Minggu 3' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_7'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_7_minggu[]' placeholder='Prognosa Minggu 4' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_7'></div>");
        $('.input_7').prop('hidden', true);
        $('.start_7 a i').toggleClass("fa-plus-circle").toggleClass("fa-minus-circle out_7");
        $('.input_7').val("");
        $('.enabled_7').prop('hidden', true);
        $('.enabled_7').prop('disabled', true);

    });
    $('body').on('click','.out_7',function(){
        $('.input_7').prop('hidden', false);
        $('.section_week_7').prop('hidden', true);
        $('.week_7').prop('disabled', true);
        $('.disabled_7').prop('hidden', true);
        $('.disabled_7').prop('disabled', false);
        $('.enabled_7').prop('hidden', false);
        $('.enabled_7').prop('disabled', false);
        $('.enabled_7').val("");
        $(".section_week_7").html("");

    });

    //BULAN_8
    $('body').on('click','.week_list_8',function(){
        $('.section_week_8').prop('hidden', false);
        $(".section_week_8").html("<div style='font-size:12px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_8_minggu[]' placeholder='Prognosa Minggu 1' style='text-align:right; font-size:14px;' type='text' class='form-control prognosaValue number week_8'></div><div required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_8_minggu[]' placeholder='Prognosa Minggu 2' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_8'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_8_minggu[]' placeholder='Prognosa Minggu 3' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_8'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_8_minggu[]' placeholder='Prognosa Minggu 4' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_8'></div>");
        $('.input_8').prop('hidden', true);
        $('.start_8 a i').toggleClass("fa-plus-circle").toggleClass("fa-minus-circle out_8");
        $('.input_8').val("");
        $('.enabled_8').prop('hidden', true);
        $('.enabled_8').prop('disabled', true);

    });
    $('body').on('click','.out_8',function(){
        $('.input_8').prop('hidden', false);
        $('.section_week_8').prop('hidden', true);
        $('.week_8').prop('disabled', true);
        $('.disabled_8').prop('hidden', true);
        $('.disabled_8').prop('disabled', false);
        $('.enabled_8').prop('hidden', false);
        $('.enabled_8').prop('disabled', false);
        $('.enabled_8').val("");
        $(".section_week_8").html("");

    });

    //BULAN_9
    $('body').on('click','.week_list_9',function(){
        $('.section_week_9').prop('hidden', false);
        $(".section_week_9").html("<div style='font-size:12px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_9_minggu[]' placeholder='Prognosa Minggu 1' style='text-align:right; font-size:14px;' type='text' class='form-control prognosaValue number week_9'></div><div required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_9_minggu[]' placeholder='Prognosa Minggu 2' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_9'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_9_minggu[]' placeholder='Prognosa Minggu 3' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_9'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_9_minggu[]' placeholder='Prognosa Minggu 4' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_9'></div>");
        $('.input_9').prop('hidden', true);
        $('.start_9 a i').toggleClass("fa-plus-circle").toggleClass("fa-minus-circle out_9");
        $('.input_9').val("");
        $('.enabled_9').prop('hidden', true);
        $('.enabled_9').prop('disabled', true);

    });
    $('body').on('click','.out_9',function(){
        $('.input_9').prop('hidden', false);
        $('.section_week_9').prop('hidden', true);
        $('.week_9').prop('disabled', true);
        $('.disabled_9').prop('hidden', true);
        $('.disabled_9').prop('disabled', false);
        $('.enabled_9').prop('hidden', false);
        $('.enabled_9').prop('disabled', false);
        $('.enabled_9').val("");
        $(".section_week_9").html("");

    });

    //BULAN_10
    $('body').on('click','.week_list_10',function(){
        $('.section_week_10').prop('hidden', false);
        $(".section_week_10").html("<div style='font-size:12px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_10_minggu[]' placeholder='Prognosa Minggu 1' style='text-align:right; font-size:14px;' type='text' class='form-control prognosaValue number week_10'></div><div required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_10_minggu[]' placeholder='Prognosa Minggu 2' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_10'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_10_minggu[]' placeholder='Prognosa Minggu 3' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_10'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_10_minggu[]' placeholder='Prognosa Minggu 4' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_10'></div>");
        $('.input_10').prop('hidden', true);
        $('.start_10 a i').toggleClass("fa-plus-circle").toggleClass("fa-minus-circle out_10");
        $('.input_10').val("");
        $('.enabled_10').prop('hidden', true);
        $('.enabled_10').prop('disabled', true);

    });
    $('body').on('click','.out_10',function(){
        $('.input_10').prop('hidden', false);
        $('.section_week_10').prop('hidden', true);
        $('.week_10').prop('disabled', true);
        $('.disabled_10').prop('hidden', true);
        $('.disabled_10').prop('disabled', false);
        $('.enabled_10').prop('hidden', false);
        $('.enabled_10').prop('disabled', false);
        $('.enabled_10').val("");
        $(".section_week_10").html("");

    });

    //BULAN_11
    $('body').on('click','.week_list_11',function(){
        $('.section_week_11').prop('hidden', false);
        $(".section_week_11").html("<div style='font-size:12px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_11_minggu[]' placeholder='Prognosa Minggu 1' style='text-align:right; font-size:14px;' type='text' class='form-control prognosaValue number week_11'></div><div required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_11_minggu[]' placeholder='Prognosa Minggu 2' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_11'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_11_minggu[]' placeholder='Prognosa Minggu 3' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_11'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_11_minggu[]' placeholder='Prognosa Minggu 4' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_11'></div>");
        $('.input_11').prop('hidden', true);
        $('.start_11 a i').toggleClass("fa-plus-circle").toggleClass("fa-minus-circle out_11");
        $('.input_11').val("");
        $('.enabled_11').prop('hidden', true);
        $('.enabled_11').prop('disabled', true);

    });
    $('body').on('click','.out_11',function(){
        $('.input_11').prop('hidden', false);
        $('.section_week_11').prop('hidden', true);
        $('.week_11').prop('disabled', true);
        $('.disabled_11').prop('hidden', true);
        $('.disabled_11').prop('disabled', false);
        $('.enabled_11').prop('hidden', false);
        $('.enabled_11').prop('disabled', false);
        $('.enabled_11').val("");
        $(".section_week_11").html("");

    });

    //BULAN_12
    $('body').on('click','.week_list_12',function(){
        $('.section_week_12').prop('hidden', false);
        $(".section_week_12").html("<div style='font-size:12px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_12_minggu[]' placeholder='Prognosa Minggu 1' style='text-align:right; font-size:14px;' type='text' class='form-control prognosaValue number week_12'></div><div required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_12_minggu[]' placeholder='Prognosa Minggu 2' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_12'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_12_minggu[]' placeholder='Prognosa Minggu 3' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_12'></div><div style='font-size:14px;' required class='input-group mb-1'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_12_minggu[]' placeholder='Prognosa Minggu 4' style='text-align:right;font-size:14px' type='text' class='form-control prognosaValue number week_12'></div>");
        $('.input_12').prop('hidden', true);
        $('.start_12 a i').toggleClass("fa-plus-circle").toggleClass("fa-minus-circle out_12");
        $('.input_12').val("");
        $('.enabled_12').prop('hidden', true);
        $('.enabled_12').prop('disabled', true);

    });
    $('body').on('click','.out_12',function(){
        $('.input_12').prop('hidden', false);
        $('.section_week_12').prop('hidden', true);
        $('.week_12').prop('disabled', true);
        $('.disabled_12').prop('hidden', true);
        $('.disabled_12').prop('disabled', false);
        $('.enabled_12').prop('hidden', false);
        $('.enabled_12').prop('disabled', false);
        $('.enabled_12').val("");
        $(".section_week_12").html("");

    });


    $('body').on('click','.setMonthBelanja',function(){
        $.blockUI({
                message: 'Updating Data',
                fadeIn: 800,
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 1201,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });

        var month   = $(this).attr("month");
        var unit    = $(this).attr("unit");
        var segment = $(this).attr("segment");
        // alert(month);

        $.ajax({
                url    : '/admin/belanja/'+unit+'/'+segment+'/'+month,
                method : "GET",
                data   : {unit:unit,segment:segment,month:month},
                cache  : false,
                success: function(data){
                    $.unblockUI();
                    location.reload();
                }
        });
    });


    $('body').on('click','.openModal',function(){
        $.blockUI({
                message: 'Loading',
                fadeIn: 800,
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 1201,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });

        var what   = $(this).attr("what");
        $.ajax({
                url    : '/admin/utility/'+what,
                method : "GET",
                data   : {what:what},
                cache  : false,
                success: function(data){
                    $('.modal-content-compose').html(data);
                    $('#open-modal-compose').modal('show');
                    $('.filter').inputmask("99.99");  //static mask
                    CKEDITOR.replace('message', {
                    height: 150,
                    toolbar: [
                    {
                        name: 'sources',
                        items: [
                            'Source',
                        ]
                    },
                    {
                        name: 'basicstyles',
                        groups: ['basicstyles'],
                        items: [
                            'Format',
                            'Bold',
                            'Italic',
                            'Underline'
                        ]
                    },
                    {
                        name: 'paragraph',
                        groups: [
                            'list',
                            'indent',
                            'blocks',
                            'align',
                            'bidi'
                        ],
                        items: [
                            'NumberedList',
                            'BulletedList',
                            'JustifyLeft',
                            'JustifyCenter',
                            'JustifyRight',
                        ]
                    }
                ],
                    });

                    $('.select').select2({
                        dropdownParent: $('#open-modal-compose'),
                        tags: true
                    });
                    // var simplemde = new SimpleMDE({element: $("#smde")[0], toolbar: ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "link", "image", "|", "guide"]});
                    $.unblockUI();
                }
        });
    });

    $('body').on('change','.pickakunrealisasi',function(){
        $.blockUI({
                message: 'Loading',
                fadeIn: 800,
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 1201,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });

        var id = $('.select').val();
        var action     = $(this).attr("what");
        $.ajax({
                url    : '/satker/monitoring/modal/'+action+'/'+id,
                method : "GET",
                data   : {action:action,id:id},
                cache  : false,
                success: function(data){
                    $('.append_data').html(data);
                    $(document).ready(function() {
                        feather.replace();
                    });
                    $.unblockUI();
                }

        });
    });


    $('body').on('change','.type',function(){
        $.blockUI({
                message: 'Loading',
                fadeIn: 800,
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 1201,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });

        var what = $('.select').val();
        // alert(what)
        $.ajax({
                url    : '/admin/utility/'+what,
                method : "GET",
                data   : {what:what},
                cache  : false,
                success: function(data){
                    $('.append_data').html(data);
                    $('.select').select2({
                        dropdownParent: $('#open-modal-compose'),
                        tags: true
                    });
                    $.unblockUI();
                }

        });
    });

    $('body').on('click','.openmessage',function(){
        $.blockUI({
                message: 'Loading',
                fadeIn: 800,
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 1201,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });

        var id   = $(this).attr("id");
        $(".openmessage").removeClass('selected');
        $(this).toggleClass("selected");
        // alert(id);
        $.ajax({
                url    : '/admin/openmessage/'+id,
                method : "GET",
                data   : {id:id},
                cache  : false,
                success: function(data){
                    $('.email-content').html(data);
                    $.unblockUI();
                }
        });
    });

    $('body').on('click','.openmessageSatker',function(){
        $.blockUI({
                message: 'Loading',
                fadeIn: 800,
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 1201,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });

        var id   = $(this).attr("id");
        $(".openmessageSatker").removeClass('selected');
        $(this).toggleClass("selected");
        $(".bg-content").removeClass('email-blank');
        // alert(id);
        $.ajax({
                url    : '/satker/openmessage/'+id,
                method : "GET",
                data   : {id:id},
                cache  : false,
                success: function(data){
                    $('.email-content').html(data);
                    $.unblockUI();
                    $(".loadDataMessage").load(" .loadDataMessage");
                    $(".loadDataMessageSidebar").load(" .loadDataMessageSidebar");
                }

        });
    });


        function addCommas(nStr) {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + '.' + '$2');
            }
            return x1 + x2;
        }

        $('body').on('keyup','.number',function(){
            $(this).val(function(i, v) {
            return v.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            });
        });

    });

    function confirmation(ev) {
        ev.preventDefault();
        var urlToRedirect = ev.currentTarget.getAttribute('href'); //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
        console.log(urlToRedirect); // verify if this is the right URL
        Swal({
          title: "Reset Password",
          text: "Anda yakin akan mereset password ??",
          type: 'warning',
          showCancelButton: true,
          cancelButtonText:'No',
        })
        .then((result) => {
            if (result.value) {
            $.ajax({
                url:urlToRedirect,
                method:"GET",
                cache: false,
                success:function(data){
                 Swal({title: "Berhasil", text: "Data Berhasil di proses", type:"success",allowOutsideClick: false,closeOnConfirm: false,closeOnCancel: false});
                }
           });
          } else {

          }
        });
        }

    function confirmation_disabled(ev) {
        ev.preventDefault();
        var urlToRedirect = ev.currentTarget.getAttribute('href'); //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
        console.log(urlToRedirect); // verify if this is the right URL
        Swal({
          title: "Konfirmasi",
          text: "Anda yakin akan memproses data ??",
          type: 'warning',
          showCancelButton: true,
          cancelButtonText:'No',
        })
        .then((result) => {
            if (result.value) {
            $.ajax({
                url:urlToRedirect,
                method:"GET",
                cache: false,
                success:function(data){
                 Swal({title: "Berhasil", text: "Data Berhasil di proses", type:"success",allowOutsideClick: false,closeOnConfirm: false,closeOnCancel: false});
                 $("#loadPejabat").load(" #loadPejabat");
                 $("#loadPejabatInactive").load(" #loadPejabatInactive");
                 $(".loadSK").load(" .loadSK");
                 $('.modal').modal('hide');
                 $(".loadrpd").load(" .loadrpd");

                }
           });
          } else {

          }
        });
        }


    $('body').on('click','.OpenModalSnipper',function(){
        $.blockUI({
                message: 'Loading',
                fadeIn: 800,
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 1201,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });

        var what     = $(this).attr("what");
        var id     = $(this).attr("id");
        // alert(id)
        $.ajax({
                url    : '/satker/snipper/openmodal/'+what+'/'+id,
                method : "GET",
                data   : {what:what,id:id},
                cache  : false,
                success: function(data){
                    $('.modal-content-finduser').html(data);
                    $('.modal-content-finduser #id').val(id);
                    $('.select').select2({
                        dropdownParent: $('#open-modal-snipper'),
                        tags: true
                    });
                    $(document).ready(function() {
                            feather.replace();
                        });
                    $('#open-modal-snipper').modal('show');
                    $.unblockUI();
                    $('.datepicker-here').datepicker();
                    $(".datepicker-here").prop('required',true);
                }

        });
    });



    $('body').on('keyup','.onlynumber',function(){
        if (/\D/g.test(this.value))
            {
                this.value = this.value.replace(/\D/g, '');
            }
    });

    $('body').on('change','.nip',function(){
        $.blockUI({
                message: 'Loading',
                fadeIn: 800,
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 1201,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });
        var nip   = $(".nip").val();
        var what   = $(".what").val();
        $('.keterangan').prop('hidden', false);
            // alert(what);
        $.ajax({
                url    : '/satker/snipper/getdata/'+nip+'/'+what,
                method : "GET",
                data   : {nip:nip,what:what},
                cache  : false,
                success: function(data){
                    $.unblockUI();
                    $('.append_profile').html(data);
                    $('.select').select2({
                        dropdownParent: $('#open-modal-snipper'),
                        tags: true
                    });
                    $(document).ready(function() {
                            feather.replace();
                        });
                    $('.datepicker-here').datepicker();
                },
                error: function () {
                Swal({title: "Warning", text: "Data Pegawai Tidak Ditemukan Dalam Database Kepegawaian", type:"error", timer: 5000});
                $.unblockUI();
                $('.submitPegawai').prop( "disabled", true );

            }
            });
    });



    $('body').on('change','.jabatan',function(){
        $.blockUI({
                message: 'Loading',
                fadeIn: 800,
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 1201,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });
        var nip       = $(".nip").val();
        var jabatan   = $(".jabatan").val();
        $.ajax({
                url    : '/satker/snipper/getcertificate/'+nip+'/'+jabatan,
                method : "GET",
                data   : {nip:nip,jabatan:jabatan},
                cache  : false,
                success: function(data){
                    $(document).ready(function() {
                            feather.replace();
                        });
                        $('.append_certificate').html(data);

                    $.unblockUI();
                }
            });
    });

        $('body').on('change','.file',function(){
            var file = $(".file").val();
            var ekstensiOk = /(\.pdf)$/i;
           if(!ekstensiOk.exec(file))
                {
                Swal({title: "Warning", text: "File Harus PDF", type:"warning", timer: 100000});
                $('.submitPegawai').prop( "disabled", true );
                }
            else
                {
                $('.submitPegawai').prop( "disabled", false );
                }
        });

        $('body').on('change','.txt',function(){
            // alert('halo');
            var file = $(".txt").val();
            var ekstensiOk = /(\.txt)$/i;
           if(!ekstensiOk.exec(file))
                {
                Swal({title: "Warning", text: "File Harus txt", type:"warning", timer: 100000});
                $('.submit').prop( "disabled", true );
                }
            else
                {
                $('.submit').prop( "disabled", false );
                }
        });

        $('body').on('change','.pdf',function(){
            // alert('halo');
            var file = $(".pdf").val();
            var ekstensiOk = /(\.pdf)$/i;
           if(!ekstensiOk.exec(file))
                {
                Swal({title: "Warning", text: "File Harus PDF", type:"warning", timer: 100000});
                $('.submit').prop( "disabled", true );
                }
            else
                {
                $('.submit').prop( "disabled", false );
                }
        });

        $('body').on('change','.csv',function(){
            var file = $(".csv").val();
            var ekstensiOk = /(\.csv)$/i;
           if(!ekstensiOk.exec(file))
                {
                Swal({title: "Warning", text: "File Harus csv", type:"warning", timer: 100000});
                $('.submit').prop( "disabled", true );
                }
            else
                {
                $('.submit').prop( "disabled", false );
                }
        });

        $('body').on('change','.xlsx',function(){
            var file = $(".xlsx").val();
            var ekstensiOk = /(\.xlsx)$/i;
           if(!ekstensiOk.exec(file))
                {
                Swal({title: "Warning", text: "File Harus xlsx", type:"warning", timer: 100000});
                $('.submit').prop( "disabled", true );
                }
            else
                {
                $('.submit').prop( "disabled", false );
                }
        });

        $('body').on('click','.OpenModalMp',function(){
        $.blockUI({
                message: 'Loading',
                fadeIn: 800,
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 1201,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });

        var what     = $(this).attr("what");
        var id     = $(this).attr("id");
        // alert(what)
        $.ajax({
                url    : '/satker/mppnbp/openmodal/'+what+'/'+id,
                method : "GET",
                data   : {what:what,id:id},
                cache  : false,
                success: function(data){
                    $('.modal-content-mp').html(data);
                    $('.modal-content-mp #id').val(id);
                    $('.select').select2({
                        dropdownParent: $('#open-modal-mp'),
                        tags: true
                    });
                    $(document).ready(function() {
                            feather.replace();
                        });
                    $('#open-modal-mp').modal('show');
                    $.unblockUI();
                }

        });
    });

        $('body').on('blur','.angkarpd',function(e){
            e.preventDefault();
            var sisa = $(".sisa").val();
            $('.angkarpd').each(function() {
            rpd = Number($(this).val().replace(/[^0-9]+/g,""));
            });
            // alert(sisa)
            if(rpd>sisa) {
                Swal({title: "Warning", text: "Total RPD Melebihi Pagu", type:"error", timer: 5000});
                $('.submitData').prop('disabled', true);
            } else {
                $('.submitData').prop('disabled', false);
            }
        });

        $('body').on('blur','.realisasiCovid',function(e){
            e.preventDefault();

            var sisa = $("#sisa").val();
            // alert(sisa);
            $('.realisasiCovid').each(function() {
            realisasi = Number($(this).val().replace(/[^0-9]+/g,""));
            });
            // alert(sisa)
            if(realisasi>sisa) {
                Swal({title: "Warning", text: "Total Realisasi Melebihi Pagu Kegiatan", type:"error", timer: 5000});
                $('.submit').prop('disabled', true);
            } else {
                $('.submit').prop('disabled', false);
            }

        });


    $('body').on('click','.OpenModalAdminSnipper',function(){
        $.blockUI({
                message: 'Loading',
                fadeIn: 800,
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 1201,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });

        var what     = $(this).attr("what");
        var id     = $(this).attr("id");
        // alert(id)
        $.ajax({
                url    : '/admin/snipper/openmodal/'+what+'/'+id,
                method : "GET",
                data   : {what:what,id:id},
                cache  : false,
                success: function(data){
                    $('.modal-content-finduser').html(data);
                    $('.modal-content-finduser #id').val(id);
                    $('.select').select2({
                        dropdownParent: $('#open-modal-snipper'),
                        tags: true
                    });
                    $(document).ready(function() {
                            feather.replace();
                        });
                    $('#open-modal-snipper').modal('show');
                    $.unblockUI();
                    $('.datepicker-here').datepicker();
                    $(".datepicker-here").prop('required',true);
                }

        });
    });
