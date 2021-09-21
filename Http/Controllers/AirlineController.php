<?php

namespace Modules\DisposableAirlines\Http\Controllers;

use App\Contracts\Controller;
use App\Models\Aircraft;
use App\Models\Airline;
use App\Models\Pirep;
use App\Models\Subfleet;
use App\Models\User;
use App\Models\Enums\PirepState;
use League\ISO3166\ISO3166;
use Nwidart\Modules\Facades\Module;

class AirlineController extends Controller
{
  // Airlines
  public function aindex()
  {
    $airlines = Airline::where('active',1)->get();

    if(!$airlines) {
      flash()->error('No Active Airlines Found !');
      return redirect(route('frontend.dashboard.index'));
    }

    if($airlines->count() === 1) {
      $airline = $airlines->first();
      return redirect(route('DisposableAirlines.ashow', [$airline->icao]));
    }

    return view('DisposableAirlines::airlines', [
      'airlines' => $airlines,
      'country'  => new ISO3166(),
    ]);
  }

  // Airline Details
  public function ashow($icao)
  {
    $airline = Airline::where('icao', $icao)->first();

    if(!$airline) {
      flash()->error('Airline Not Hub !');
      return redirect(route('DisposableAirlines.aindex'));
    }

    if($airline) {

      $DisposableTools = Module::find('DisposableTools');
      if($DisposableTools) {
        $DisposableTools = $DisposableTools->isEnabled();
      }

      $DisposableHubs = Module::find('DisposableHubs');
      if($DisposableHubs) {
        $DisposableHubs = $DisposableHubs->isEnabled();
      }

      $pilot_where = [];
      $pilot_where['airline_id'] = $airline->id;

      if(setting('pilots.hide_inactive')) {
        $pilot_where['state'] = 1;
      }

      $pilots = User::where($pilot_where)->orderby('id')->get();
      $subfleets_array = Subfleet::where('airline_id', $airline->id)->pluck('id')->toArray();
      $aircraft = Aircraft::whereIn('subfleet_id', $subfleets_array)->orderby('registration')->get();
      $pireps = Pirep::where('airline_id', $airline->id)->where('state', '!=', PirepState::IN_PROGRESS)->orderby('submitted_at', 'desc')->paginate(100);
      $income = $airline->journal->transactions->sum('credit');
      $expense = $airline->journal->transactions->sum('debit');
      $balance = $income - $expense;

      return view('DisposableAirlines::airline', [
        'disptools' => $DisposableTools,
        'disphubs'  => $DisposableHubs,
        'airline'   => $airline,
        'income'    => $income,
        'expense'   => $expense,
        'balance'   => $balance,
        'users'     => $pilots,
        'pireps'    => $pireps,
        'fleet'     => $aircraft,
        'country'   => new ISO3166(),
      ]);
    }
  }
}
