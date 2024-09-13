@extends('layouts.main')

@section('title')
     Transfer Rekening
@endsection

@section('judul')
     Transfer Rekening
@endsection

@section('form')
     <style>
          .value_transaksi {
               font-weight: 400
          }
     </style>

     <form id="formLogin">
          <div class="row gy-3 gy-md-4 overflow-hidden">
               <div class="col-12">
                    <label class="form-label">Bank Tujuan <span class="text-danger">*</span></label>
                    <select class="form-select" name="bank_tujuan" id="bank_tujuan" aria-label="Default select example">
                         <option selected disabled>Pilih Bank Tujuan</option>
                         @foreach ($bank as $item)
                              <option value="{{ $item->name }}">{{ $item->name }}</option>
                         @endforeach
                    </select>
               </div>
               <div class="col-12">
                    <label class="form-label">Atas Nama Tujuan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="atasnama_tujuan" id="atasnama_tujuan" placeholder="Masukkan Atas Nama Tujuan">
               </div>
               <div class="col-12">
                    <label class="form-label">Rekening Tujuan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="rekening_tujuan" id="rekening_tujuan" placeholder="Masukkan Atas Nama Tujuan">
               </div>
               <div class="col-12">
                    <label class="form-label">Nilai Transfer <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nilai_transfer" id="nilai_transfer" placeholder="Masukkan Nilai Transfer">
               </div>
               <div class="col-12">
                    <label class="form-label">Bank Pengirim <span class="text-danger">*</span></label>
                    <select class="form-select" name="bank_pengirim" id="bank_pengirim" aria-label="Default select example">
                         <option selected disabled>Pilih Bank Pengirim</option>
                         @foreach ($bank as $item)
                              <option value="{{ $item->name }}">{{ $item->name }}</option>
                         @endforeach
                    </select>
               </div>
               <div class="col-12">
                    <div class="d-grid">
                         <button class="btn btn-lg btn-primary" type="submit">Buat Transaksi</button>
                         <button class="btn btn-lg btn-secondary mt-2" type="button" id="refreshToken">Refresh Token</button>
                    </div>
               </div>
          </div>
     </form>

     <div class="modal fade" id="modalTransaksi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
               <div class="modal-content">
                    <div class="modal-header">
                         <h1 class="modal-title fs-5" id="exampleModalLabel">Transaksi Berhasil</h1>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                         <h6>ID Transaksi : <span class="value_transaksi" id="text_id_transaksi"></span></h6>
                         <h6>Bank Perantara : <span class="value_transaksi" id="text_bank_perantara"></span></h6>
                         <h6>Nilai Transfer : <span class="value_transaksi"id="text_nilai_transfer"></span></h6>
                         <h6>Kode Unik : <span class="value_transaksi" id="text_kode_unik"></span></h6>
                         <h6>Biaya Admin : <span class="value_transaksi" id="text_biaya_admin"></span></h6>
                         <h6>Rekening Perantara: <span class="value_transaksi" id="text_rekening_perantara"></span></h6>
                         <h6>Total Transfer : <span class="value_transaksi" id="text_total_transfer"></span></h6>
                         <h6>Berlaku Hingga : <span class="value_transaksi" id="text_berlaku_hingga"></span></h6>
                    </div>
               </div>
          </div>
     </div>

     <script>
          function createTransfer(data) {
               const token = localStorage.getItem('accessToken');
               $.ajax({
                    type: "POST",
                    url: "{{ route('refreshToken') }}",
                    data: data,
                    headers: {
                         'Authorization': 'Bearer' + token
                    },
                    dataType: "json",
                    success: function(response) {
                         if (response.status == 200) {
                              $('#text_id_transaksi').text(response.id_transaksi);
                              $('#text_bank_perantara').text(response.bank_perantara);
                              $('#text_nilai_transfer').text(response.nilai_transfer);
                              $('#text_kode_unik').text(response.kode_unik);
                              $('#text_biaya_admin').text(response.biaya_admin);
                              $('#text_rekening_perantara').text(response.rekening_perantara);
                              $('#text_total_transfer').text(response.total_transfer);
                              $('#text_berlaku_hingga').text(response.berlaku_hingga);
                              $('#modalTransaksi').modal('show');
                         }
                    }
               });
          }

          function refreshToken() {
               const token = localStorage.getItem('accessToken');
               const refreshToken = localStorage.getItem('refreshToken');

               $.ajax({
                    type: "POST",
                    url: "{{ route('refreshToken') }}",
                    data: {
                         refreshToken
                    },
                    headers: {
                         'Authorization': 'Bearer' + token
                    },
                    dataType: "json",
                    success: function(response) {
                         if (response.status == 200) {

                              localStorage.setItem('accessToken', response.accessToken);
                              localStorage.setItem('refreshToken', response.refreshToken);
                              Swal.fire({
                                   title: response.message,
                                   icon: "success"
                              });
                         }

                    }
               });
          }

          $(document).ready(function() {

               $('#rekening_tujuan').on('input', function() {
                    $(this).val($(this).val().replace(/[^0-9]/g, ''));
               });

               $('#nilai_transfer').on('input', function() {
                    $(this).val($(this).val().replace(/[^0-9]/g, ''));
               });

               $('#formLogin').on('submit', function(event) {
                    event.preventDefault();
                    var bank_tujuan = $('#bank_tujuan').val();
                    var atasnama_tujuan = $('#atasnama_tujuan').val();
                    var nilai_transfer = $('#nilai_transfer').val();
                    var bank_pengirim = $('#bank_pengirim').val();
                    var rekening_tujuan = $('#rekening_tujuan').val();

                    var data = {
                         bank_tujuan,
                         atasnama_tujuan,
                         rekening_tujuan,
                         nilai_transfer,
                         bank_pengirim
                    }

                    createTransfer(data);
               });

               $('#refreshToken').click(function(e) {
                    e.preventDefault();
                    refreshToken();
               });
          });
     </script>
@endsection
