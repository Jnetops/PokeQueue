<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Models\PokeTracker;
use App\Traits\ValidateInput;
use View;
use File;

class PokeTrackerController extends Controller
{
    use ValidateInput;
    public function Submit_Pokemon(Request $request)
    {
      $validateInput = $this->submitPokeValidate($request);
      if ($validateInput->passes())
      {
        $submitPokemon = new PokeTracker();

        $submit = $submitPokemon->submitPokemon($request);

        if ($submit['Success'] == 'False')
        {
          return redirect()->route('pokeTrackerAdd')->with('error', $submit['Error']);
        }
        else {
          // return to view figur out how to do thi
          return redirect()->route('pokeTrackerAll', ['distance' => 25]);
        }
      }
      else {
        return redirect()->route('pokeTrackerAdd')->with('errors', $validateInput->errors());
      }
    }

    public function Submit_Pokemon_Form(Request $request)
    {
      $path = public_path() . '/protos/pokemon.json';// ie: /var/www/laravel/app/storage/json/filename.json
      if (!File::exists($path)) {
          return 'failed';
      }

      $file = File::get($path); // string
      $file = json_decode($file, true);

      $pokemonArray = array();
      foreach ($file as $poke)
      {
        $pokemonArray[$poke['Name']] = (int)$poke['Number'];
      }

      return View::make('poketracker_add',['pokemon' => $pokemonArray]);
    }

    public function ajax_Moveset(Request $request)
    {
      $path = public_path() . '/protos/pokemon.json';// ie: /var/www/laravel/app/storage/json/filename.json
      if (!File::exists($path)) {
          return 'failed';
      }

      $file = File::get($path); // string
      $file = json_decode($file, true);

      $pokemonMoves = array();
      foreach ($file as $poke)
      {
        if ($poke['Number'] == $request->input('pokemon'))
        {
          $pokemonMoves['fast_move'] = $poke['Fast Attack(s)'];
          $pokemonMoves['charge_move'] = $poke['Special Attack(s)'];
        }
      }
      return $pokemonMoves;
    }

    public function Gather_Pokemon(Request $request)
    {
      $gatherPokes = new PokeTracker();

      $gather = $gatherPokes->gatherPokemon($request);

      if ($request->has('ajax') && $request->input('ajax') == 'true')
      {
        return $gather;
      }
      else {
        return View::make('poketracker_all',['pokemons' => $gather]);
      }
    }

    public function Gather_Specific_Pokemon(Request $request, $id)
    {
      $gatherPokes = new PokeTracker();

      if ($request->has('filter-id'))
      {
          $request->merge(['filter-id' => $id]);
      }
      else {
          $request->request->add(['filter-id' => $id]);
      }
      $gather = $gatherPokes->gatherPokemon($request);

      if ($request->has('ajax') && $request->input('ajax') == 'true')
      {
        return $gather;
      }
      else {
        if (array_key_exists('Expired', $gather))
        {
          return View::make('expired', ['Type' => 'Pokemon']);
        }
        if (array_key_exists('Found', $gather))
        {
          return View::make('notfound', ['Type' => 'Pokemon']);
        }
        return View::make('poketracker_all',['pokemons' => $gather]);
      }
    }
}
