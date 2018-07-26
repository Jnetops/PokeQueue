<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Models\PokeTracker;
use App\Http\Models\Notifications;
use Carbon\Carbon;

class RemoveInactivePokemon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RemoveInactivePokemon:removepokemon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Expired Pokemon';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $pokemons = PokeTracker::where('pokemon_expire', '<=', Carbon::now())->get();
      $typeArray = array(
        'App\Notifications\PokeTrackerAdd',
      );

      $notifications = Notifications::whereIn('type', $typeArray)->get();
      foreach ($pokemons as $pokemon)
      {
        foreach ($notifications as $notification)
        {
          $data = $notification->data;
          if ($data['pokemon']['tracker_id'] == $pokemon->id)
          {
            Notifications::where('id', $notification->id)->delete();
          }
        }
        $pokemon->delete();
      }
    }
}
