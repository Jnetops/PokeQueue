<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait ValidateInput
{
  public function groupCreateValidate($request)
  {
    $validate = Validator::make($request->all(), [
        'count' => 'required|integer',
        'description' => 'required|string|max:255',
        'address' => 'required|string',
        'city' => 'required|string',
        'state' => 'required|string'
    ]);
    return $validate;
  }

  public function eventCreateValidate($request)
  {
    $validate = Validator::make($request->all(), [
        'description' => 'required|string|max:255',
        'month' => 'required|integer',
        'year' => 'required|integer',
        'day' => 'required|integer',
        'time' => 'required|string',
        'state' => 'required|string',
        'city' => 'required|string',
        'address' => 'required|string',
        'count' => 'required|integer',
        'type' => 'required|integer',
        'subType1' => 'sometimes|integer',
        'subType2' => 'sometimes|integer',
    ]);
    return $validate;
  }

  public function eventDeleteValidate($request)
  {
    $validate = Validator::make($request->all(), [
        'event-id' => 'required|integer'
    ]);
    return $validate;
  }

  public function eventModifyValidate($request)
  {
    $validate = Validator::make($request->all(), [
        'id' => 'required|integer',
        'description' => 'sometimes|string|max:255',
        'location' => 'sometimes|string',
    ]);
    return $validate;
  }

  public function friendsValidate($request)
  {
    $validate = Validator::make($request->all(), [
        'trainer' => 'required|string|max:15'
    ]);
    return $validate;
  }

  public function generalCommentValidate($request)
  {
    $validate = Validator::make($request->all(), [
        'comment' => 'required|string|max:255'
    ]);
    return $validate;
  }

  public function groupCommentValidate($request)
  {
    $validate = Validator::make($request->all(), [
        'comment' => 'required|string|max:255',
        'group-id' => 'required|integer'
    ]);
    return $validate;
  }

  public function eventCommentValidate($request)
  {
    $validate = Validator::make($request->all(), [
        'comment' => 'required|string|max:255',
        'event-id' => 'required|integer'
    ]);
    return $validate;
  }

  public function transferAdminValidate($request)
  {
    $validate = Validator::make($request->all(), [
        'trainer' => 'required|string|max:15',
        'group-id' => 'sometimes|integer',
        'event-id' => 'sometimes|integer'
    ]);
    return $validate;
  }

  public function submitPokeValidate($request) {
    $validate = Validator::make($request->all(), [
        'pokemon_id' => 'required|integer|min:1|max:251',
        'pokemon_cp' => 'nullable|sometimes|integer|min:10|max:4760',
        'pokemon_iv' => 'nullable|sometimes|integer|min:0|max:100',
        'pokemon_move_1' => 'nullable|sometimes|string|min:1|max:25',
        'pokemon_move_2' => 'nullable|sometimes|string|min:1|max:25',
        'pokemon_lat' => 'required|numeric',
        'pokemon_lon' => 'required|numeric',
        'expire_hour' => 'nullable|sometimes|integer|min:1|max:2',
        'expire_minute' => 'nullable|sometimes|integer|min:1|max:59'
    ]);
    return $validate;
  }

  public function eventTrainerValidate($request) {
    $validate = Validator::make($request->all(), [
        'event-id' => 'required|integer|min:1',
        'trainer' => 'required|string|max:15',
    ]);
    return $validate;
  }

  public function eventNonTrainerValidate($request) {
    $validate = Validator::make($request->all(), [
        'event-id' => 'required|integer|min:1'
    ]);
    return $validate;
  }

  public function groupTrainerValidate($request) {
    $validate = Validator::make($request->all(), [
        'group-id' => 'required|integer',
        'trainer' => 'required|string|max:15',
    ]);
    return $validate;
  }

  public function groupNonTrainerValidate($request) {
    $validate = Validator::make($request->all(), [
        'group-id' => 'required|integer'
    ]);
    return $validate;
  }

  public function raidTrackerAddValidate($request) {
    $validate = Validator::make($request->all(), [
        'gym_lat' => 'nullable|numeric',
        'gym_lon' => 'nullable|numeric',
        'pokemon_id' => 'required|integer|min:1|max:251',
        'address' => 'nullable|string',
        'gym_city' => 'nullable|string|max:75',
        'gym_state' => 'nullable|string|max:75',
        'star_level' => 'required|integer|min:1|max:6',
        'raid_expire_hour' => 'nullable|integer|max:2',
        'raid_expire_minute' => 'required|integer|min:1|max:59'
    ]);
    return $validate;
  }

  public function raidTrackerGetValidate($request) {
    $validate = Validator::make($request->all(), [
        'distance' => 'sometimes|integer|min:10|max:50',
        'pokemon-id' => 'sometimes|integer|min:1|max:251',
        'raid-tier' => 'sometimes|integer|min:1|max:5'
    ]);
    return $validate;
  }

  public function trainerNameValidate($request) {
    $validate = Validator::make($request->all(), [
        'trainer-name' => 'required|string|max:15'
    ]);
    return $validate;
  }

  public function leaveGroupValidate($request) {
    $validate = Validator::make($request->all(), [
        'group-id' => 'required|integer'
    ]);
    return $validate;
  }

  public function leaveEventValidate($request) {
    $validate = Validator::make($request->all(), [
        'event-id' => 'required|integer'
    ]);
    return $validate;
  }

}
