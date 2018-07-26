<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Models\Friends;
use App\Traits\ValidateInput;

class FriendsController extends Controller
{
    use ValidateInput;
    public function add(Request $request)
    {
      //$data = array('request_uid' => $userid, 'received_uid' => 8, 'action' => 'request');
      $validateResults = $this->friendsValidate($request);

      if ($validateResults->passes()) {

        $friends = new Friends();

        $addFriend = $friends->friend_add($request);

        return $addFriend;

      } else {
        return array('Success' => 'False', 'Error' => $validateResults->errors());
      }
    }

    public function accept(Request $request)
    {
      //$data = array('request_uid' => $userid, 'received_uid' => 8, 'action' => 'accept');
      $validateResults = $this->friendsValidate($request);

      if ($validateResults->passes()) {

        $friends = new Friends();

        $acceptFriend = $friends->friend_accept($request);

        return $acceptFriend;

      } else {
        return array('Success' => 'False', 'Error' => $validateResults->errors());
      }
    }

    public function reject(Request $request)
    {
      //$data = array('request_uid' => $userid, 'received_uid' => 7, 'action' => 'reject');
      $validateResults = $this->friendsValidate($request);

      if ($validateResults->passes()) {

        $friends = new Friends();

        $rejectFriend = $friends->friend_reject($request);

        return $rejectFriend;

      } else {
        return array('Success' => 'False', 'Error' => $validateResults->errors());
      }
    }

    public function delete(Request $request)
    {
      $friends = new Friends();

      $deleteFriend = $friends->friend_delete($request);

      echo $deleteFriend;
      //$data = array('request_uid' => $userid, 'received_uid' => 7, 'action' => 'delete');
      // $validateResults = $this->friendsValidate($request);
      //
      // if ($validateResults->passes()) {
      //
      //   $friends = new Friends();
      //
      //   $deleteFriend = $friends->friend_delete($request);
      //
      //   echo $deleteFriend;
      //
      // } else {
      //   echo 'error';
      // }
    }

    public function get_friends()
    {
      $friends = new Friends();
      $gather = $friends->gather_friends();
      echo $gather;
    }
}
