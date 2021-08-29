@switch($what)
    @case('compose')
    <form id="myform" action="{{route(Auth::user()->ba.'/post/message')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Tulis Pesan</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <div class="mb-1">
            <label class="form-label mt-3" for="validationCustom02">Jenis Pesan</label>
            <select required class="select col-sm-12 type" name="type">
                <option value="">Pilih</option>
                <option value="all">Broadcast</option>
                <option value="region">Propinsi</option>
                <option value="unit">Satker</option>
            </select>
            </div>
            <div class="append_data"></div>
            <label class="form-label mt-1" for="validationCustom02">Judul</label>
            <input class="form-control" type="text" name="subject" required="" data-bs-original-title="" title="">
            <label class="form-label mt-1" for="validationCustom02">Pesan</label>
            <textarea id="message" name="message" cols="30" rows="10"></textarea>
            <div class="mt-2">
                <label class="form-label">Upload File</label>
                <input multiple name="file[]" class="form-control mb-4 col-sm-8" type="file" aria-label="file" >
            </div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary submitPrognosa" type="submit">Simpan</button>
         </div>
    </form>

        @break
        @case('region')
        <div class="mb-1">
            <label class="form-label mt-3" for="validationCustom02">Daftar Propinsi</label>
            <select required class="select col-sm-12" name="wilayah[]" multiple="multiple">
                <option value="">Pilih</option>
        @foreach ($data as $item)
            <option value="{{$item->KodeWilayah}}">{{ucwords(strtolower($item->WilayahName))}}</option>
        @endforeach
            </select>
        </div>
        @break

        @case('unit')
        <div class="mb-1">
            <label class="form-label mt-3" for="validationCustom02">Daftar Satker</label>
            <select required class="select col-sm-12" name="satker[]" multiple="multiple">
                <option value="">Pilih</option>
        @foreach ($data as $item)
            <option value="{{$item->KodeSatker}}">{{ucwords(strtolower($item->NamaSatuanKerja))}}</option>
        @endforeach
            </select>
        </div>
        @break
        @case('filter')
        <form id="myformx" action="{{route(Auth::user()->ba.'/rangking/filter')}}" method="get">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Filter Ranking</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <div class="row">
                <div class="col-6 mb-3">
                <label class="form-label mt-3">Batas Atas</label>
                <input class="form-control filter" type="text" name="top" required="">
                </div>
                <div class="col-6 mb-3">
                <label class="form-label mt-3">Batas Bawah</label>
                <input class="form-control filter" type="text" name="bottom" required="">
                </div>
                 </div>
             </div>
             <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary submitPrognosa" type="submit">Proses</button>
             </div>
        </form>
        @break

    @default

@endswitch

