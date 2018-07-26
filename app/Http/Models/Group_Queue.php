<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Distance;

class Group_Queue extends Model
{
  use Distance;
  protected $fillable = ['userid', 'type', 'lat', 'lon', 'distance'];
  protected $table = 'group-queue';
  protected $primaryKey = 'userid'; // or null
  public $incrementing = false;

  protected $casts = [
      'type' => 'array',
  ];
  public $timestamps = false;

  public function join_Quick_Queue($userid, array $type, $distance)
  {
    $queued = Group_Queue::where('userid', $userid)->first();
    if ($queued)
    {
      return 'User Already Queued';
    }
    else {
      $profile = Profile::where('user_id', $userid)->first();
      $lat = $profile->lat;
      $lon = $profile->lon;

      $joinQueue = Group_Queue::create([
          'userid' => $userid,
          'type' => $type,
          'lat' => $lat,
          'lon' => $lon,
          'distance' => $distance
      ]);

      $distanceCheck = $this->match_Quick_Queue($userid, $type, $lat, $lon, $distance);
      if ($distanceCheck->isEmpty())
      {
        return $joinQueue;
      }
      else {
        return $distanceCheck;
      }
    }
  }

  public function leave_Quick_Queue($userid)
  {
    $queued = Group_Queue::where('userid', $userid)->first();

    if ($queued)
    {
      $queueDelete = Group_Queue::where('userid', $userid)->delete();
      return $queueDelete;
    }
    else {
      return 'User Not Queued';
    }
  }

  public function match_Quick_Queue($userid, $type, $lat, $lon, $distance)
  {
    $gatherUsers = Group_Queue::where('userid', '!=', $userid)
      ->where('type', json_encode($type))
      ->get();

    if ($gatherUsers->isEmpty())
    {
      return $gatherUsers;
    }
    else {
      // go through collection and find users distance choice
      // for each user, calc distance parimiter and see if new queued user is within their distance constraints
      // if so, gather userids of current user submission, as well as those within users distance
      // trait call to gather high/low for user matching
      $distanceCheck = $this->gatherHighLow($lat, $lon, $distance);
      $latCheck = array($distanceCheck['latLow'], $distanceCheck['latHigh']);
      $lonCheck = array($distanceCheck['lonLow'], $distanceCheck['lonHigh']);
      return $gatherUsers;
    }
  }

}
