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
        <table style="width: 100%;  border-collapse: collapse;">
            @if ($disposition->subSector->bagian_id == 2)
            <tr>
                <th colspan="2" style="text-align: center">SUBBAG RENMIN </th>
            </tr>
            @else 
            <tr>
                <th colspan="2" style="text-align: center">SUBBAG {{ strtoupper($subbag) }}</th>
            </tr>
            @endif
          
            <tr>
                <th>NO. AGENDA BINKAR</th>
                <td>{{ $disposition->disposable->no_agenda }}</td>
            </tr>
            <tr>
                <th>TANGGAL TERIMA</th>
                <td>{{ $disposition->created_at->format('d-m-Y') }}</td>
            </tr>
          
            <tr>
               <td colspan="2">
                   <u><h5 style="display: block; text-align:center; ">DISPOSISI KASUBAG</h5></u>
                   <p>Yth. PAUR</p>
                   <p>{{ $disposition->disposable->perihal }}</p>
               </td>
            </tr>
            <tr>
                <td colspan="2">
                       <u><h5 style="display: block; text-align:center; ">DISPOSISI PAUR</h5></u>
                    <table style="width: 100%">
                        <tr>
                            <td style="border: none; width:50%; border-right: 1px solid black">
                            <p>Kepada Yth:</p>
                            <p>{{ $disposition->staffmin->name }}</p>
                            </td>
                            <td style="border: none; width:50%">{{ $disposition->isi_disposisi }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
          
         
      
        </table>

    </div>
</body>
</html>