<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Models\Chat;
use App\Traits\ValidateInput;

class GeneralChatController extends Controller
{
  use ValidateInput;
  /*
  public function Submit_General_Comment(Request $request)
  {
    $validateResults = $this->generalCommentValidate($request);

    if ($validateResults->passes())
    {
      $chat = new Chat();
      $addChat = $chat->General_Comment($request);
      echo $addChat;
    }
    else
    {
      echo 'error';
    }
  }
  */

  public function Submit_Location_Comment(Request $request)
  {
    $validateResults = $this->generalCommentValidate($request);

    if ($validateResults->passes())
    {
      $chat = new Chat();
      $addChat = $chat->Location_Comment();
      echo $addChat;
    }
    else
    {
      echo 'error';
    }
  }

  /*
  public function Gather_General_Comments(Request $request)
  {
    $chat = new Chat();
    $gather = $chat->Gather_General();
    echo $gather;
  }
  */

  public function Gather_Location_Comments(Request $request)
  {
    $chat = new Chat();
    $gather = $chat->Gather_Location($request);
    echo $gather;
  }
}
