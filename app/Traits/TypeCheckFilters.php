<?php

namespace App\Traits;

trait TypeCheckFilters
{
  public function checkTypes($request)
  {
    if ($request->has('type'))
    {
      $typeArray = array(1,2,3);
      if (in_array($request->input('type'), $typeArray))
      {
        if ($request->has('subType1'))
        {
          if (in_array($request->input('subType1'), $typeArray))
          {
            if ($request->has('subType2') && $request->input('subType1') == 2)
            {
              if ($request->input('subType2') >= 1 && $request->input('subType2') <= 251)
              {
                return array(
                  'type' => $request->input('type'),
                  'subType1' => $request->input('subType1'),
                  'subType2' => $request->input('subType2')
                );
              }
              else {
                return array('error' => 'Invalid Sub Type Provided');
              }
            }
            else {
              return array(
                'type' => $request->input('type'),
                'subType1' => $request->input('subType1')
              );
            }
          }
          else {
            return array('error' => 'Invalid Sub Type Provided');
          }
        }
        else {
          return array(
            'type' => $request->input('type')
          );
        }
      }
      elseif ($request->input('type') == 4)
      {
        if ($request->has('subType1'))
        {
          if ($request->has('subType2'))
          {
            return array(
              'type' => $request->input('type'),
              'subType1' => $request->input('subType1'),
              'subType2' => $request->input('subType2')
            );
          }
          return array(
            'type' => $request->input('type'),
            'subType1' => $request->input('subType1')
          );
        }
        else {
          return array('type' => $request->input('type'));
        }
      }
      else {
        return array('error' => 'Invalid Types Provided');
      }
    }
    else {
      return array('success' => 'No Types Supplied');
    }
  }
}
