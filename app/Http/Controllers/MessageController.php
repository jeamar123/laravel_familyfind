<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DateTime;

use App\User;
use App\UserContacts;
use App\Messages;

class MessageController extends Controller
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

  public function getMessages(){
    $data = array();
    $data['users'] = Messages::get();
    $data['status'] = true;
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

  public function getMessagesPerChat($id){
    $data = array();
    $data['users'] = Messages::where('contact_id', $id)->get();
    $data['status'] = true;
    return $data;
  }


}