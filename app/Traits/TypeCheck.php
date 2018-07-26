<?php

namespace App\Traits;

trait TypeCheck
{
  public function checkTypes($request)
  {
    if ($request->has('type'))
    {
      if ($request->input('type') == 1)
      {
        if ($request->has('subType1'))
        {
          if ($request->input('subType1') == 1 || $request->input('subType1') == 2 || $request->input('subType1') == 3)
          {
            return array('type' => $request->input('type'), 'subType1' => $request->input('subType1'));
          }
          else {
            return array('error' => 'Invalid Types Provided');
          }
        }
        else {
          return array('error' => 'Invalid Types Provided');
        }
      }
      elseif ($request->input('type') == 2)
      {
        if ($request->has('subType1'))
        {
          if ($request->input('subType1') == 1 || $request->input('subType1') == 3)
          {
            return array('type' => $request->input('type'), 'subType1' => $request->input('subType1'));
          }
          elseif ($request->input('subType1') == 2)
          {
            if ($request->has('subType2'))
            {
              $pokedex = range(1,251);
              if (in_array($request->input('subType2'), $pokedex))
              {
                return array(
                  'type' => $request->input('type'),
                  'subType1' => $request->input('subType1'),
                  'subType2' => $request->input('subType2')
                );
              }
              else {
                return array('error' => 'Invalid Pokemon Selection');
              }
            }
            else {
              return array('error' => 'Invalid Types Provided');
            }
          }
          else {
            return array('error' => 'Invalid Types Provided');
          }
        }
        else {
          return array('error' => 'Invalid Types Provided');
        }
      }
      elseif ($request->input('type') == 3)
      {
        if ($request->has('subType1') || $request->has('subType2'))
        {
          return array('error' => 'Invalid Types Provided');
        }
        else {
          return array('type' => $request->input('type'));
        }
      }
    }
    else {
      return array('missing' => 'Missing Type Options');
    }
  }
}
