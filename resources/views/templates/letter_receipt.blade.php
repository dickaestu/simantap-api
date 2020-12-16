<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Terima Surat</title>
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
            width: 35%
        }
     
    </style>
</head>
<body>
    <div>
        <img class="" src="files/kop_surat/kop-surat-dummy.jpg"  width="100%" alt="Kop Surat">
    </div>

    {{-- <hr> --}}

    <div>
        <h2 class="text-center">TANDA TERIMA SURAT</h2>
    </div>


        <table style="width: 100%;  border-collapse: collapse;"  >
            <tr>
                <th>
                    Telah terima dari
                </th>
                <td>
                    {{$message->sumber_surat}}
                </td>
            </tr>
            <tr>
                <th>
                    Nomor Surat
                </th>
                <td>
                    {{$message->no_surat}}
                </td>
            </tr>
            <tr>
                <th>
                    Tanggal Surat
                </th>
                <td>
                  
                     {{\Carbon\Carbon::create($message->tanggal_surat)->format('d F Y')}}
                </td>
            </tr>
            <tr>
                <th>
                    Tujuan Surat
                </th>
                <td>
                    {{$message->tujuan_surat}}
                </td>
            </tr>
            <tr>
                <th>
                    Tanggal Terima
                </th>
                <td>
                    {{\Carbon\Carbon::create($message->tanggal_terima)->format('d F Y')}}
                </td>
            </tr>
            <tr>
                <th>
                    Perihal
                </th>
                <td>
                    {{$message->perihal}}
                </td>
            </tr>
        </table>

    <table style="margin-top: 80px; width:100%;">
        <tr>
            <td style="text-align: center; padding:0; border:none">Yang Menyetujui</td>
            <td style="text-align: center; padding:0; border:none">Yang Menyetujui</td>
        </tr>
        <tr>
            <th style="text-align: center; padding-top: 100px; border:none">{{ucwords($bagian[0]['nama_bagian'])}}</th>
            <th style="text-align: center; padding-top: 100px; border:none">{{ ucwords($bagian[1]['nama_bagian']) }}</th>
        </tr>
        <tr>
            <td style="text-align: center; padding:0; padding-top:50px; border:none">Yang Menyetujui</td>
            <td style="text-align: center; padding:0; padding-top:50px; border:none">Yang Menyetujui</td>
        </tr>
        <tr>
            <th style="text-align: center; padding-top: 100px; border:none">{{ucwords($bagian[2]['nama_bagian'])}}</th>
            <th style="text-align: center; padding-top: 100px; border:none">{{ ucwords($bagian[3]['nama_bagian']) }}</th>
        </tr>
    </table>
  


</body>
</html>