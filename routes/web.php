<?php

use Carbon\Carbon;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::check())
    {
      return redirect('/dashboard');
    }
    else {
      return view('welcome');
    }
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
  Route::get('/dashboard', 'HomeController@index')->name('home');
  Route::get('/friends/add', 'FriendsController@add');
  Route::get('/friends/accept', 'FriendsController@accept');
  Route::get('/friends/reject', 'FriendsController@reject');
  Route::get('/friends/delete', 'FriendsController@delete');
  Route::get('/friends', 'FriendsController@get_friends');

  Route::post('/profile/update/password', 'ProfileController@Update_Password');
  Route::post('/profile/update/level', 'ProfileController@Update_Level');
  Route::post('/profile/update/team', 'ProfileController@Update_Team');
  Route::get('/profile/update/trainer', 'ProfileController@Update_Trainer');
  Route::post('/profile/update/location', 'ProfileController@Update_Location');

  Route::get('/profile', 'ProfileController@get_Profile');
  Route::post('/profile/friends/remove', 'FriendsController@delete');

  Route::get('/users/{userID}', 'ProfileController@Get_User');

  Route::get('/chat/location/add', 'GeneralChatController@Submit_Location_Comment');
  Route::get('/chat/location', 'GeneralChatController@Gather_Location_Comments');

  Route::post('/events/create', 'EventsController@Event_Creation')->name('eventFormPost');
  Route::get('/events/create', 'EventsController@Event_Creation_Form')->name('eventForm');
  Route::post('/events/delete', 'EventsController@Event_Deletion');
  Route::post('events/ownership', 'EventsController@transferAdmin');
  Route::post('/events/chat/submit', 'EventsController@submitEventChat');
  Route::post('/events/request/invite', 'EventsController@Request_Invite_To_Event');
  Route::post('/events/invite', 'EventsController@Invite_To_Event');
  Route::get('/events/invite/accept', 'EventsController@Admin_Accept_Invite');
  Route::get('/events/request/accept', 'EventsController@User_Accept_Request');
  Route::get('/events/reject/invite', 'EventsController@Admin_Reject_Invite');
  Route::get('/events/reject/request', 'EventsController@User_Reject_Request');
  Route::post('/events/leave', 'EventsController@leaveEvent');
  Route::get('/events/modify', 'EventsController@Event_Modify');
  Route::get('/events', 'EventsController@Gather_Events')->name('allEvents');
  //Route::get('/events/{eventID}', 'EventsController@Gather_Specific_Event')->name('specificEvent');



  Route::get('/group/create', 'GroupController@createGroupForm')->name('/groupForm');
  Route::post('/group/create', 'GroupController@createGroup')->name('/groupFormPost');
  Route::post('/group/raid/create', 'GroupController@createRaidGroup');
  Route::post('/group/chat/submit', 'GroupController@submitGroupChat');
  Route::post('/group/disband', 'GroupController@disbandGroup');
  Route::post('/group/finalize', 'GroupController@finalizeGroup');
  Route::post('/group/requeue', 'GroupController@requeueGroup');
  Route::post('/group/ownership', 'GroupController@transferAdmin');
  Route::get('/group/users', 'GroupController@gatherUsers');
  Route::post('/group/invite', 'GroupController@inviteToGroup');
  Route::post('/group/request/invite', 'GroupController@inviteRequest');
  Route::get('/group/invite/accept', 'GroupController@acceptInvite');
  Route::get('/group/request/accept', 'GroupController@acceptRequest');
  Route::get('/group/reject/request', 'GroupController@rejectRequest');
  Route::get('/group/reject/invite', 'GroupController@rejectInvite');
  Route::post('/group/leave', 'GroupController@leaveGroup');
  //Route::get('/group/{groupID}', 'GroupController@gatherSpecificGroup')->name('specificGroup');
  Route::get('/groups', 'GroupController@gatherGroups')->name('allGroups');
  Route::get('/group', 'GroupController@redirectUserGroup');

  Route::get('poke/tracker/add', 'PokeTrackerController@Submit_Pokemon_Form')->name('pokeTrackerAdd');
  Route::post('poke/tracker/add', 'PokeTrackerController@Submit_Pokemon');
  Route::get('poke/tracker', 'PokeTrackerController@Gather_Pokemon')->name('pokeTrackerAll');
  Route::get('poke/tracker/moveset', 'PokeTrackerController@ajax_Moveset');

  Route::get('raid/tracker/add', 'RaidTrackerController@AddRaidForm')->name('raidTrackerAdd');
  Route::post('raid/tracker/add', 'RaidTrackerController@AddRaid');
  Route::get('raid/tracker', 'RaidTrackerController@GetRaids')->name('raidTrackerAll');

  //Route::get('notification/all', 'NotificationsController@all');
  //Route::get('notification/all/markallread', 'NotificationsController@deleteAll');
  Route::get('notification/{id}', 'NotificationsController@delete');
  Route::post('/group/{groupID}/chat/auth', 'NotificationsController@groupChatAuth');
  Route::post('events/{eventID}/chat/auth', 'NotificationsController@eventChatAuth');
  Route::post('notifications', 'NotificationsController@notificationAuth');

});

Route::get('/events/{eventID}', 'EventsController@Gather_Specific_Event')->name('specificEvent');
Route::get('/group/{groupID}', 'GroupController@gatherSpecificGroup')->name('specificGroup');
Route::get('poke/tracker/{id}', 'PokeTrackerController@Gather_Specific_Pokemon');
Route::get('raid/tracker/{id}', 'RaidTrackerController@GetSpecificRaid');
