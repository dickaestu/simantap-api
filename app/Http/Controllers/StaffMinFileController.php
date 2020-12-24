<?php

namespace App\Http\Controllers;

use App\Models\Disposition;
use App\Models\StaffminFile;
use App\Models\SubBagian;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class StaffMinFileController extends Controller
{
    public function store(Request $request, $id)
    {
        $user = JWTAuth::user();
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,xlsx,xls,pdf,doc,docx|max:5000',
            'catatan' => 'nullable',
        ]);

        $status = "error";
        $message = "";
        $data = null;
        $code = 400;

        if ($validator->fails()) {
            $errors = $validator->errors();
            $message = $errors;
        } else {
            $file = $request->file('file');

            $fileName = now()->toDateString() . '_' . $file->getClientOriginalName();
            $file->move('files/staff_min/', $fileName);
            // $surat_masuk = SuratMasuk::findOrFail($id);

            $staffmin_file = StaffminFile::create([
                'surat_masuk_id' => $id,
                'catatan' => $request->catatan,
                'file' => $fileName,
                'created_by' => $user->id
            ]);

            $staffmin_file->history()->create([
                'status' => $user->name  . ' telah mengupload hasil kerjaan',
                'surat_masuk_id' => $id
            ]);
            if ($staffmin_file) {
                $staffmin_file->surat_masuk->update([
                    'status' => 6
                ]);
                $status = "success";
                $message = "Data berhasil dibuat";
                $data = $staffmin_file;
                $code = 200;
            } else {
                $message = 'Failed';
            }
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
