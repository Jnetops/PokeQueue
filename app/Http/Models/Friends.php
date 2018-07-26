<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\User;
use App\Traits\SendNotifications;


class Friends extends Model
{
    //
    use SendNotifications;
    protected $fillable = ['request_uid', 'received_uid', 'status'];
    protected $table = 'friends';

    public function friend_add($request)
    {
        $verifyUser = User::where('trainer_name', $request->input('trainer'))->first();
        if (!$verifyUser)
        {
          return array('Success' => 'False', 'Error' => 'Supplied Invalid User');
          //return 'Supplied Invalid User';
        }
        else {
          $userid = Auth::user()->trainer_name;
          if ($userid == $request->input('trainer'))
          {
            return array('Success' => 'False', 'Error' => 'Cant Add Yourself As A Friend');
            //return 'Cant Add Yourself As A Friend';
          }
          $friendsCheck = Friends::where([['request_uid', $userid], ['received_uid', $request->input('trainer')]])
                          ->orwhere([['request_uid', $request->input('trainer')], ['received_uid', $userid]])
                          ->first();

          if (!$friendsCheck)
          {
            $addFriend = Friends::create([
                'request_uid' => $userid,
                'received_uid' => $request->input('trainer'),
                'status' => 'request'
            ]);

            $requeryFriend = Friends::where([['request_uid', $userid], ['received_uid', $request->input('trainer')], ['status', 'request']])
                                      ->orwhere([['request_uid', $request->input('trainer')], ['received_uid', $userid], ['status', 'request']])
                                      ->first();
            if ($requeryFriend)
            {
              // send user notification
              $this->ReceivedFriendRequest($request->input('trainer'), $requeryFriend);

              return array('Success' => 'True');
              //return 'Success';
            }
            else {
              return array('Success' => 'False', 'Error' => 'Failed to gather friend info');
              //return 'Failed to gather friend info';
            }
          }
          else
          {
            return array('Success' => 'False', 'Error' => 'Action Already Taken Place');
            //return 'Action Already Taken Place';
          }
        }
    }

    public function friend_accept($request)
    {
      $verifyUser = User::where('trainer_name', $request->input('trainer'))->first();
      if (!$verifyUser)
      {
        return array('Success' => 'False', 'Error' => 'Supplied Invalid User ID');
        //return 'Supplied Invalid User ID';
      }
      else {
        $userid = Auth::user()->trainer_name;
        $friendsCheck = Friends::where([['request_uid', $userid], ['received_uid', $request->input('trainer')]])
                        ->orwhere([['request_uid', $request->input('trainer')], ['received_uid', $userid]])
                        ->first();
        if (!$friendsCheck)
        {
          return array('Success' => 'False', 'Error' => 'No Request To Accept');
          //echo 'No Request To Accept';
        }
        else
        {
          if ($friendsCheck->status == 'request')
          {
            $acceptFriend = Friends::where([['request_uid', $userid], ['received_uid', $request->input('trainer')]])
                            ->orwhere([['request_uid', $request->input('trainer')], ['received_uid', $userid]])
                            ->update(['status' => 'accepted']);
            if ($acceptFriend)
            {
              $requeryFriend = Friends::where([['request_uid', $userid], ['received_uid', $request->input('trainer')], ['status', 'accepted']])
                                        ->orwhere([['request_uid', $request->input('trainer')], ['received_uid', $userid], ['status', 'accepted']])
                                        ->first();
              if ($requeryFriend)
              {
                // send user notification
                $this->AcceptedFriendRequest($request->input('trainer'), $requeryFriend);
                return array('Success' => 'True');
                //return 'accepted friend!';
              }
              else {
                return array('Success' => 'False', 'Error' => 'Failed To Requery Friend');
                //return 'Failed To Requery Friend';
              }
            }
            else
            {
              return array('Success' => 'False', 'Error' => 'Failed To Accept Friend');
              //return 'failed to accept friend';
            }
          }
          else
          {
              return array('Success' => 'False', 'Error' => 'Failed To Accept Friend');
              //return 'cant accept friend request';
          }
        }
      }
    }

    public function friend_reject($request)
    {
      $verifyUser = User::where('trainer_name', $request->input('trainer'))->first(['id']);
      if (!$verifyUser)
      {
        return array('Success' => 'False', 'Error' => 'Supplied Invalid User ID');
        //return 'Supplied Invalid User ID';
      }
      else {
        $userid = Auth::user()->trainer_name;
        $friendsCheck = Friends::where([['request_uid', $userid], ['received_uid', $request->input('trainer')]])
                        ->orwhere([['request_uid', $request->input('trainer')], ['received_uid', $userid]])
                        ->first();
        if (!$friendsCheck)
        {
          return array('Success' => 'False', 'Error' => 'No Request To Reject');
          //echo 'No Request To Reject';
        }
        else
        {
          if ($friendsCheck->status == 'request')
          {
            $rejectFriend = Friends::where([['request_uid', $userid], ['received_uid', $request->input('trainer')]])
                            ->orwhere([['request_uid', $request->input('trainer')], ['received_uid', $userid]])
                            ->delete();
            if ($rejectFriend)
            {
              return array('Success' => 'True');
              //return 'Rejected Friend Request!';
            }
            else
            {
              return array('Success' => 'False', 'Error' => 'Failed To Reject Request');
              //return 'Failed To Reject Request';
            }
          }
          else {
            return array('Success' => 'False', 'Error' => 'Cannot Reject This Request');
            //return 'Cannot Reject This Request';
          }
        }
      }
    }

    public function friend_delete($request)
    {

      $allTrainers = $request->input('friends');


      $verifyUser = User::whereIn('trainer_name', $allTrainers)->first(['id']);
      if (!$verifyUser)
      {
        return 'Supplied Invalid User ID';
      }
      else {
        $userid = Auth::user()->trainer_name;

        $friendsCheck = Friends::where('request_uid', $userid)->whereIn('received_uid', $allTrainers)->get();
        $friendsCheckReverse = Friends::where('received_uid', $userid)->whereIn('request_uid', $allTrainers)->get();

        if (!$friendsCheck->count() && !$friendsCheckReverse->count())
        {
          echo 'No Friends To Remove';
        }
        else
        {
          if ($friendsCheck->count())
          {
            $friendsDelete = Friends::where('request_uid', $userid)->whereIn('received_uid', $allTrainers)->where('status', 'accepted')->delete();
          }
          elseif ($friendsCheckReverse->count())
          {
            $friendsDeleteReverse = Friends::where('received_uid', $userid)->whereIn('request_uid', $allTrainers)->where('status', 'accepted')->delete();
          }
          else {
            return "false";
          }
          return "true";
        }
      }
    }

    public function gather_friends($trainer)
    {
        $friendsCheck = Friends::where('request_uid', $trainer)->orWhere('received_uid', $trainer)->get();
        return $friendsCheck;
    }
}
