<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MONIRA : MONITORING INFORMASI DAN REALISASI ANGGARAN</title>

  <link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}">
  <meta name="csrf-token" content="<?= csrf_token() ?>" />
  <meta name="csrf-param" content="_token" />
  <style>
      body {
          text-align: center;
          font-size: 9px;
          font-family: Arial, Helvetica, sans-serif;
      }


      @page {
            padding-bottom:34px;
        }

        .pagenum:before {
        content: counter(page);
    }
        footer {
            padding-top:10px;
                position: fixed;
                bottom: 1cm;
                left: 0cm;
                right: 0cm;
                height: 0cm;
                text-align:center;
            }

      #table {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        page-break-inside: always;
        }

        #table td, #table th {
        border: 1px solid #ddd;
        padding: 8px;
        }

        #table tr:nth-child(even){background-color: #f2f2f2;}

        #table tr:hover {background-color: #ddd;}

        #table th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
        }
    h3,h4 {
        text-transform: uppercase;
    }
    img {
        width: 100px;
        height: 100px;
        border: 0px solid #fff;
        border-radius: 999px;
        margin-bottom: 10px;
        float: right;
        margin-top: 46px;
    }
    .left {
        text-align: left;
    }


  </style>
  </head>
  <body>

    <div class="container-default">

    <h3>Daftar Pejabat Perbendaharaan <br>Kantor {{Auth::user()->satker->NamaSatuanKerja}} <br>DIREKTORAT JENDERAL PERHUBUNGAN LAUT <br>Tahun Anggaran {{$setyear}}</h3>


      @foreach($item as $data)
      <img src="{{ asset('storage/'.$data->profile->foto)}}" alt="img" class="img">

      <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-title text-c profile-pegawai">
                <h4 class="left">{{$data->refJabatan->keterangan}}</h4>
            </div>
            <div class="panel-body">

          <!-- Start Profile Widget -->
          <table id="table">
            <tbody>
              <tr>
                <td width="30%">Nama</td>
                <td>{{$data->profile->nama}}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>{{$data->profile->nip}}</td>
            </tr>
            <tr>
                <td>Pangkat/Golongan</td>
                <td>@if(isset($data->profile->pangkat)) {{$data->profile->pangkat}} ({{$data->profile->golongan}}) @endif</td>
            </tr>
            <tr>
                <td>Pendidikan</td>
                <td>{{$data->profile->pendidikan_terakhir}}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{$data->profile->email}}</td>
            </tr>
            <tr>
                <td>Telepon</td>
                <td>{{$data->profile->telepon}}</td>
            </tr>
            <tr>
                <td>Nomor SK Jabatan</td>
                <td>{{$data->detiljabatan->notmt_jabatan ?? ''}}</td>
            </tr>
            <tr>
                <td>Tanggal SK Jabatan</td>
                <td>@if(isset($data->detiljabatan->tmt_jabatan)) {{\Carbon\Carbon::parse($data->detiljabatan->tmt_jabatan)->isoFormat('D MMMM Y') }} @endif</td>
            </tr>
            <tr>
                <td>Lama Menjabat</td>
                <td>@if(isset($data->detiljabatan->tmt_jabatan)){{\Carbon\Carbon::parse($data->detiljabatan->tmt_jabatan)->diff(\Carbon\Carbon::now())->format('%y thn %m bln')}} @endif
                </td>
            </tr>


            <tr>
                <td>Kantor Asal</td>
                <td>{{$data->profile->kantor}}</td>
            </tr>
            <tr>
                <td>Sertifikat</td>
                <td>
                    @if(!empty($data->bnt))
                    <div class="ribbon ribbon-bookmark ribbon-right ribbon-success">{{$data->bnt->no_bnt}}</div>
                    @endif
                    @if(!empty($data->barjas))
                    <div class="ribbon ribbon-bookmark ribbon-right ribbon-success">Barang dan Jasa : {{$data->barjas->nomor_sertifikat}}</div>
                    @endif

                </td>
            </tr>
            <tr>
                <td>Keterangan </td>
                <td>{{$data->detiljabatan->keterangan_pejabat ?? ''}}</td>
            </tr>
            </tbody>
          </table>

          </div>

        </div>
          <!-- End Profile Widget -->
      </div>
      @endforeach
    </div>


    <footer>
        <img width="" src="{{public_path('assets/images/logo/logo-icon.png')}}" alt="Monira"><br>
        MONIRA : Monitoring Informasi dan Realisasi Anggaran <br>
        Direktorat Jenderal Perhubungan Laut <br>Tahun Anggaran {{date('Y')}}
    </footer>

</body>
</html>
