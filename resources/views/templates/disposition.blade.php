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
    </style>
</head>
<body>
    <div>
        <img class="" src="files/kop_surat/kop-surat-dummy.jpg" height="100px" alt="">
    </div>

    <hr>

    <div>
        <h1 class="text-center"><b><u>Lembar Disposisi</u></b></h1>
    </div>

    <div>
        <table class="center" border="1">
            <tr>
                <td>
                    Surat Dari
                </td>
                <td>
                    {{$disposition->disposable->sumber_surat}}
                </td>
                <td>
                    Diterima Tanggal
                </td>
                <td>
                    {{$disposition->disposable->tanggal_terima}}
                </td>
            </tr>
            <tr>
                <td>
                    Tanggal Surat
                </td>
                <td>
                    {{$disposition->disposable->tanggal_surat}}
                </td>
                <td>
                    Nomor Agenda
                </td>
                <td>
                    {{$disposition->disposable->tanggal_terima}}
                </td>
            </tr>
            <tr>
                <td>
                    Nomor Surat
                </td>
                <td>
                    {{$disposition->disposable->no_surat}}
                </td>
                <td>
                    Disposisi ke
                </td>
                <td>
                    {{$disposition->sector->nama_bagian}}
                </td>
            </tr>
            <tr>
                <td>
                    Tujuan Surat
                </td>
                <td>
                    {{$disposition->disposable->tujuan_surat}}
                </td>
                <td>
                    Tanggal Disposisi
                </td>
                <td>
                    {{$disposition->created_at}}
                </td>
            </tr>
            <tr>
                <td>
                    Perihal
                </td>
                <td colspan="3">
                    {{$disposition->disposable->perihal}}
                </td>
            </tr>
        </table>

        <table class="center" border="1">
            <tr>
                <th>Isi Disposisi:</th>
            </tr>
            <tr>
                <td>{{$disposition->catatan}}</td>
            </tr>
        </table>

        <table class="center" border="1">
            <tr>
                <th>Tembusan:</th>
            </tr>
            @foreach($disposition->sections as $section)
                <tr>
                    <td>{{$section->nama_bagian}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</body>
</html>