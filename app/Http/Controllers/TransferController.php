<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class TransferController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nilai_transfer' => 'required',
                'bank_tujuan' => 'required',
                'rekening_tujuan' => 'required',
                'atasnama_tujuan' => 'required',
                'bank_pengirim' => 'required',
            ], [
                'nilai_transfer' => 'Nilai transfer tidak boleh kosong',
                'bank_tujuan' => 'Bank tujuan tidak boleh kosong',
                'rekening_tujuan' => 'Rekening tujuan tidak boleh kosong',
                'atasnama_tujuan' => 'Atas nama tujuan tidak boleh kosong',
                'bank_pengirim' => 'Bank pengirim tidak boleh kosong',
            ]);

            $id_transaksi = Transaksi::generateIdTransaksi();
            $kode_unik = Transaksi::generateUniqueCode();

            $nilai_transfer = $request->nilai_transfer;
            $nilaiTransferFormat = str_replace('.', '', $nilai_transfer);

            $bank_tujuan = Bank::getBank($request->bank_tujuan);

            if (!$bank_tujuan) {
                throw new \Exception('Bank tujuan tidak ditemukan');
            }

            $bank_pengirim = Bank::getBank($request->bank_pengirim);

            if (!$bank_pengirim) {
                throw new \Exception('Bank pengirim tidak ditemukan');
            }

            $total_transfer = $nilaiTransferFormat + $kode_unik + $bank_pengirim->biaya_admin;

            $berlaku_hingga = Transaksi::getOneDayFromNow();

            $data = [
                'id_transaksi' => $id_transaksi,
                'users_id' => Auth::id(),
                'nilai_transfer' => $nilaiTransferFormat,
                'bank_tujuan' => $bank_tujuan->id,
                'atasnama_tujuan' => $request['atasnama_tujuan'],
                'bank_pengirim' => $bank_pengirim->id,
                'kode_unik' => $kode_unik,
                'biaya_admin' => intval($bank_pengirim->biaya_admin),
                'total_transfer' => $total_transfer,
                'bank_perantara' => $bank_pengirim->id,
                'status' => 'Unpaid',
                'masa_berlaku' => Carbon::parse($berlaku_hingga)->format('Y-m-d H:i:s'),
            ];

            $transaksi = Transaksi::createTransaksi($data);

            if (!$transaksi) {
                throw new \Exception('Transaksi Gagal Dibuat');
            }

            return response()->json([
                'message' => 'Transaksi berhasil dibuat',
                'id_transaksi' => $id_transaksi,
                'nilai_transfer' => $nilaiTransferFormat,
                'kode_unik' => (string) $kode_unik,
                'biaya_admin' => (string) intval($bank_pengirim->biaya_admin),
                'total_transfer' => (string) $total_transfer,
                'bank_perantara' => $bank_pengirim->name,
                'rekening_perantara' => $bank_pengirim->getRekening->no_rekening,
                'berlaku_hingga' => $berlaku_hingga,
                'status' => 200,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function transfer()
    {
        $bank = Bank::all();
        return view('transfer', [
            'bank' => $bank,
        ]);
    }
}
