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
                <th colspan="2" style="text-align: center">SUBBAG</th>
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
                   <p>{{ $subbag_disposition->isi_disposisi }}</p>
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

     <div class="page-break" style=" page-break-after: always;"></div>
     {{-- KASUBAG PDF --}}

      <div style="display: inline-block">
        <div class="header-left" style="border-bottom: 1px solid #000;">
            <h5 style="margin-bottom: 5px">BIRO SDM POLDA METRO JAYA  <br> {{ $kasubag->subSector->jenis_bagian->nama_bagian }}</h5>
            
        </div>
    </div>
    <div class="header-right" style="float: right">
        <p style="">Klasifikasi : {{ ucwords($kasubag->disposable->klasifikasi) }}</p>
    </div>

    <div style="margin-top: 40px">
         <h4 class="text-center">LEMBAR DISPOSISI</h4>
    </div>

    <div>
        <table style="width: 100%;  border-collapse: collapse;">
            <tr>
                <td style="border: none; font-size: 13px">No.Agenda {{ $kasubag->subSector->jenis_bagian->nama_bagian }}: {{ $kasubag->disposable->no_agenda }}</td>
                <td style="border: none; font-size: 13px">Diterima Tgl: {{$kasubag->created_at->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <th class="text-center">
                    CATATAN {{ strtoupper($kasubag->user_created_by->bagian->nama) }}
                </th>
                <th class="text-center">
                    ISI DISPOSISI
                </th>
            </tr>
          
            <tr>
                <td style="padding: 5px">
                    <table style="width:100%">
                        <tr>
                            <td style="padding-left:0; border:none;  vertical-align:top">Surat Dari</td>
                            <td style="padding-left:0; border:none;  vertical-align:top">:</td>
                            <td style="padding-left:0; border:none;  vertical-align:top">{{ $kasubag->disposable->sumber_surat }}</td>
                        </tr>
                      
                       
                        <tr class="">
                            <td style="padding-left:0; border:none; vertical-align:top">Perihal</td>
                            <td style="padding-left:0; border:none; vertical-align:top">:</td>
                            <td style="padding-left:0; border:none ; vertical-align:top"> {{ $kasubag->disposable->perihal }}</td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: middle; text-align:center" rowspan="3">
                  <p>{{ $kasubag->isi_disposisi }}</p>
                </td>
            </tr>
            <tr>
                <td style="font-size: 13px;font-weight:bold" class="text-center">
                    DITERUSKAN KEPADA
                </td>
            </tr>
            <tr>
                <td>
                    <table style="width: 100%">
                        @foreach ($kasubagSubSections as $kasub)
                        <tr>
                            <td style="padding-top:0; border:none;  width:10%"> <input {{ $kasubag->kepada == $kasub->id ? 'checked' :'' }} type="checkbox" /></td>
                            <td style="padding-top:0; border:none; padding-left:0">{{ $kasub->nama }}</td>
                        </tr>
                        @endforeach
                    </table>
                        
                   
                </td>
            </tr>
           <tr>
         
            <td style="vertical-align: top" colspan="2">
                    <p>Catatan:</p> 
                    <p>{{ $kasubag->catatan }}</p>
            </td>
         
           </tr>
      
        </table>

    </div>

     <div class="page-break" style=" page-break-after: always;"></div>

    {{-- KABAG PDF --}}
    
       <div style="display: inline-block">
        <div class="header-left" style="border-bottom: 1px solid #000;">
            <h5 style="margin-bottom: 5px">KEPOLISIAN DAERAH METRO JAYA <br> BIRO SUMBER DAYA MANUSIA</h5>
            
        </div>
    </div>
    <div class="header-right" style="float: right">
        <p style="">Klasifikasi : {{ ucwords($kabag->disposable->klasifikasi) }}</p>
    </div>

    <div style="margin-top: 40px">
         <h4 class="text-center">LEMBAR DISPOSISI</h4>
    </div>

    <div>
        <table style="width: 100%;  border-collapse: collapse;">
            <tr>
                <td style="border: none; font-size: 13px">No.Agenda: {{ $kabag->disposable->no_agenda }}</td>
                <td style="border: none; font-size: 13px">Diterima Tgl: {{ \Carbon\Carbon::create($kabag->disposable->tanggal_terima)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <th class="text-center">
                    CATATAN
                </th>
                <th class="text-center">
                    ISI DISPOSISI
                </th>
            </tr>
            <tr>
                <td style="font-size: 13px;font-weight:bold">
                    Yth. Karo SDM
                </td>
                <td>
                
                </td>
            </tr>
            <tr>
                <td style="padding: 5px">
                    <table style="width:100%">
                        <tr>
                            <td style="padding-left:0; border:none;  vertical-align:top">Surat Dari</td>
                            <td style="padding-left:0; border:none;  vertical-align:top">:</td>
                            <td style="padding-left:0; border:none;  vertical-align:top">{{ $kabag->disposable->sumber_surat }}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:0; border:none;  vertical-align:top">Nomor</td>
                            <td style="padding-left:0; border:none;  vertical-align:top">:</td>
                            <td style="padding-left:0; border:none;  vertical-align:top">{{ $kabag->disposable->no_surat }}</td>
                        </tr>
                        <tr>
                            <td style="padding-left:0; border:none;  vertical-align:top">Tanggal</td>
                            <td style="padding-left:0; border:none;  vertical-align:top">:</td>
                            <td style="padding-left:0; border:none;  vertical-align:top"> {{ \Carbon\Carbon::create($kabag->disposable->tanggal_surat)->format('d-m-Y') }}</td>
                        </tr>
                        <tr class="">
                            <td style="padding-left:0; border:none; vertical-align:top">Perihal</td>
                            <td style="padding-left:0; border:none; vertical-align:top">:</td>
                            <td style="padding-left:0; border:none ; vertical-align:top"> {{ $kabag->disposable->perihal }}</td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: middle; text-align:center" rowspan="4">
                  <p>{{ $kabag->isi_disposisi }}</p>
                </td>
            </tr>
            <tr>
                <td style="font-size: 13px;font-weight:bold" class="text-center">
                    DITERUSKAN
                </td>
            </tr>
            <tr>
                <td  class="">
                    Kepada Yth:
                </td>
            </tr>
            <tr>
                <td>
                    <table style="width: 100%">
                        @foreach ($kabagSubSections as $kabagSub)
                        <tr>
                            <td style="padding-top:0; border:none;  width:10%"> <input {{ $kabag->kepada == $kabagSub->id ? 'checked' :'' }} type="checkbox" /></td>
                            <td style="padding-top:0; border:none; padding-left:0">{{ $kabagSub->nama }}</td>
                        </tr>
                        @endforeach
                    </table>
                        
                   
                </td>
            </tr>
           <tr>
         
            <td style="vertical-align: top" colspan="2">
                    <p>Catatan:</p> 
                    <p>{{ $kabag->catatan }}</p>
            </td>
         
           </tr>
      
        </table>

    </div>
</body>
</html>