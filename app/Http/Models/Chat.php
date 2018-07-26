<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\User;
use App\Traits\Distance;

class Chat extends Model
{
  protected $fillable = ['user_id', 'trainer_name', 'comment', 'location', 'lat', 'lon'];
  protected $table = 'general_chat';
  use Distance;

  /*
  public function General_Comment($request)
  {
    $userid = Auth::user()->id;

    $addChat = Chat::create([
        'user_id' => $userid,
        'comment' => $request->input('comment')
    ]);
    return $addChat;
  }

  public function Gather_General()
  {
    $gatherChat = \DB::table('general_chat')->whereNull('location')->get();
    return $gatherChat;
  }
  */

  public function Location_Comment($request)
  {
    $userid = Auth::user()->id;
    $userLocation = User::where('id', $userid)->first(['trainer_name', 'location', 'lat', 'lon']);

    $addChat = Chat::create([
        'user_id' => $userid,
        'trainer_name' => $userLocation->trainer_name,
        'comment' => $request->input('comment'),
        'location' => $userLocation->location,
        'lat' => $userLocation->lat,
        'lon' => $userLocation->lon
    ]);
    return $addChat;
  }

  public function Gather_Location($distanceArray)
  {
    $getChat = Chat::whereBetween('lat', [$distanceArray['latLow'], $distanceArray['latHigh']])->whereBetween('lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])->orderBy('created_at', 'asc')->take(100)->get(['trainer_name', 'comment', 'created_at']);
    return $getChat;
  }

}
