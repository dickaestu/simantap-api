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
    </style>
</head>
<body>
    <div>
        <img class="" src="files/kop_surat/kop-surat-dummy.jpg" height="100px" alt="">
    </div>

    <hr>

    <div>
        <h1 class="text-center">TANDA TERIMA SURAT</h1>
    </div>

    <div>
        <table class="center" border="1">
            <tr>
                <td>
                    Telah terima dari
                </td>
                <td>
                    {{$message->sumber_surat}}
                </td>
            </tr>
            <tr>
                <td>
                    Nomor Surat
                </td>
                <td>
                    {{$message->no_surat}}
                </td>
            </tr>
            <tr>
                <td>
                    Tanggal Surat
                </td>
                <td>
                    {{$message->tanggal_surat}}
                </td>
            </tr>
            <tr>
                <td>
                    Tujuan Surat
                </td>
                <td>
                    {{$message->tujuan_surat}}
                </td>
            </tr>
            <tr>
                <td>
                    Tanggal Terima
                </td>
                <td>
                    {{$message->tanggal_terima}}
                </td>
            </tr>
            <tr>
                <td>
                    Perihal
                </td>
                <td>
                    {{$message->perihal}}
                </td>
            </tr>
        </table>
    </div>
    <p >yang menyerahkan yang menerima</p>
    <p>{{$message->sumber_surat}} {{$user->name}}</p>
</body>
</html>