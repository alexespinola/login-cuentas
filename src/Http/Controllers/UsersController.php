<?php

namespace loginCuentas\Http\Controllers;

use Illuminate\Http\Request;
use loginCuentas\Helpers\OAuth2CuentasHelper;
use Exception;
use Redirect;
use Session;
use Config;
use DB;


class UsersController extends Controller {

  /** muesta listado de usuarios */
  public function index(Request $request) {
    try {
      $usersCuentas = OAuth2CuentasHelper::getUsers();
      $usersDB = DB::table('users')->get();
      //define el artributo sync si esta en cuentas y en DB = true sino = false
      foreach ($usersCuentas->userlist as $user) {
        if($usersDB->where('id', $user->oauth_id)->count()) {
          $user->sync = true;
          $key = $usersDB->search(function($item) use($user) {
            return $item->id == $user->oauth_id;
          });
          $usersDB->pull($key);
        }
        else {
          $user->sync = false;
        }
      }
      //datos para la vista
      $data = ['usersCuentas'=>$usersCuentas->userlist, 'usersDB'=>$usersDB];
      return view('loginCuentas::users.index')->with($data);
    }
    catch (Exception $e){
      throw new Exception($e);
    }
  }

  /** asocia un nuevo usuario al ambiente del sistema en cuentas y lo crea en la DB del sistema */
  public function store(Request $request) {
    try {
      $email = $request->email;
      // valida que no exista
      if (DB::table('users')->where('email',$email)->first()) {
        throw new Exception('El usuario ya se registró en el sistema');
      }

      // Asocia el user al ambiente del sistema en cuentas 
      $res = OAuth2CuentasHelper::asociate($email);
      if ($res->code != 200) throw new Exception($res->data);

      //creoa el usuario en la DB del sistema
      DB::insert('insert into users (id, name, email, password) values (?, ?, ?, ?)', 
      [
        $res->data->oauth_id, 
        $res->data->first_name.' '.$res->data->last_name, 
        $res->data->email,
        bcrypt('123456')
      ]);

      $request->session()->flash('message', 'Usuario '.$res->data->first_name.' '.$res->data->last_name.' asociado con éxito.');
      $request->session()->flash('alert-class', 'alert-success');
      return redirect('users');
    }
    catch (Exception $e){
      $request->session()->flash('message', $e->getMessage());
      $request->session()->flash('alert-class', 'alert-danger');
      return redirect('/users');
    }
  }

  /** sincroniuza un usuario del sistema con el ambiente de cuentas del sistema */
  public function syncUser(Request $request) {
    try {
      $user = unserialize($request->item);

      //si existe en cuentas pero no en la DB del sitema
      if($request->estado == 'enCuentas'){
        //creoa el usuario en la DB del sistema
        DB::insert('insert into users (id, name, email, password) values (?, ?, ?, ?)', 
        [
          $user->oauth_id, 
          $user->first_name.' '.$user->last_name, 
          $user->email,
          bcrypt('123456')
        ]);

        $request->session()->flash('message', 'Usuario '.$user->first_name.' '.$user->last_name.' creado con éxito');
        $request->session()->flash('alert-class', 'alert-success');
      }

      // si existe en la DB del sistema pero no en cuentas
      if($request->estado == 'enSistema'){
        // Asocia el user al ambiente del sistema en cuentas 
        $res = OAuth2CuentasHelper::asociate($user->email);
        if ($res->code != 200) throw new Exception($res->data);
        
        $request->session()->flash('message', 'Usuario '.$res->data->first_name.' '.$res->data->last_name.' asociado con éxito');
        $request->session()->flash('alert-class', 'alert-success');
      }

      return redirect('/users');
    } 
    catch (Exception $e) {
      $request->session()->flash('message', $e->getMessage());
      $request->session()->flash('alert-class', 'alert-danger');
      return redirect('/users');
    }
  }

}
