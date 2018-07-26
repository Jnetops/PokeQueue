<?php

namespace App\Traits;

trait PrivacyCheck
{
  public function ValidatePrivacy($request)
  {
    if ($request->has('privacy'))
    {
      if ($request->input('privacy') == 0)
      {
        if ($request->has('privacySubOption'))
        {
          return array('error' => 'Provided Sub Options When Not Needed');
        }
        else {
          return array('privacy' => $request->input('privacy'));
        }
      }
      elseif ($request->input('privacy') == 1)
      {
        if ($request->has('privacySubOption'))
        {
          if ($request->input('privacySubOption') == 1 || $request->input('privacySubOption') == 2)
          {
            return array('privacy' => $request->input('privacy'), 'privacySubOption' => $request->input('privacySubOption'));
          }
          else {
            return array('error' => 'Invalid Privacy Sub Option');
          }
        }
        else {
          return array('error' => 'No Privacy Sub Option');
        }
      }
      else {
        return array('error' => 'Invalid Privacy Options');
      }
    }
    else {
      return array('missing' => 'Missing Privacy Options');
    }
  }
}
