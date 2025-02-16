# Disposable Airlines Module

Module is compatible with any latest development build of phpVMS v7 released after **17.APR.2021**. Provides ;

* Airlines page (if you have more than one)
* Airline Details page
* Subfleet Details page
* Aircraft Details page
* Fleet List page
* Pireps List page
* Listens phpVMS v7 events and can change aircraft states

## Installation Steps

* Manual Install : Upload contents of the package to your root/modules folder via ftp or your control panel's file manager 
* GitHub Clone : Clone/pull repository to your root/modules/DisposableAirlines folder
* PhpVms Module Installer : Go to `admin -> addons/modules` , click `Add New` , select downloaded file and click `Add Module`

Go to admin section and enable the module, that's all  
After enabling/disabling modules an app cache cleaning process is mandatory (check admin/maintenance)  

## Usage

If you want to enable module auto links, then enable frontend link registration commands in ModuleFolder\Providers\....ServiceProvider.php file as shown below, *Two forward slashes (//) = Disabled, No forward slashes = Enabled*

```php
  $this->moduleSvc->addFrontendLink('Airlines', '/dairlines', 'fas fa-calendar-alt', $logged_in=true);
  // $this->moduleSvc->addFrontendLink('Fleet', '/dfleet', 'fas fa-plane-departure', $logged_in=true);
  // $this->moduleSvc->addFrontendLink('All PIREPs', '/dpireps', 'fas fa-upload', $logged_in=true);
```

Disposable Theme v2 *IS* capable of recognizing and showing proper links for Disposable Modules but if you need some more control, then you can add links to your navbar (or any other place) by using below examples;

```html
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

```html
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

## Using Aircraft / Subfleet Images

If you want to display Aircraft pictures or screenshots, create two folders (**image/aircraft** and **image/subfleet**) under your public image folder (*public* or *public_html* according to your installation), then place your images under them.

Aircraft images should be named by registration like **d-ispo.jpg** and Subfleet images by their type like **a320neo-dsp.jpg** (all lowercase)  
When viewing aircraft details page, this image will be displayed in a card and aircraft images have priority over subfleet images.

Example final path will be like ***public/image/aircraft/d-ispo.jpg*** or ***public_html/image/subfleet/a320neo-dsp.jpg***

## Duplicating Module Blades/Views

Technically all blade files should work with your template but they are mainly designed for Bootstrap compatible themes. So if something looks weird in your template then you need to edit them. I kindly suggest copying them under your theme folder and do your changes there, directly editing module files will only make updating harder for you.

All Disposable Modules are capable of displaying customized files located under your theme folders;

* Original Location : `root/modules/DisposableModule/Resources/Views/somefile.blade.php`
* Target Location   : `root/resources/views/layouts/YourTheme/modules/DisposableModule/somefile.blade.php`

## Update Notes

21.SEP.21
* Aircraft details updated to match latest changes to Disposable Tech and TurkSim modules
* Discord Notificiation avatar now uses va defined (env.php) ghost gravatar image if user has no avatar

**Important Note For TurkSim Module Users : Update TurkSim Module FIRST, this is really important and mandatory! Sorry**

11.SEP.21
* PT-BR Translation (Thanks to Edson Felix)

07.AUG.21
* Release zip file structure changed for PhpVms Module Installer compatability.

08.JUL.21
* Italian Translation (thanks @Fabietto996)

06.JUL.21
* Updated German Translation (thanks @GAE074)

24.JUN.21
* Added settings for Aircraft State Control/Change feature, by default it is **DISABLED**

21.JUN.21
* Aircraft state change logic updated for better handling of acars start flight errors.

10.JUN.21
* Update helpers and necessary blades to show SimBrief Booking state as AC state too.

03.JUN.21
* German Translation (thanks @derrobin154)

01.JUN.21
* Added Discord Webhook integration for sending out `Pirep Received` messages  
  ( Go to `Admin -> Disposable Airlines` module for basic settings )

11.MAY.21
* Module is now able to listen PhpVms events and change aircraft state

```md
Boarding    : PARKED > IN USE
TakeOff     : IN USE > IN AIR
Landing     : IN AIR > IN USE
Pirep Filed : IN USE > PARKED  **Also sends a Discord Message if enabled**
```

* Fixed Aircraft Pireps card not showing latest accepted pireps
* Switched from php/number_format to phpvms/money to avoid non-numeric string errors.
* Also some minor fixes applied to other blades  
