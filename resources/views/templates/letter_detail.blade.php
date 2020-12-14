<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Surat</title>
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
            width: 16%;
            font-size: 14px
        }
    </style>
</head>
<body>
    <div>
        <img class="" src="files/kop_surat/kop-surat-dummy.jpg"  width="100%" alt="Kop Surat">
    </div>

    {{-- <hr> --}}

    <div>
        <h2 class="text-center">SURAT MASUK CUSTOM</h2>
    </div>

    <div>
        <table style="width: 100%;  border-collapse: collapse;"  >
            <tr>
                <th>
                    Surat Dari
                </th>
                <td>
                    {{$message->sumber_surat}}
                </td>
                <th>
                    Diterima Tanggal
                </th>
                <td>
                    {{$message->tanggal_terima}}
                </td>
            </tr>
            <tr>
                <th>
                    Tanggal Surat
                </th>
                <td>
                    {{$message->tanggal_surat}}
                </td>
                <th>
                    Nomor Agenda
                </th>
                <td>
                    {{$message->tanggal_terima}}
                </td>
            </tr>
            <tr>
                <th>
                    Nomor Surat
                </th>
                <td>
                    {{$message->no_surat}}
                </td>
                <th>
                    Tujuan Surat
                </th>
                <td>
                    {{$message->tujuan_surat}}
                </td>
            </tr>
            <tr>
                <th>
                    Perihal
                </th>
                <td>
                    {{$message->perihal}}
                </td>
                <th>
                    Keterangan
                </th>
                <td>
                    {{$message->keterangan}}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>