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
    </style>
</head>
<body>
    <div>
        <img class="" src="files/kop_surat/kop-surat-dummy.jpg" height="100px" alt="">
    </div>

    <hr>

    <div>
        <h1 class="text-center"><b><u>Surat Masuk Custom</u></b></h1>
    </div>

    <div>
        <table class="center" border="1">
            <tr>
                <td>
                    Surat Dari
                </td>
                <td>
                    {{$message->sumber_surat}}
                </td>
                <td>
                    Diterima Tanggal
                </td>
                <td>
                    {{$message->tanggal_terima}}
                </td>
            </tr>
            <tr>
                <td>
                    Tanggal Surat
                </td>
                <td>
                    {{$message->tanggal_surat}}
                </td>
                <td>
                    Nomor Agenda
                </td>
                <td>
                    {{$message->tanggal_terima}}
                </td>
            </tr>
            <tr>
                <td>
                    Nomor Surat
                </td>
                <td>
                    {{$message->no_surat}}
                </td>
                <td>
                    Tujuan Surat
                </td>
                <td>
                    {{$message->tujuan_surat}}
                </td>
            </tr>
            <tr>
                <td>
                    Perihal
                </td>
                <td>
                    {{$message->perihal}}
                </td>
                <td>
                    Keterangan
                </td>
                <td>
                    {{$message->keterangan}}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>