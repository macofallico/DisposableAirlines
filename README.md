## Disposable Airlines Module for phpVMS v7
**Update Notes**

03.JUN.2021
* German Translation (thanks @derrobin154)

01.JUN.2021
* Added Discord Webhook integration for sending out `Pirep Received` messages  
  ( Go to `Admin -> Disposable Airlines` module for basic settings )

12.MAY.2021
* Fixed a typo in airline.blade.php

11.MAY.2021
* Module is now able to listen PhpVms events and change aircraft state
```
  Pirep Prefiled : PARKED > IN USE
  Airborne       : IN USE > IN AIR
  Landing        : IN AIR > IN USE
  Pirep Filed    : IN USE > PARKED  *** Also Sends Out Discord Message if enabled / 01.JAN ***
```
  (01.JAN: SimBrief form of PhpVms and vmsAcars supports state checks)  
* Fixed Aircraft Pireps card not showing latest accepted pireps
* Switched from php/number_format to phpvms/money to avoid non-numeric string errors.
* Also some minor fixes applied to other blades.

---

Module is compatible with the latest dev build as of 17APR2021, there is no need to modify any default files.  
Technically all blade files (views/pages or whatever you call them) should work with your template but they are mainly designed for Bootstrap compatible themes (like Disposable Themes, Stisla etc).  
So if something looks weird in your template then you need to edit them.  
To edit blade files, I kindly suggest copying them under your theme folder and do your changes there. Directly editing module blade files will only make updating harder for you.  

All Disposable Modules are capable of displaying customized files located under your theme folders;  
* Original Location : root/modules/DisposableModule/Resources/Views/somefile.blade.php
* Target Location   : root/resources/views/layouts/YourTheme/modules/DisposableModule/somefile.blade.php

**Installation Steps**

Upload contents of the package (or pull/clone from GitHub) to your root/modules/DisposableAirlines folder  
Go to admin section and enable the module, that's all  
After enabling/disabling modules an app cache cleaning process is necessary (check admin/maintenance)

**Usage**

If you want to enable module auto links, then enable frontend link registration commands in ModuleFolder\Providers\....ServiceProvider.php file as shown below;\
(Two forward slashes (//) = Disabled, No forward slashes = Enabled )

```
  $this->moduleSvc->addFrontendLink('Airlines', '/dairlines', 'fas fa-calendar-alt', $logged_in=true);
  // $this->moduleSvc->addFrontendLink('Fleet', '/dfleet', 'fas fa-plane-departure', $logged_in=true);
  // $this->moduleSvc->addFrontendLink('All PIREPs', '/dpireps', 'fas fa-upload', $logged_in=true);
```
    
Disposable Theme v2 *IS* capable of recognizing and showing proper links for Disposable Modules but if you need some more control, then you can add links to your navbar (or any other place) with below examples;

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
*(Best way to add links in Laravel structure is to use routes like above, but plain html href="/dairline/DSP" is also possible)*

---

You are free to edit any of the files as you wish, but please do not expect help/updates for the code you edited (controllers and providers)  
I always try to provide info and support but can not fix things you broke ;) Just share your thoughts about any improvements so we can think together before changing things.

Enjoy,  
Disposable  
17.APR.2021
