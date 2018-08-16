<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DateTime;

use App\User;
use App\UserContacts;

class AuthController extends Controller
{
  /**
   * Show the profile for the given user.
   *
   * @param  int  $id
   * @return Response
   */
  public function login( Request $request ){
    $data = array();

    $count = User::where('email', '=', $request->get('email'))
                ->where('password', '=', md5($request->get('password')) )->count();

    if($count > 0) {
      $update = User::where('email', '=', $request->get('email'))->update(['isActive' => true, 'last_session' => new DateTime()]);
      $fetch = User::where('email', '=', $request->get('email'))->get();
      // $request->session()->put( "active" , $fetch[0]->email);
      // $request->session()->put( "active_data" , $fetch[0]);

      $data['status'] = true;
      $data['message'] = 'Success.';
      $data['user'] = $fetch[0];
    }else{
      $data['status'] = false;
      $data['message'] = 'Account does not exist.';
    }

    return $data;
  }

  public function logout( Request $request ){
    $data = array();

    $update = User::where('id', '=', $request->get('user_id'))->update(['isActive' => false]);

    if($update > 0) {
      $data['status'] = true;
      $data['message'] = 'Success.';
    }else{
      $data['status'] = false;
      $data['message'] = 'Failed.';
    }

    // $data = $request->session()->flush();
    return $data;
  }

  public function register( Request $request ){
    $data = array();

    $count = User::where('email', '=', $request->get('email'))->count();

    if($count > 0) {
      $data['status'] = false;
      $data['message'] = 'Email already taken.';

      return $data;
    }

    $create = User::create([
                'name' => $request->get('name'),
                'img' => "http://res.cloudinary.com/dwl3yrtx8/image/upload/v1530486141/default-user.png",
                'email' => $request->get('email'),
                'password' => md5($request->get('password')),
            ]);

    if( $create ){
      $all_users = User::orderBy('name', 'asc')->get();

      for( $x = 0; $x < count($all_users); $x++ ){
        for( $y = 0; $y < count($all_users); $y++ ){
          if( $all_users[$x]->id != $all_users[$y]->id ){
            $check = UserContacts::where('user_id', '=', $all_users[$x]->id )->where('contact_id', '=', $all_users[$y]->id )->count();
            
            if( $check == 0 ){
              $contact = UserContacts::create([
                'user_id' => $all_users[$x]->id,
                'contact_id' => $all_users[$y]->id,
                'status' => false,
              ]);
            }
          }
        }
      }

      $data['status'] = true;
      $data['message'] = 'Success.';
    } else {
      $data['status'] = false;
      $data['message'] = 'Failed.';
    }
    return $data;
  }

  public function get_session(Request $request){
    $data = array();
    $data['user'] = $request->session()->get('active_data');
    return $data;
  }

  public function checkSessionStatus(Request $request){
    $data = array();

    if ($request->session()->has('active')) {
      $data['isActive'] = true;
    }else{
      $data['isActive'] = false;
    }
      
    return $data;
  }

}