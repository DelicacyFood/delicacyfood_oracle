@extends('layouts.master-auth')
@section('title', 'Jumlah Order')
@section('content')
<!-- Main Content -->
<div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <img src="/assets/img/delicacy-login.png" alt="logo" width="100" class="shadow-light rounded-circle">
            </div>

            <div class="card card-primary">
              <div class="card-header"><h4>Jumlah Order </h4></div>

              <div class="card-body">
                <p class="text-muted">Masukkan jumlah order untuk menu </p>
                <form method="POST">
                  <div class="form-group">
                    <label for="jumlah_order">Jumlah Order</label>
                    <input id="jumlah_order" type="text" class="form-control" name="jumlah_order" tabindex="1" required autofocus>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Konfirmasi
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <div class="mt-5 text-muted text-center">
              <a href="{{route('dashboard')}}">Back to previous page</a>
            </div>
            <div class="simple-footer">Copyright &copy; Delicacy Food 2021</div>
          </div>
        </div>
      </div>
    </section>
  </div>

@endsection