<?php

namespace Modules\DisposableAirlines\Http\Controllers;

use App\Contracts\Controller;
use App\Models\Enums\PirepState;
use App\Repositories\PirepRepository;

class PirepsController extends Controller
{
  private $pirepRepo;

  public function __construct(
    PirepRepository $pirepRepo
  ) {
    $this->pirepRepo = $pirepRepo;
  }
  // All Pireps (except inProgress flights)
  // Return collection
  public function allpireps()
  {
    $pireps = $this->pirepRepo->where('state', '!=', PirepState::IN_PROGRESS)->orderby('submitted_at', 'desc')->paginate(50);

    return view('DisposableAirlines::pireps',[
      'pireps'    => $pireps,
    ]);
  }
}
