<?php

namespace Modules\DisposableAirlines\Http\Controllers;

use App\Contracts\Controller;
use App\Models\Enums\PirepState;
use App\Repositories\AircraftRepository;
use App\Repositories\AirlineRepository;
use App\Repositories\PirepRepository;
use App\Repositories\SubfleetRepository;
use App\Repositories\UserRepository;
use League\ISO3166\ISO3166;
use Nwidart\Modules\Facades\Module;

class AirlineController extends Controller
{
    private $aircraftRepo;
    private $airlineRepo;
    private $pirepRepo;
    private $subfleetRepo;
    private $userRepo;

    public function __construct(
        AircraftRepository $aircraftRepo,
        AirlineRepository $airlineRepo,
        PirepRepository $pirepRepo,
        SubfleetRepository $subfleetRepo,
        UserRepository $userRepo
    ) {
        $this->aircraftRepo = $aircraftRepo;
        $this->airlineRepo = $airlineRepo;
        $this->pirepRepo = $pirepRepo;
        $this->subfleetRepo = $subfleetRepo;
        $this->userRepo = $userRepo;
    }
    // Airlines
    // List all active Airlines
    // Return collection
    public function aindex()
    {
        $airlines = $this->airlineRepo->where('active',1)->get();

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
    // Details of selected Airline
    // Return mixed
    public function ashow($icao)
    {
        $airline = $this->airlineRepo->where('icao', $icao)->first();

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
            $pilots = $this->userRepo->where('airline_id', $airline->id)->orderby('id')->get();
            $subfleets = $this->subfleetRepo->where('airline_id', $airline->id)->pluck('id')->all();
            $aircraft = $this->aircraftRepo->whereIn('subfleet_id', $subfleets)->orderby('registration')->get();
            $pireps = $this->pirepRepo->where('airline_id', $airline->id)
                ->where('state', '!=', PirepState::IN_PROGRESS)
                ->orderby('submitted_at', 'desc')
                ->get();
            // $income = substr($airline->journal->transactions->sum('credit'),0,-2);
            // $expense = substr($airline->journal->transactions->sum('debit'),0,-2);
            $income = $airline->journal->transactions->sum('credit');
            $expense = $airline->journal->transactions->sum('debit');
            $balance = $income - $expense;

            if(setting('pilots.hide_inactive')) {
                $pilots = $pilots->where('state',1);
            }

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
