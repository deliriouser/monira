@switch($what)
    @case('create')
    <form id="myform" action="{{route('satker/mppnbp/post/rpd')}}" method="POST">
        @csrf
        <input type="hidden" value="{{$id}}" name="bulan">
        <input type="hidden" class="sisa" name="sisa" value="{{$sisa}}">
        <div class="modal-header">
            <h5 class="modal-title">Input Data RPD Bulan {{nameofmonth($id)}}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
                    <div class="mb-2">
                    <label class="form-label">Jenis Akun</label>
                    <div class="input-group">
                        <select style="font-size:14px" required class="form-control select col-sm-12" name="akun">
                            <option value="">Pilih</option>
                            @foreach ($data as $item)
                            <option value="{{$item->Akun}}">{{$item->keterangan->NamaAkun}}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Jumlah Penarikan</label>
                        <div class="input-group">
                                <input required name="jumlah" style="text-align:left; font-size:14px" type="text" class="form-control number angkarpd">
                         </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Prioritas</label>
                        <div class="input-group">
                            <select style="font-size:14px" required class="form-control select col-sm-12" name="prioritas">
                                <option value="">Pilih</option>
                                <option value="0">Rendah</option>
                                <option value="1">Sedang</option>
                                <option value="2">Tinggi</option>
                            </select>
                         </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Rincian Kegiatan</label>
                        <div class="input-group">
                            <textarea style="font-size:14px" required class="form-control" name="keterangan" rows="3"></textarea>
                         </div>
                    </div>

         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary submitData" type="submit">Simpan</button>
         </div>
    </form>
    @break

    @case('read')
    <div class="modal-header">
        <h5 class="modal-title">Daftar RPD Bulan {{nameofmonth($id)}}</h5>
        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
     </div>
     <div class="modal-body">

    {{-- <div class="table-responsive"> --}}
        <table class="table table-sm">
            <thead class="bg-primary">
                <tr>
                    <th class="text-center">NO</th>
                    <th class="text-start">AKUN</th>
                    <th class="text-end">JUMLAH</th>
                    <th class="text-center">...</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                <tr class=" @if($item->prioritas==0) table-success @elseif($item->prioritas==1) table-warning @else table-danger @endif ">
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td class="text-start">{{$item->akun}} - {{$item->ketAkun->NamaAkun ?? ''}}<br>
                        <small>{{$item->keterangan}}</small>
                    </td>
                    <td class="text-end">{{RP($item->jumlah)}}</td>
                    <td>
                        <a title="hapus" onclick="confirmation_disabled(event)" href="{{route('satker/mppnbp/rpd',['status'=>'0', 'id'=>Crypt::encrypt($item->id),'what'=>'rpd'])}}" data-bs-original-title="" class="text-danger"><i class="icofont icofont-error fa-2x"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-info">
                    <th class="text-center"></th>
                    <th class="text-end">TOTAL</th>
                    <th class="text-end">{{RP($data->sum('jumlah'))}}</th>
                    <th class="text-center"></th>
                </tr>
            </tfoot>

        </table>
    </div>
</div>
<div class="modal-footer">
   <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
</div>

    @break
@endswitch


