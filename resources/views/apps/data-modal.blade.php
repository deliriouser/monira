@switch($action)
    @case('insertPrognosa')

    <form id="myform" action="{{route('satker/post/prognosa')}}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">{{$titleHead}}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">


            <div class="list-group mb-3">
                <a class="list-group-item list-group-item-danger kegiatan text-center"></a>
                <a class="list-group-item list-group-item-warning output text-center"></a>
                <a class="list-group-item list-group-item-primary akun text-center"></a>
                <a class="list-group-item list-group-item-success dana text-center"></a>
                <a class="list-group-item list-group-item-grey pagu text-center"></a>
            </div>

            <div class="list-group mb-3 text-center">
            <button class="btn btn-outline-info btn-sm mb-3 autofill" type="button" title="Set Otomatis">Isi Bagi Rata</button>
            <button class="btn btn-outline-success btn-sm mb-3 autofill-realisasi" type="button" title="Set Otomatis">Isi Sesuai Realisasi</button>
            <button class="btn btn-outline-danger btn-sm resetfill" type="button" title="Set Otomatis">Reset</button>
            </div>

            @foreach ($data as $item)
            <input value="{{$item->JAN}}" disabled type="hidden" id="JAN">
            <input value="{{$item->FEB}}" disabled type="hidden" id="FEB">
            <input value="{{$item->MAR}}" disabled type="hidden" id="MAR">
            <input value="{{$item->APR}}" disabled type="hidden" id="APR">
            <input value="{{$item->MEI}}" disabled type="hidden" id="MEI">
            <input value="{{$item->JUN}}" disabled type="hidden" id="JUN">
            <input value="{{$item->JUL}}" disabled type="hidden" id="JUL">
            <input value="{{$item->AGS}}" disabled type="hidden" id="AGS">
            <input value="{{$item->SEP}}" disabled type="hidden" id="SEP">
            <input value="{{$item->OKT}}" disabled type="hidden" id="OKT">
            <input value="{{$item->NOV}}" disabled type="hidden" id="NOV">
            <input value="{{$item->DES}}" disabled type="hidden" id="DES">

            @endforeach

            <input type="hidden" id="pagu">
            <input type="hidden" id="id" name="id">

            <div class="table-responsive">
                <table class="table">
                  <thead class="bg-primary">
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Bulan</th>
                      <th class="text-center">Prognosa</th>
                    </tr>
                  </thead>
                  <tbody>
                      @for($i=1;$i<=12;$i++)
                    <tr class="mb-0 pb-0">
                      <td class="text-center" valign="middle">{{$i}}</td>
                      <td class="text-start start_{{$i}}" valign="middle"><a href="#" tilte="Prognosa Mingguan"><i class="fa fa-plus-circle week_list_{{$i}} text-primary"></i></a> {{nameofmonth($i)}} </td>
                      <td>
                          <input name="bulan[]" style="text-align:right; font-size:14px" type="text" class="form-control prognosaValue number input_{{$i}}">
                          <div class="section_week_{{$i}}"></div>
                      </td>
                    </tr>
                    @endfor
                  </tbody>
                  <tfoot>
                    <tr class="table-danger">
                      <th class="text-center"></th>
                      <th class="text-center">Prognosa</th>
                      <th class="text-end"><input disabled id="total" style="text-align:right; font-size:14px" type="text" class="form-control number"></th>
                    </tr>
                    <tr class="table-primary">
                        <th class="text-center"></th>
                        <th class="text-center">Pagu</th>
                        <th class="text-end"><input disabled style="text-align:right; font-size:14px" type="text" class="form-control pagu"></th>
                      </tr>
                      <tr class="table-warning">
                        <th class="text-center"></th>
                        <th class="text-center">Sisa</th>
                        <th class="text-end"><input disabled style="text-align:right; font-size:14px" type="text" class="form-control sisa"></th>
                      </tr>
                  </tfoot>

                </table>
                <div class="newSection"></div>
              </div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-success validasi" type="button">Validasi</button>
            <button class="btn btn-primary submitPrognosa" type="submit">Simpan</button>
         </div>
    </form>

    @break

    @case('updatePrognosa')

    <form id="myform" action="{{route('satker/post/prognosa')}}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Data Prognosa</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">


            <div class="list-group mb-3">
                <a class="list-group-item list-group-item-danger kegiatan text-center"></a>
                <a class="list-group-item list-group-item-warning output text-center"></a>
                <a class="list-group-item list-group-item-primary akun text-center"></a>
                <a class="list-group-item list-group-item-success dana text-center"></a>
                <a class="list-group-item list-group-item-grey pagu text-center"></a>
            </div>

            <div class="list-group mb-3 text-center">
                <button class="btn btn-outline-info btn-sm mb-3 autofill" type="button" title="Set Otomatis">Bagi Rata</button>
                <button class="btn btn-outline-success btn-sm mb-3 autofill-realisasi" type="button" title="Set Otomatis">Isi Sesuai Realisasi</button>
                <button class="btn btn-outline-danger btn-sm resetfill" type="button" title="Set Otomatis">Reset</button>
                </div>


                @foreach ($data_dsa as $item)

                <input value="{{$item->JAN}}" disabled type="hidden" id="JAN">
                <input value="{{$item->FEB}}" disabled type="hidden" id="FEB">
                <input value="{{$item->MAR}}" disabled type="hidden" id="MAR">
                <input value="{{$item->APR}}" disabled type="hidden" id="APR">
                <input value="{{$item->MEI}}" disabled type="hidden" id="MEI">
                <input value="{{$item->JUN}}" disabled type="hidden" id="JUN">
                <input value="{{$item->JUL}}" disabled type="hidden" id="JUL">
                <input value="{{$item->AGS}}" disabled type="hidden" id="AGS">
                <input value="{{$item->SEP}}" disabled type="hidden" id="SEP">
                <input value="{{$item->OKT}}" disabled type="hidden" id="OKT">
                <input value="{{$item->NOV}}" disabled type="hidden" id="NOV">
                <input value="{{$item->DES}}" disabled type="hidden" id="DES">

                @endforeach

            <input type="hidden" id="pagu">
            <input type="hidden" id="id" name="id">

            <div class="table-responsive">
                <table class="table">
                  <thead class="bg-primary">
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Bulan</th>
                      <th class="text-center">Prognosa</th>
                    </tr>
                  </thead>
                  <tbody>
                      @php $prognosa = 0 @endphp
                      @foreach($data as $item)
                    <tr class="mb-0 pb-0">
                      <td class="text-center" valign="middle">{{$item->Bulan}}</td>
                      <td class="text-start start_{{$item->Bulan}}" valign="middle"><a href="#" tilte="Prognosa Mingguan"><i class="fa week_list_{{$item->Bulan}} @if(count($item->mingguan)>0) fa-minus-circle out_{{$item->Bulan}} @else fa-plus-circle @endif text-primary"></i></a> {{nameofmonth($item->Bulan)}} </td>
                      <td>
                          @php
                              $total=0;
                          @endphp
                          @foreach($item->mingguan as $minggu)
                          <div style='font-size:12px;' required class='input-group mb-1 disabled_{{$item->Bulan}}'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_{{$item->Bulan}}_minggu[]' style='text-align:right; font-size:14px;' type='text' class='form-control prognosaValue number week_{{$item->Bulan}}_disabled' value="{{RP($minggu->Amount)}}"></div>
                            @php $total += $minggu->Amount; @endphp
                          @endforeach
                          @php
                              $prognosa+=$total;
                          @endphp
                          @if($total>0)
                          <div style='font-size:12px;' required class='input-group mb-1'>
                            <input name='bulan[]' style='text-align:right; font-size:14px;' type='text' disabled class='form-control input_{{$item->Bulan}} prognosaValue enabled_{{$item->Bulan}}' value="{{RP($total)}}">
                          </div>
                          @else
                          <input name="bulan[]" style="text-align:right; font-size:14px" type="text" class="form-control mt-1 prognosaValue number input_{{$item->Bulan}}" value="{{RP($item->Amount)}}">
                          @endif
                          <div class="section_week_{{$item->Bulan}}"></div>
                    </td>
                    </tr>
                    {{-- @php $prognosa += $total @endphp --}}
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr class="table-danger">
                      <th class="text-center"></th>
                      <th class="text-center">Prognosa</th>
                      <th class="text-end"><input disabled id="total" style="text-align:right; font-size:14px" type="text" class="form-control" value="{{RP($prognosa+$data->sum('Amount'))}}"></th>
                    </tr>
                    <tr class="table-primary">
                        <th class="text-center"></th>
                        <th class="text-center">Pagu</th>
                        <th class="text-end"><input disabled style="text-align:right; font-size:14px" type="text" class="form-control pagu"></th>
                      </tr>
                      <tr class="table-warning">
                        <th class="text-center"></th>
                        <th class="text-center">Sisa</th>
                        <th class="text-end"><input disabled style="text-align:right; font-size:14px" type="text" class="form-control sisa" id="sisa"></th>
                      </tr>
                  </tfoot>

                </table>
                <div class="newSectionx">
                    {{-- @if(!empty($justifikasi->Justifikasi)) --}}
                    <div class='form-group mb-3 mt-3'><label class='col-form-label'>Justifikasi Prognosa Tidak 100%</label><textarea required style="font-size:14px;" class='form-control wajib' name='justifikasi' rows='3'>{{$justifikasi->Justifikasi ?? ''}}</textarea></div>
                    {{-- @endif --}}
                </div>
            </div>
         </div>
         <div class="modal-footer">
          <button class="btn btn-secondary text-start" type="button" data-bs-dismiss="modal">Close</button>
          <button class="btn btn-success validasi" type="button">Validasi</button>
            <button class="btn btn-primary submitPrognosa" type="submit">Simpan</button>
         </div>
    </form>
    @break

    @case('locking')

        <div class="modal-header">
            <h5 class="modal-title">Status Data Prognosa</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">

            <div class="alert alert-secondary" role="alert">
                <p>Data Prognosa Realisasi Sudah Terkunci, Harap Menghubungi Admin</p>
              </div>

         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
         </div>

    @break

@endswitch


