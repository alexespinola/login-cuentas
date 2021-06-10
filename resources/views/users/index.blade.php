@extends('loginCuentas::template')

@section('content')
  <div class="row" style="margin-top: 15px;">
    <div class="col-xs-6">
      <p class="lead primary"> <b>Usuarios del sistema</b></p>
    </div>

    {{-- Form add user --}}
    <div class="col-xs-6 text-right">
      <form action="{{url('users')}}" method="POST">
        @csrf
        <div class="input-group">
          <input type="text" name="email" class="form-control input-sm" required placeholder="Correo Electrónico">
          <div class="input-group-btn">
            <button type="submit" class="btn btn-primary input-sm"> <i class="fa fa-plus"></i> Agregar usuario</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Messages in session  --}}
  @if(Session::has('message'))
    <div class="alert {{ Session::get('alert-class', 'alert-info') }}"> 
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      {{Session::get('message') }}
    </div>
  @endif

  {{-- Users table  --}}
  <table id="usersTable" class="table table-striped table-condensed">
    <thead>
      <tr>
        <th>ID</th>
        <th>Dombre</th>
        <th>E-mail</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($usersCuentas as $item)
        <tr>
          <td>{{$item->oauth_id}}</td>
          <td>{{$item->first_name}} {{$item->last_name}}</td>
          <td>{{$item->email}}</td>
          @if ($item->sync)
            <td><span class="label label-success">Sincronizado</span></td>
          @else
            <td><span class="label label-danger">No existe en el sistema</span></td>
          @endif
          <td>
            @if ($item->sync)
              <a href="#" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Ver</a>
            @else
              <a href="{{url('users-sync')}}?estado=enCuentas&item={{serialize($item)}}" class="btn btn-primary btn-xs" title="sincronizar con cuentas"> Sync </a>
            @endif
          </td>
        </tr>
      @endforeach
      @foreach ($usersDB as $item)
        <tr>
          <td>{{$item->id}}</td>
          <td>{{$item->name}}</td>
          <td>{{$item->email}}</td>
          <td><span class="label label-warning">No asociado a cuentas</span></td>
          <td>
            <a href="{{url('users-sync')}}?estado=enSistema&item={{serialize($item)}}" class="btn btn-primary btn-xs" title="sincronizar con cuentas"> Sync </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection

@section('scripts')
  <script>
    $(document).ready( function () {
      $('#usersTable').DataTable();
    });
  </script>
@endsection

