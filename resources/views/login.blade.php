@extends('layouts.main')

@section('title')
     Selamat Datang
@endsection

@section('judul')
     Log in
@endsection

@section('form')
     <form id="formLogin">
          <div class="row gy-3 gy-md-4 overflow-hidden">
               <div class="col-12">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
               </div>
               <div class="col-12">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="password" id="password" value="" required>
               </div>
               <div class="col-12">
                    <div class="d-grid">
                         <button class="btn btn-lg btn-primary" type="submit">Log in now</button>
                    </div>
               </div>
          </div>
     </form>

     <script>
          function login(email, password) {
               $.ajax({
                    type: "POST",
                    url: "{{ route('doLogin') }}",
                    headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                         email: email,
                         password: password,
                    },
                    dataType: "json",
                    success: function(response) {
                         if (response.status === 200) {
                              localStorage.setItem('accessToken', response.accessToken);
                              localStorage.setItem('refreshToken', response.refreshToken);
                              window.location.href = '/transfer';
                         }
                    }
               });
          }

          $(document).ready(function() {
               $('#formLogin').on('submit', function(event) {
                    event.preventDefault();
                    var email = $('#email').val();
                    var password = $('#password').val();
                    login(email, password)
               });
          });
     </script>
@endsection
