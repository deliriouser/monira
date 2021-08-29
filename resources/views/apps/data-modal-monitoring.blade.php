@switch($action)
    @case('insertKegiatanCovid')
    <form id="myform" action="{{route('satker/post/data')}}" method="POST">
        @csrf
        <input type="hidden" name="type" value="kegiatan">
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

            <input type="hidden" id="pagu">
            <input type="hidden" id="id" name="id">


            <div class="mb-3">
                <label class="form-label">Pagu Anggaran Kegiatan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="credit-card"></i></span>
                    <input required style="text-align:right; font-size:14px" type="text" class="form-control number" name="pagu">
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Volume Kegiatan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="layers"></i></span>
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number" name="volume">
                    </div>
                </div>
                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Satuan</label>
                    <select style="font-size:14px" required class="form-control select col-sm-12" name="satuan">
                        <option value="">Pilih</option>
                        @foreach ($satuan as $item)
                        <option value="{{$item->Satuan}}">{{$item->Satuan}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Uraian Kegiatan / Barang</label>
                <div class="input-group">
                    <textarea required style="font-size:14px;" class='form-control' name='kegiatan' rows='2'></textarea>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Catatan / Keterangan</label>
                <div class="input-group">
                    <textarea style="font-size:14px;" class='form-control' name='catatan' rows='3'></textarea>
                </div>
            </div>

         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary submit" type="submit">Simpan</button>
         </div>
    </form>

    @break

    @case('updateKegiatanCovid')
    <form id="myform" action="{{route('satker/post/data')}}" method="POST">
        @csrf
        <input type="hidden" name="type" value="updatekegiatan">
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

            <input type="hidden" id="pagu">
            <input type="hidden" id="id" name="guid">
            <input type="hidden" name="id" value="0000.000.000000.0.0.0000000">


            <div class="mb-3">
                <label class="form-label">Pagu Anggaran Kegiatan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="credit-card"></i></span>
                    <input required style="text-align:right; font-size:14px" type="text" class="form-control number" name="pagu" value="{{RP($data->Amount)}}">
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Volume Kegiatan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="layers"></i></span>
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number" name="volume" value="{{$data->Volume}}">
                    </div>
                </div>
                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Satuan</label>
                    <select style="font-size:14px" required class="form-control select col-sm-12" name="satuan">
                        <option value="">Pilih</option>
                        @foreach ($satuan as $item)
                        <option @if($data->Satuan==$item->Satuan) selected @endif value="{{$item->Satuan}}">{{$item->Satuan}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Uraian Kegiatan / Barang</label>
                <div class="input-group">
                    <textarea required style="font-size:14px;" class='form-control' name='kegiatan' rows='2'>{{$data->Uraian}}</textarea>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Catatan / Keterangan</label>
                <div class="input-group">
                    <textarea style="font-size:14px;" class='form-control' name='catatan' rows='3'>
                    {{$data->BudgetType}}</textarea>
                </div>
            </div>

         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary submit" type="submit">Simpan</button>
         </div>
    </form>

    @break

    @case('insertRealisasiCovid')
    <form id="myform" action="{{route('satker/post/data')}}" method="POST">
        @csrf
        <input type="hidden" name="type" value="realisasi">

        <div class="modal-header">
            <h5 class="modal-title">{{$titleHead}}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">


            <div class="list-group mb-3">
                <a class="list-group-item list-group-item-danger kegiatan text-center"></a>
                <a class="list-group-item list-group-item-warning dana text-center"></a>
                <a class="list-group-item list-group-item-primary pagu text-center"></a>
            </div>

            <input type="hidden" id="pagu">
            <input type="hidden" name="guid" id="akun">
            <input type="hidden" id="sisa">
            <input type="hidden" id="id" name="id">
            <div class="row">

                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Rupiah Realisasi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="credit-card"></i></span>
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number realisasiCovid" name="realisasi">
                    </div>
                </div>

                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Volume Kegiatan</label>
                    <div class="input-group">
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number" name="volume">
                        <span class="input-group-text output"></span>
                    </div>
                </div>

            </div>
            <div class="row">
            <div class="mb-3 col-sm-6">
                <label class="form-label">Tanggal SP2D</label>
                <input required name="bulan" style="font-size:14px; background-color:#fff;" readonly class="datepicker-here form-control" type="text" data-language="en">

            </div>
            <div class="mb-3 col-sm-6">
                <label class="form-label">Nomor SP2D</label>
                <input maxlength="25" required style="font-size:14px" type="text" class="form-control number" name="nosp2d">

            </div>
            </div>


         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary submit" type="submit">Simpan</button>
         </div>
    </form>

    @break


    @case('dataRealisasiCovid')
        <input type="hidden" name="type" value="realisasi">

        <div class="modal-header">
            <h5 class="modal-title">{{$titleHead}}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">

            <div class="table-responsive">
                <table class="table table-sm" id="page-all">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-center">NO</th>
                            <th class="text-center">TGL / NO SP2D</th>
                            <th class="text-end">REALISASI</th>
                            <th class="text-center">VOLUME</th>
                            <th class="text-center">...</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @dd($data) --}}
                        @if(count($data)>0)
                        @foreach ($data as $item)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td class="text-start">{{\Carbon\Carbon::parse($item->Bulan ?? '')->isoFormat('DD/MM/YYYY')}}<br>{{$item->Nosp2d ?? ''}}</td>
                            <td class="text-end">{{RP($item->Amount)}}</td>
                            <td class="text-center">{{($item->Volume)}} {{($item->Satuan)}}</td>
                            <td class="text-center">
                                <a onclick="confirmation_disabled(event)" href="{{route('satker/monitoring/status',['status'=>'0', 'id'=> Crypt::encrypt($item->idtable),'what'=>'realisasi'])}}" data-bs-original-title="" class="text-danger"><i class="icofont icofont-error fa-2x"></i></a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td class="text-center" colspan="5">Belum Ada Data Realisasi</td>
                        </tr>
                        @endif
                    </tbody>
                    @if(count($data)>0)
                    <tfoot class="table-primary">
                        <tr>
                            <th class="text-center"></th>
                            <th class="text-center">Total</th>
                            <th class="text-end">{{RP($data->sum('Amount') ?? '')}}</th>
                            <th class="text-center">{{$data->sum('Volume') ?? ''}} {{($item->Satuan ?? '')}}</th>
                            <th class="text-center">...</th>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>


         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
         </div>

    @break

    @case('insertPadatKarya')
    <form id="myform" action="{{route('satker/post/data')}}" method="POST">
        @csrf
        <input type="hidden" name="type" value="padatkarya">
        <input type="hidden" name="id" value="0000.000.000000.0.0.0000000">

        <div class="modal-header">
            <h5 class="modal-title">{{$titleHead}}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <div class="row">
                <div class="col-8"><h6 class="">KEGIATAN</h6></div>
                <div title="Tambah Akun Kegiatan" class="col-4 text-primary text-end"><a class="addakun" href="#"><i data-feather="plus-circle"></i></a></div>
            </div>
            <hr class="mt-2 mb-3">
            <div class="row">
            <div class="mb-3 col-xl-7 col-sm-12">
                <label class="form-label">Akun Belanja</label>
                <select style="font-size:14px" required class="form-control select col-sm-12 kabupaten" name="akun[]">
                    <option value="">Pilih Akun Belanja</option>
                    @foreach ($akun as $item)
                        <option value="{{$item->Id}}.{{$item->Kewenangan}}.{{$item->Program}}">{{$item->Id}} : {{$item->keterangan->NamaAkun}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-xl-5 col-sm-12">
                <label class="form-label">Jumlah Pagu Kegiatan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="credit-card"></i></span>
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number pagukegiatan" name="pagukegiatan[]" value="">
                </div>
            </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Kegiatan Padat Karya</label>
                <div class="input-group">
                    <input required style="text-align:left; font-size:14px; padding:9px !important;" type="text" class="form-control" name="kegiatan[]">
                </div>
            </div>
            <div class="newData"></div>
            <h6 class="mt-4">RINCIAN KEGIATAN</h6>
            <hr class="mt-2 mb-3">
            <div class="mb-3">
                <label class="form-label">Total Pagu</label>
                <div class="input-group">
                    <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control totalpagu number" name="totalpagu">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Lokasi Kegiatan</label>
                <select style="font-size:14px" required class="form-control select col-sm-12 kabupaten" name="kdlokasi">
                    <option value="">Pilih Desa/Kecamatan Lokasi</option>
                    @foreach ($lokasi as $item)
                    @if(strlen($item->kode)==5)
                        <option disabled value="{{$item->kode}}">{{$item->Nama}}</option>
                    @elseif(strlen($item->kode)==8)
                        <option disabled value="{{$item->kode}}">Kecamatan {{$item->Nama}}</option>
                    @elseif(strlen($item->kode)>8)
                        <option value="{{$item->kode}}">Kelurahan / Desa {{$item->Nama}}</option>
                    @endif
                    @endforeach
                </select>
            </div>


            <div class="row">
            <div class="mb-3 col-xl-6 col-sm-12">
                <label class="form-label">Rencana Waktu Pelaksanaan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="calendar"></i></span>
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control" name="jadwal">
                </div>
            </div>
            <div class="mb-3 col-xl-6 col-sm-12">
                <label class="form-label">Mekanisme Pelaksanan</label>
                <div class="input-group">
                    <select style="font-size:14px" required class="form-control select col-sm-12" name="mekanisme">
                        <option value="">Pilih</option>
                            <option value="Swakelola">Swakelola</option>
                            <option value="PL Penyedia Jasa">PL Penyedia Jasa</option>
                            <option value="Lelang">Lelang</option>
                    </select>

                </div>
            </div>
            </div>

            <div class="row">

            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Orang</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="user"></i></span>
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number orang" name="jumlahorang">
                </div>
            </div>
            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Hari</label>
                <div class="input-group">
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number hari" name="jumlahhari">
                    <span class="input-group-text">Hari</span>
                </div>
            </div>
            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Orang x Hari</label>
                <div class="input-group">
                    <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control oranghari number" id="oranghari" name="jumlahoranghari">
                </div>
            </div>
            </div>

            <div class="row">
                <div class="mb-3 col-xl-3 col-sm-12">
                    <label class="form-label">Upah Harian</label>
                    <div class="input-group">
                        {{-- <span class="input-group-text"><i data-feather="credit-card"></i></span> --}}
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number upah" name="upahharian" value="150.000">
                    </div>
                </div>
                <div class="mb-3 col-xl-5 col-sm-12">
                    <label class="form-label">Total Biaya Upah</label>
                    <div class="input-group">
                        <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control biayaupah number" id="biayaupah" name="biayaupah">
                        <span class="input-group-text persenupah" style="font-size:14px;">{{Persen($item->PersenBiayaUpah)}}</span>
                    </div>
                </div>
                <div class="mb-3 col-xl-4 col-sm-12">
                    <label class="form-label">Total Biaya Lain</label>
                    <div class="input-group">
                        {{-- <span class="input-group-text"><i data-feather="credit-card"></i></span> --}}
                        <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control biayalain number" id="biayalain" name="biayalain">
                    </div>
                </div>
            </div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-success validasi" type="button">Validasi</button>
            <button class="btn btn-primary submit" type="submit">Simpan</button>
         </div>

    </form>
    @break



    @case('realisasiPadatKarya')
    <form id="myform" action="{{route('satker/post/data')}}" method="POST">
        @csrf
        <input type="hidden" name="type" value="realisasipadatkarya">
        <input type="hidden" name="id" id="sisa" value="0000.000.000000.0.0.0000000">
        <input type="hidden" id="id" name="guid">
        <input type="hidden" id="akun" name="akun">
        {{-- @foreach ($data as $item)

        <input style="text-align:right; font-size:14px; padding:9px !important;" type="hidden" class="form-control number pagukegiatan" name="pagukegiatan[]" value="{{RP($item->Amount)}}">

        @endforeach --}}

        <div class="modal-header">
            <h5 class="modal-title">{{$titleHead}}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Jumlah Pagu Kegiatan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="credit-card"></i></span>
                    <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" id="pagu" class="form-control number totalpagu pagukegiatan" name="pagu">
                    <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="hidden" disabled class="form-control number totalpagu">
                </div>
                </div>

            {{-- <div class="mb-3">
                <label class="form-label">Pilih Akun Kegiatan</label>
                <select what="showpaguakun" style="font-size:14px" required class="form-control select col-sm-12 pickakunrealisasi" name="akun">
                    <option value="">Pilih</option>
                    @foreach ($data as $item)
                        <option value="{{$item->Id}}">{{$item->Akun}} : {{$item->Uraian}}</option>
                    @endforeach
                </select>


            </div>
            <div class="append_data">
            </div> --}}

            <div class="row">
            <div class="mb-3 col-xl-6 col-sm-12">
                <label class="form-label">Waktu Pelaksanaan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="calendar"></i></span>
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control" name="jadwal">
                </div>
            </div>
            <div class="mb-3 col-xl-6 col-sm-12">
                <label class="form-label">Mekanisme Pelaksanan</label>
                <div class="input-group">
                    <select style="font-size:14px" required class="form-control select col-sm-12" name="mekanisme">
                        <option value="">Pilih</option>
                            <option value="Swakelola">Swakelola</option>
                            <option value="PL Penyedia Jasa">PL Penyedia Jasa</option>
                            <option value="Lelang">Lelang</option>
                    </select>

                </div>
            </div>
            </div>

            <div class="row">

            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Orang</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="user"></i></span>
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number orang" name="jumlahorang">
                </div>
            </div>
            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Hari</label>
                <div class="input-group">
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number hari" name="jumlahhari">
                    <span class="input-group-text">Hari</span>
                </div>
            </div>
            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Orang x Hari</label>
                <div class="input-group">
                    <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control oranghari number" id="oranghari" name="jumlahoranghari">
                </div>
            </div>
            </div>

            <div class="row">
                <div class="mb-3 col-xl-3 col-sm-12">
                    <label class="form-label">Upah Harian</label>
                    <div class="input-group">
                        {{-- <span class="input-group-text"><i data-feather="credit-card"></i></span> --}}
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number upah" name="upahharian" value="150.000">
                    </div>
                </div>
                <div class="mb-3 col-xl-5 col-sm-12">
                    <label class="form-label">Total Biaya Upah</label>
                    <div class="input-group">
                        <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control biayaupah number" id="biayaupah" name="biayaupah">
                        <span class="input-group-text persenupah" style="font-size:14px;"></span>
                    </div>
                </div>
                <div class="mb-3 col-xl-4 col-sm-12">
                    <label class="form-label">Total Biaya Lain</label>
                    <div class="input-group">
                        {{-- <span class="input-group-text"><i data-feather="credit-card"></i></span> --}}
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number totalbiayalain" name="totalbiayalain">
                    </div>
                </div>
            </div>
                <div class="row">
                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Jumlah Realisasi Kegiatan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="credit-card"></i></span>
                        <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number jumlahRealisasi" id="jumlahRealisasi" name="jumlahRealisasi">
                    </div>
                </div>
                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Sisa Anggaran Kegiatan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="credit-card"></i></span>
                        <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number sisaRealisasi" id="sisaRealisasi" name="sisaRealisasi">
                    </div>
                </div>
                </div>

                <div class="row mt-3">
                    <div class="col-8"><h6 class="">RINCIAN PEMBAYARAN</h6></div>
                    <div title="Tambah No SP2D" class="col-4 text-primary text-end"><a class="addsp2d" href="#"><i data-feather="plus-circle"></i></a></div>
                </div>
                <hr class="mt-2 mb-2">


                <div class="row">
                    <div class="mb-3 col-sm-5">
                        <label class="form-label">Tanggal SP2D</label>
                        <div class="input-group">
                            <span class="input-group-text"><i data-feather="calendar"></i></span>
                        <input required name="tanggal[]" style="font-size:14px; background-color:#fff;" readonly class="datepicker-here form-control" type="text" data-language="en">
                        </div>
                    </div>
                    <div class="mb-3 col-sm-7">
                        <label class="form-label">Nomor SP2D</label>
                        <div class="input-group">
                            <span class="input-group-text"><i data-feather="file-text"></i></span>
                        <input maxlength="25" required style="font-size:14px" type="text" class="form-control number" name="nosp2d[]">
                        </div>
                    </div>
                </div>
                <div class="newDatasp2d"></div>

                <div class="mb-3">
                    <label class="form-label">Keterangan / Kendala Pelaksanaan</label>
                    <div class="input-group">
                        <textarea style="font-size:14px;" class='form-control' name='keterangan' rows='3'></textarea>
                    </div>
                </div>

         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-success validasi" type="button">Validasi</button>
            <button class="btn btn-primary submit" type="submit">Simpan</button>
         </div>

    </form>
    @break

    @case('editRPadatKarya')
    <form id="myform" action="{{route('satker/post/data')}}" method="POST">
        @csrf
        <input type="hidden" name="type" value="editrealisasipadatkarya">
        <input type="hidden" name="id" value="0000.000.000000.0.0.0000000">
        <input type="hidden" id="id" name="guid">
        <input type="hidden" name="guid_sppd" value="{{$item->guid_sppd}}">

        {{-- @foreach ($data as $item_pagu) --}}

        <input style="text-align:right; font-size:14px; padding:9px !important;" type="hidden" class="form-control number pagukegiatan" name="pagukegiatan[]" value="{{RP($item->TotalPaguDipa)}}">

        {{-- @endforeach --}}



        <div class="modal-header">
            <h5 class="modal-title">{{$titleHead}}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Total Pagu Kegiatan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="credit-card"></i></span>
                    <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number pagu totalpagu" id="pagu" name="pagu">
                </div>
            </div>

            <div class="row">
            <div class="mb-3 col-xl-6 col-sm-12">
                <label class="form-label">Waktu Pelaksanaan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="calendar"></i></span>
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control" name="jadwal" value="{{($item->Jadwal)}}">
                </div>
            </div>
            <div class="mb-3 col-xl-6 col-sm-12">
                <label class="form-label">Mekanisme Pelaksanan</label>
                <div class="input-group">
                    <select style="font-size:14px" required class="form-control select col-sm-12" name="mekanisme">
                        <option value="">Pilih</option>
                            <option @if($item->Mekanisme=='Swakelola') selected @endif value="Swakelola">Swakelola</option>
                            <option @if($item->Mekanisme=='PL Penyedia Jasa') selected @endif value="PL Penyedia Jasa">PL Penyedia Jasa</option>
                            <option @if($item->Mekanisme=='Lelang') selected @endif value="Lelang">Lelang</option>
                    </select>

                </div>
            </div>
            </div>

            <div class="row">

            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Orang</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="user"></i></span>
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number orang" value="{{($item->JumlahOrang)}}" name="jumlahorang">
                </div>
            </div>
            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Hari</label>
                <div class="input-group">
                    <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number hari" name="jumlahhari" value="{{($item->JumlahHari)}}">
                    <span class="input-group-text">Hari</span>
                </div>
            </div>
            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Orang x Hari</label>
                <div class="input-group">
                    <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control oranghari number" id="oranghari" value="{{($item->JumlahOrangHari)}}"name="jumlahoranghari">
                </div>
            </div>
            </div>

            <div class="row">
                <div class="mb-3 col-xl-3 col-sm-12">
                    <label class="form-label">Upah Harian</label>
                    <div class="input-group">
                        {{-- <span class="input-group-text"><i data-feather="credit-card"></i></span> --}}
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number upah" name="upahharian" value="{{($item->UpahHarian)}}">
                    </div>
                </div>
                <div class="mb-3 col-xl-5 col-sm-12">
                    <label class="form-label">Total Biaya Upah</label>
                    <div class="input-group">
                        <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control biayaupah number" id="biayaupah" name="biayaupah" value="{{($item->TotalBiayaUpah)}}">
                        <span class="input-group-text persenupah" style="font-size:14px;">{{Persen($item->PersenBiayaUpah)}}%</span>
                    </div>
                </div>
                <div class="mb-3 col-xl-4 col-sm-12">
                    <label class="form-label">Total Biaya Lain</label>
                    <div class="input-group">
                        {{-- <span class="input-group-text"><i data-feather="credit-card"></i></span> --}}
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number totalbiayalain" name="totalbiayalain" value="{{($item->TotalBiayaLain)}}">
                    </div>
                </div>
            </div>
                <div class="row">
                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Jumlah Realisasi Kegiatan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="credit-card"></i></span>
                        <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number jumlahRealisasi output" id="jumlahRealisasi" name="jumlahRealisasi" value="{{($item->TotalPagu)}}">
                    </div>
                </div>
                <div class="mb-3 col-xl-6 col-sm-12">
                    <label class="form-label">Sisa Anggaran Kegiatan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="credit-card"></i></span>
                        <input readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number sisaRealisasi" id="sisaRealisasi" name="sisaRealisasi">
                    </div>
                </div>
                </div>

                <div class="row mt-3">
                    <div class="col-8"><h6 class="">RINCIAN PEMBAYARAN</h6></div>
                    <div title="Tambah No SP2D" class="col-4 text-primary text-end"><a class="addsp2d" href="#"><i data-feather="plus-circle"></i></a></div>
                </div>
                <hr class="mt-2 mb-2">


                <div class="row">
                    @foreach ($item->sppd as $sppd)
                    <input type="hidden" name="idtable[]" value="{{$sppd->idtable}}">
                    <div class="mb-3 col-sm-5">
                        <label class="form-label">Tanggal SP2D</label>
                        <div class="input-group">
                            <span class="input-group-text"><i data-feather="calendar"></i></span>
                            <input required name="tanggal[]" style="font-size:14px; background-color:#fff;" readonly class="datepicker-here form-control" value="{{\Carbon\Carbon::parse($sppd->tanggal)->isoFormat('DD/MM/YYYY')}}" type="text" data-language="en">
                        </div>
                    </div>
                    <div class="mb-3 col-sm-7">
                        <label class="form-label">Nomor SP2D</label>
                        <div class="input-group">
                            <span class="input-group-text"><i data-feather="file-text"></i></span>
                            <input maxlength="25" required style="font-size:14px" type="text" class="form-control number" value="{{$sppd->nosppd}}" name="nosp2d[]">
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="newDatasp2d"></div>

                <div class="mb-3">
                    <label class="form-label">Keterangan / Kendala Pelaksanaan</label>
                    <div class="input-group">
                        <textarea style="font-size:14px;" class='form-control' name='keterangan' rows='3'>{{$item->Keterangan}}</textarea>
                    </div>
                </div>


         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-success validasi" type="button">Validasi</button>
            <button class="btn btn-primary submit" type="submit">Simpan</button>
         </div>

    </form>
    @break

    @case('editKegiatanPadatKarya')
    <form id="myform" action="{{route('satker/post/data')}}" method="POST">
        @csrf
        <input type="hidden" name="type" value="editpadatkarya">
        <input type="hidden" name="id" value="0000.000.000000.0.0.0000000">
        <input type="hidden" name="guid" value="{{$dataDB->guid}}">

        <div class="modal-header">
            <h5 class="modal-title">{{$titleHead}}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <div class="row">
                <div class="col-8"><h6 class="">KEGIATAN</h6></div>
                <div title="Tambah Akun Kegiatan" class="col-4 text-primary text-end"><a class="addakun" href="#"><i data-feather="plus-circle"></i></a></div>
            </div>
            <hr class="mt-2 mb-3">
            {{-- @dd($akunDB) --}}
            @foreach ($akunDB as $akunkegiatan)
            <input value="{{$akunkegiatan->idtable}}" type="hidden" name="idtable[]">

            {{-- {{$akunkegiatan->Id}} --}}
            <div class="row">
                <div class="mb-3 col-xl-7 col-sm-12">
                    <label class="form-label">Akun Belanja</label>
                    <select style="font-size:14px" required class="form-control select col-sm-12 kabupaten" name="akun[]">
                        <option value="">Pilih Akun Belanja</option>
                        @foreach ($akun as $item)
                        <option @if($item->Id==$akunkegiatan->Id) selected @endif value="{{$item->Id}}.{{$item->Kewenangan}}.{{$item->Program}}">{{$item->Id}} : {{$item->keterangan->NamaAkun}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 col-xl-5 col-sm-12">
                    <label class="form-label">Jumlah Pagu Kegiatan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-feather="credit-card"></i></span>
                        <input value="{{RP($akunkegiatan->Amount)}}" required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number pagukegiatan" name="pagukegiatan[]" value="">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Kegiatan Padat Karya</label>
                <div class="input-group">
                    <input value="{{$akunkegiatan->Uraian}}" required style="text-align:left; font-size:14px; padding:9px !important;" type="text" class="form-control" name="kegiatan[]">
                </div>
            </div>
            @endforeach
            <div class="newData"></div>
            <h6 class="mt-4">RINCIAN KEGIATAN</h6>
            <hr class="mt-2 mb-3">
            <div class="mb-3">
                <label class="form-label">Total Pagu</label>
                <div class="input-group">
                    <input value="{{RP($dataDB->TotalPagu)}}" readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control totalpagu number" name="totalpagu">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Lokasi Kegiatan</label>
                <select style="font-size:14px" required class="form-control select col-sm-12 kabupaten" name="kdlokasi">
                    <option value="">Pilih Desa/Kecamatan Lokasi</option>
                    @foreach ($lokasi as $item)
                    @if(strlen($item->kode)==5)
                        <option disabled value="{{$item->kode}}">{{$item->Nama}}</option>
                    @elseif(strlen($item->kode)==8)
                        <option disabled value="{{$item->kode}}">Kecamatan {{$item->Nama}}</option>
                    @elseif(strlen($item->kode)>8)
                        <option @if($item->kode==$dataDB->KdLokasi) selected @endif value="{{$item->kode}}">Kelurahan / Desa {{$item->Nama}}</option>
                    @endif
                    @endforeach
                </select>
            </div>


            <div class="row">
            <div class="mb-3 col-xl-6 col-sm-12">
                <label class="form-label">Rencana Waktu Pelaksanaan</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="calendar"></i></span>
                    <input value="{{$dataDB->Jadwal}}" required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control" name="jadwal">
                </div>
            </div>
            <div class="mb-3 col-xl-6 col-sm-12">
                <label class="form-label">Mekanisme Pelaksanan</label>
                <div class="input-group">
                    <select style="font-size:14px" required class="form-control select col-sm-12" name="mekanisme">
                        <option value="">Pilih</option>
                            <option @if($dataDB->Mekanisme=="Swakelola") selected @endif value="Swakelola">Swakelola</option>
                            <option @if($dataDB->Mekanisme=="PL Penyedia Jasa") selected @endif value="PL Penyedia Jasa">PL Penyedia Jasa</option>
                            <option @if($dataDB->Mekanisme=="Lelang") selected @endif value="Lelang">Lelang</option>
                    </select>

                </div>
            </div>
            </div>

            <div class="row">

            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Orang</label>
                <div class="input-group">
                    <span class="input-group-text"><i data-feather="user"></i></span>
                    <input value="{{$dataDB->JumlahOrang}}" required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number orang" name="jumlahorang">
                </div>
            </div>
            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Hari</label>
                <div class="input-group">
                    <input value="{{$dataDB->JumlahHari}}" required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number hari" name="jumlahhari">
                    <span class="input-group-text">Hari</span>
                </div>
            </div>
            <div class="mb-3 col-xl-4 col-sm-12">
                <label class="form-label">Jumlah Orang x Hari</label>
                <div class="input-group">
                    <input value="{{$dataDB->JumlahOrangHari}}" readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control oranghari number" id="oranghari" name="jumlahoranghari">
                </div>
            </div>
            </div>

            <div class="row">
                <div class="mb-3 col-xl-3 col-sm-12">
                    <label class="form-label">Upah Harian</label>
                    <div class="input-group">
                        {{-- <span class="input-group-text"><i data-feather="credit-card"></i></span> --}}
                        <input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number upah" name="upahharian" value="150.000">
                    </div>
                </div>
                <div class="mb-3 col-xl-5 col-sm-12">
                    <label class="form-label">Total Biaya Upah</label>
                    <div class="input-group">
                        <input  value="{{RP($dataDB->TotalBiayaUpah)}}" readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control biayaupah number" id="biayaupah" name="biayaupah">
                        <span class="input-group-text persenupah" style="font-size:14px;">{{Persen($item->PersenBiayaUpah)}}</span>
                    </div>
                </div>
                <div class="mb-3 col-xl-4 col-sm-12">
                    <label class="form-label">Total Biaya Lain</label>
                    <div class="input-group">
                        {{-- <span class="input-group-text"><i data-feather="credit-card"></i></span> --}}
                        <input  value="{{RP($dataDB->TotalBiayaLain)}}" readonly style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control biayalain number" id="biayalain" name="biayalain">
                    </div>
                </div>
            </div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-success validasi" type="button">Validasi</button>
            <button class="btn btn-primary submit" type="submit">Simpan</button>
         </div>

    </form>
    @break


    @case('showpaguakun')
    @break

@endswitch


