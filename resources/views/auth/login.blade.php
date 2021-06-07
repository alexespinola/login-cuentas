<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  {{-- <link rel="shortcut icon" href="{{ asset('img/uc.ico') }}"> --}}
  <title>{{ config('app.name') , 'login' }}</title>
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

</head>
<body style="background: #1688be; text-align: center; padding: 100px;">
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><img src="https://adminlte.sofse.gob.ar/dist/img/logo_desktop.png"></a>
    </div>
    <br>

    <div class="row">
      <div class="col-xs-4 col-xs-offset-4">
        <div class="panel panel-default" style="padding: 25px;">
          <p class="login-box-msg">Ingresar al sistema</p>

          <form class="form-signin" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fa fa-key"></i> Acceder con el sistema de cuentas</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>


