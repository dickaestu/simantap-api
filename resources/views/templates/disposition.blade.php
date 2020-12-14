<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disposisi Surat</title>
    <style>
        .text-center{
            text-align: center;
        }
        .center{
            margin: 5px auto;
        }
         td, th {
        border: 1px solid #2c2c2c;
        text-align: left;
        padding: 8px;
        }

        th {
            width: 15%;
            font-size: 14px
        }
    </style>
</head>
<body>
    <div>
        <img class="" src="files/kop_surat/kop-surat-dummy.jpg"  width="100%" alt="Kop Surat">
    </div>

    <hr>

    <div>
         <h2 class="text-center">DISPOSISI SURAT</h2>
    </div>

    <div>
        <table style="width: 100%;  border-collapse: collapse;">
            <tr>
                <th>
                    Surat Dari
                </th>
                <td>
                    {{$disposition->disposable->sumber_surat}}
                </td>
                <th>
                    Diterima Tanggal
                </th>
                <td>
                    {{\Carbon\Carbon::create($disposition->disposable->tanggal_terima)->format('d F Y')}}
                </td>
            </tr>
            <tr>
                <th>
                    Tanggal Surat
                </th>
                <td>
                       {{\Carbon\Carbon::create($disposition->disposable->tanggal_surat)->format('d F Y')}}
                </td>
                <th>
                    Nomor Agenda
                </th>
                <td>
                    {{$disposition->disposable->no_agenda}}
                </td>
            </tr>
            <tr>
                <th>
                    Nomor Surat
                </th>
                <td>
                    {{$disposition->disposable->no_surat}}
                </td>
                <th>
                    Disposisi ke
                </th>
                <td>
                    {{$disposition->sector->nama_bagian}}
                </td>
            </tr>
            <tr>
                <th>
                    Tujuan Surat
                </th>
                <td>
                    {{$disposition->disposable->tujuan_surat}}
                </td>
                <th>
                    Tanggal Disposisi
                </th>
                <td>
                    {{$disposition->created_at->format('d F Y')}}
                </td>
            </tr>
            <tr>
                <th>
                    Perihal
                </th>
                <td >
                    {{$disposition->disposable->perihal}}
                </td>
                <th>
                    Keterangan
                </th>
                <td >
                    {{$disposition->disposable->keterangan}}
                </td>
            </tr>
        </table>

        <table style="width: 100%;  border-collapse: collapse; margin-top:50px">
            <tr>
                <th>Tembusan:</th>
                <th>Isi Disposisi:</th>
            </tr>
            <tr>
                <td>
                   <ul>
                    @foreach ($disposition->sections as $section)
                        <li>{{$section->nama_bagian}}</li>
                    @endforeach
                   </ul>
                </td>
                <td>{{$disposition->catatan}}</td>
            </tr>
        </table>
    </div>
</body>
</html>