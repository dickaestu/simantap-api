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
        .content .table-top td {
            border:none;
            font-size: 14px
        }
        
        .disposisi{
            margin-top: 20px
        }
        .disposisi .table-disposisi{
            width: 100%
        }

        .disposisi .table-disposisi th {
            text-align: center;
            border: none
        }
    </style>
</head>
<body>

    <div style="display: inline-block">
        <div class="header-left" style="border-bottom: 1px solid #000;">
            <p style="margin-bottom: 5px, font-size:20px">POLRI DAERAH METRO JAYA <br> DIREKTORAT LALU LINTAS <br>
                Jalan Jenderal Sudirman 55, Jakarta 12190
            </p>
            
        </div>
    </div>
   

    <div style="margin-top: 20px">
         <h4 class="text-center">LEMBAR DISPOSISI</h4>
    </div>

    <div class="content">
        <table class="table-top">
            <tr>
                <td>Kepada</td>
                <td></td> 
                <td>:</td>
                <td>KASI STNK</td>
            </tr>
            <tr>
                <td>Nomor Agenda</td>
                 <td></td> 
                <td>:</td>
                <td>{{ $disposition->disposable->no_agenda }} 
                    <span style="margin-left: 20px"> Diterima Tanggal : {{ \Carbon\Carbon::create($disposition->disposable->tanggal_terima)->format("d-m-Y") }}</span> 
                </td>
          
            </tr>
            <tr>
                <td>Surat Dari</td>
                 <td></td> 
                <td>:</td>
                <td>{{ $disposition->disposable->user_created_by->name }}</td>
            </tr>
            <tr>
                <td>Nomor Surat/tanggal</td>
                 <td></td> 
                <td>:</td>
                <td>{{ $disposition->disposable->no_surat }} / {{ \Carbon\Carbon::create($disposition->disposable->tanggal_surat)->format("d-m-Y") }}</td>
            </tr>
            <tr>
                <td>Perihal</td>
                 <td></td> 
                <td>:</td>
                <td>{{ $disposition->disposable->perihal }}</td>
            </tr>
        </table>
    </div>

    <div class="disposisi">
        <table class="table-disposisi">
            <tr>
                <th colspan="2" style="border-bottom:1px solid #000 !important;border-top:1px solid #000 !important;">
                    ISI DISPOSISI
                </th>
            </tr>
            <tr >
                <th style="border-right:1px solid #000 !important; border-bottom:1px solid #000 !important; ">
                    KASI STNK</th>
                <th style="border-bottom:1px solid #000 !important;"></th>
            </tr>
            <tr>
                <td style="border: none; border-right:1px solid #000 !important">
                    <table style="width: 100%">
                        @foreach ($subSections as $section)
                        <tr>
                            <td style="padding-top:0; border:none;  width:10%"> <input {{ $disposition->kepada == $section->id ? 'checked' :'' }} type="checkbox" /></td>
                            <td style="padding-top:0; border:none; padding-left:0">{{ $section->nama }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td>
                <td style="border: none">
                    {{ $disposition->isi_disposisi }}
                </td>
            </tr>
        </table>
    </div>

   
</body>
</html>