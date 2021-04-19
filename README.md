# Disposable Airlines Module for phpVMS v7

This module is compatible with the latest dev build as of 17APR2021, there is no need to modify any default files.\
Technically all blade files (views/pages or whatever you call them) should work with your template but they are mainly designed for Bootstrap compatible themes (like Disposable Themes, Stisla etc). 

So if something looks weird in your template then you need to edit them.

***** Installation Steps 

Upload contents of the package (or pull/clone from GitHub) to your root/modules/DisposableAirlines folder\
Go to admin section and enable the module, that's all\
After enabling/disabling modules an app cache cleaning process is necessary (check admin/maintenance)

***** Usage

If you want to disable module auto links and add your own according to your template, then dashout 3 frontend link registration commands in the Providers\AirlinesServiceProvider.php file as shown below;
(Two forward slashes will make them disabled.)

```
  // $this->moduleSvc->addFrontendLink('Airlines', '/dairlines', 'fas fa-calendar-alt', $logged_in=true);
  // $this->moduleSvc->addFrontendLink('Fleet', '/dfleet', 'fas fa-plane-departure', $logged_in=true);
  // $this->moduleSvc->addFrontendLink('All PIREPs', '/dpireps', 'fas fa-upload', $logged_in=true);
```
    
Then you can add links to your navbar with below examples;

```
<li>
  <a class="nav-link" href="{{ route('DisposableAirlines.aindex') }}">
    <i class="fas fa-calendar-alt"></i>
    <span>Airlines</span>
  </a>
</li>

<li>
  <a class="nav-link" href="{{ route('DisposableAirlines.dfleet') }}">
    <i class="fas fa-plane"></i>
    <span>Fleet</span>
  </a>
</li>

<li>
  <a class="nav-link" href="{{ route('DisposableAirlines.dpireps') }}">
    <i class="fas fa-upload"></i>
    <span>All Pireps</span>
  </a>
</li>
```

Also having a direct link to a specific airline/subfleet/aircraft is possible with;

```
<li>
  <a class="nav-link" href="{{ route('DisposableAirlines.ashow', ['DSP']) }}">
    <i class="fas fa-calendar-day"></i>
    <span>My Airline</span>
  </a>
</li>

<li>
  <a class="nav-link" href="{{ route('DisposableAirlines.daircraft', ['D-ISPO']) }}">
    <i class="fas fa-plane"></i>
    <span>My Aircraft</span>
  </a>
</li>

<li>
  <a class="nav-link" href="{{ route('DisposableAirlines.dsubfleet', ['A320NEO-DSP']) }}">
    <i class="fas fa-plane-departure"></i>
    <span>My Subfleet</span>
  </a>
</li>
```

Airline direct links use airline icao code, subfleets use their unique type and aircrafts use their registrations.

(Best way to add links in Laravel structure is to use routes like above, but plain html href="/dairline/DSP" is also possible)

You are free to edit any of the files as you wish, but please do not expect help/updates for the code you edited (controllers and providers)\
I always try to provide info and support but can not fix things you broke ;) Just share your thoughts about any improvements so we can think together before changing things.

Enjoy,\
Disposable\
17.APR.2021
