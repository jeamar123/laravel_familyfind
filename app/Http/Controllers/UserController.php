<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DateTime;

use App\User;
use App\UserContacts;

class UserController extends Controller
{
  /**
   * Show the profile for the given user.
   *
   * @param  int  $id
   * @return Response
   */

  public function __construct( ){
    \Cloudinary::config(array(
        "cloud_name" => "dwl3yrtx8",
        "api_key" => "922394114834959",
        "api_secret" => "86jWexq6wG12b1lxTo9E2pwuL6w"
    ));
  }

  public function getUsers(){
    $data = array();
    $data['users'] = User::orderBy('name', 'asc')->get();
    $data['status'] = true;
    return $data;
  }

  public function getUsersbyID( $id ){
    $data = array();
    $data['users'] = User::where('name', $id)->get();
    $data['status'] = true;

    return $data;
  }

  public function getUsersbyName( Request $request ){
    $data = array();

    $data['users'] = User::join('user_contacts', 'users.id', '=', 'user_contacts.contact_id')
                  ->where('user_contacts.user_id', $request->get('user_id'))
                  ->where('users.name', 'LIKE', '%' . $request->get('search') . '%')
                  ->select('users.name', 'users.img', 'user_contacts.*')
                  ->get();

    $data['status'] = true;

    return $data;
  }

  public function getUserContacts( $id ){
    $data = array();
    $users = array();
    $contacts = UserContacts::where('user_id', $id)->where('status', true)->get();
    for( $i = 0; $i < count($contacts); $i++ ){
      $temp_data = User::where('id', $contacts[$i]->contact_id)->get();
      $users[] = $temp_data[0];
    }
    $data['users'] = $users;
    $data['status'] = true;

    return $data;
  }

  public function updateUser( Request $request ){
    $data = array();
    $user_image = $request->get('img');

    $check_current_user = User::where('id', '=', $request->get('id'))->get();
    $count = User::where('email', '=', $request->get('email'))->count();

    if($count > 0) {
      if( $check_current_user[0]->email != $request->get('email') ){
        $data['status'] = false;
        $data['message'] = 'Email already taken.';

        return $data;
      }
    }

    if($request->hasFile('file')) {
      $rules = array(
        'file' => 'required | mimes:jpeg,jpg,png',
      );

      $validator = \Validator::make($request->all(), $rules);

      if($validator->fails()) {
        return array('status' => false, 'message' => 'Invalid file.');
      }

      $file = $request->file('file');

      $image = \Cloudinary\Uploader::upload($file->getPathName());

      $user_image = $image['secure_url'];
    }

    $save_data = array(
        "name" => $request->get('name'),
        "img" => $user_image,
        "email" => $request->get('email'),
      );

    $update = User::where('id', '=', $request->get('id'))->update($save_data);
    $fetch = User::where('id', '=', $request->get('id'))->get();

    if( $update ){
      $data['status'] = true;
      $data['message'] = 'Successfully Updated.';
      $data['user'] = $fetch[0];
    } else {
      $data['status'] = false;
      $data['message'] = 'Failed.';
    }
    return $data;
  }

  public function updateUserPassword( Request $request ){
    $data = array();

    $check_current_user = User::where('id', '=', $request->get('id'))->get();
    $new_password = $request->get('new_password');

    if( $check_current_user[0]->password == md5( $new_password ) ){
      $data['status'] = false;
      $data['message'] = 'Cannot use the same password.';

      return $data;
    }

    $save_data = array(
        "password" => md5($request->get('new_password')),
      );

    $update = User::where('id', '=', $request->get('id'))->update($save_data);

    if( $update ){
      $data['status'] = true;
      $data['message'] = 'Successfully Updated Password.';
    } else {
      $data['status'] = false;
      $data['message'] = 'Failed.';
    }
    return $data;
  }

  public function addRemoveUserContact( Request $request ){
    $save_data = array(
      "status" => $request->get('status'),
    );

    $update_contact = UserContacts::where('user_id', '=', $request->get('user_id'))
                                      ->where('contact_id', '=', $request->get('contact_id'))->update($save_data);

    if( $update_contact ){
        $data['status'] = true;
        $data['message'] = 'Success.';
    } else {
        $data['status'] = false;
        $data['message'] = 'Failed.';
    }

    return $data;
  }

}