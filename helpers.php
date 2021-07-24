<?php
use \App\Models\Enums\AircraftState;
use \App\Models\Enums\AircraftStatus;
use \App\Models\Enums\PirepState;
use \App\Models\Enums\UserState;
use \App\Models\SimBrief;
use \Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\DB;

// Check Disposable Modules
// Return boolean
if (!function_exists('Dispo_Modules')) {
  function Dispo_Modules($module)
  {
    $module_enabled = false;
    $dispo_module = Module::find($module);
    if($dispo_module) {
      $module_enabled = $dispo_module->isEnabled();
    }
    return $module_enabled;
  }
}

// Convert Minutes To Hours:Minutes
if (!function_exists('Dispo_TimeConvert')) {
  function Dispo_TimeConvert($minutes)
  {
    $hours = floor($minutes / 60);
    $minutes = ($minutes % 60);
    return sprintf('%02d:%02d', $hours, $minutes);
  }
}

// Below Functions Will Be Used If DisposableTools Module Is Not Installed or Disabled
// Same functions are defined there, this is just a safety backup
// If you need to edit any of these functions, please use the main module helper file instead of this
// If that module gets uninstalled or disabled, this module will work as expected with the functions below
if(!Dispo_Modules('DisposableTools'))
{
  // Format Pirep State Badge
  // Return formatted string (with html tags)
  if (!function_exists('Dispo_PirepBadge')) {
    function Dispo_PirepBadge($pirepstate)
    {
      $color = 'primary';
      if($pirepstate === 0 || $pirepstate === 5) { $color = 'info'; }
      elseif($pirepstate === 1) { $color = 'secondary'; }
      elseif($pirepstate === 2) { $color = 'success'; }
      elseif($pirepstate === 3) { $color = 'warning'; }
      elseif($pirepstate === 4 || $pirepstate === 6) { $color = 'danger'; }

      $result = "<span class='badge badge-".$color."'>".PirepState::label($pirepstate)."</span>";
      return $result;
    }
  }

  // Format User State Badge
  // Return formatted string (with html tags)
  if (!function_exists('Dispo_UserStateBadge')) {
    function Dispo_UserStateBadge($state)
    {
      $color = 'primary';
      if($state === 0) { $color = 'secondary'; }
      elseif($state === 1) { $color = 'success'; }
      elseif($state === 3) { $color = 'warning'; }
      elseif($state === 2 || $state === 4 || $state === 5) { $color = 'danger'; }

      $result = "<span class='badge badge-".$color."'>".UserState::label($state)."</span>";
      return $result;
    }
  }

  // Format Aircraft State Badge
  // return formatted string (with html tags)
  if (!function_exists('Dispo_AcStateBadge')) {
    function Dispo_AcStateBadge($state, $aircraft_id = null)
    {
      $color = 'primary';
      if($state === 0) { $color = 'success'; }
      elseif($state === 1) { $color = 'info'; }
      elseif($state === 2) { $color = 'warning'; }

      $result = "<span class='badge badge-".$color."'>".AircraftState::label($state)."</span>";

      // See if this aircraft is being used by some user's active simbrief ofp
      if (setting('simbrief.block_aircraft') && $aircraft_id && $state === 0) {
        $simbrief_book = SimBrief::where('aircraft_id', $aircraft_id)->whereNotNull('flight_id')->whereNull('pirep_id')->orderby('created_at', 'desc')->first();
        if (!empty($simbrief_book)) {
          $result = "<span class='badge badge-secondary' title='Booked By: ".$simbrief_book->user->name_private."'>Booked</span>";
        }
      }

      return $result;
    }
  }

  // Format Aircraft Status Badge
  // return formatted string (with html tags)
  if (!function_exists('Dispo_AcStatusBadge')) {
    function Dispo_AcStatusBadge($status)
    {
      $color = 'primary';
      if($status === 'A') { $color = 'success'; }
      elseif($status === 'S' || $status === 'R') { $color = 'warning'; }
      elseif($status === 'C' || $status === 'W') { $color = 'danger'; }

      $result = "<span class='badge badge-".$color."'>".AircraftStatus::label($status)."</span>";
      return $result;
    }
  }

  // Format RouteCode
  // Return string
  if (!function_exists('Dispo_RouteCode')) {
  function Dispo_RouteCode($route_code)
    {
      if(Dispo_Modules('DisposableTours'))
      {
        $route_code = Dsp_TourName($route_code);
        return $route_code;
      }
      if($route_code === 'H'){ $route_code = 'Historic' ;}
      // You can add more text for your own codes like below
      // elseif($route_code === 'AJ'){ $route_code = 'AnadoluJet' ;}
      return $route_code;
    }
  }

  // Generic Value Rounder with Weight Converter
  // Conversion part designed for SimBrief Briefing Blade
  // Return Numeric String
  if (!function_exists('Dispo_Round')) {
    function Dispo_Round($value, $round, $conv = null)
    {
      if($conv === 'lbs') { $value = $value * 2.20462262185; }
      elseif($conv === 'kg') { $value = $value / 2.20462262185; }
      elseif($conv === 'kgs') { $value = $value / 2.20462262185; }
      $value = floatval($value);
      $rmv = fmod($value,$round);
      $rmv = $round - $rmv;
      $rounded = $value + $rmv;
      return $rounded;
    }
  }

  // Generic Fuel Cost Converter
  // Return formatted string
  if (!function_exists('Dispo_FuelCost')) {
    function Dispo_FuelCost($cost)
    {
      $unit = setting('units.fuel');
      $currency = setting('units.currency');
      if($unit === 'kg') { $cost = $cost / 2.20462262185; }
      $cost = number_format($cost,3)." ".$currency."/".$unit;
      return $cost;
    }
  }

  // Generic Fuel Weight Converter
  // Return formatted string
  if (!function_exists('Dispo_Fuel')) {
    function Dispo_Fuel($fuel)
    {
      $unit = setting('units.fuel');
      if($unit === 'kg') { $fuel = $fuel / 2.20462262185; }
      $fuel = number_format($fuel)." ".setting('units.fuel');
      return $fuel;
    }
  }

  // Generic Weight Converter
  // Return formatted string
  if (!function_exists('Dispo_Weight')) {
    function Dispo_Weight($weight)
    {
      $unit = setting('units.weight');
      if($unit === 'kg') { $weight = $weight / 2.20462262185; }
      $weight = number_format($weight)." ".setting('units.weight');
      return $weight;
    }
  }

  // Generic Distance Converter
  // Return formatted string
  if (!function_exists('Dispo_Distance')) {
    function Dispo_Distance($distance)
    {
      $unit = setting('units.distance');
      if($unit === 'km') { $distance = $distance * 1.852; }
      elseif($unit === 'mi') { $distance = $distance * 1.15078; }
      $distance = number_format($distance)." ".$unit;
      return $distance;
    }
  }

  // Generic Altitude Converter
  // Return formatted string
  if (!function_exists('Dispo_Altitude')) {
    function Dispo_Altitude($altitude)
    {
      $unit = setting('units.altitude');
      if($unit === 'm') { $altitude = $altitude / 3.280840; }
      $altitude = number_format($altitude)." ".$unit;
      return $altitude;
    }
  }

  // Check Disposable Settings
  // Return mixed, either boolean as true/false or the value as string
  // If settings is not found, return either false or provided default
  if (!function_exists('Dispo_Settings')) {
    function Dispo_Settings($key, $default_value = null)
    {
      $setting = DB::table('disposable_settings')->select('key', 'value')->where('key', $key)->first();

      if (!$setting && !$default_value) { $result = false; }
      elseif (!$setting && $default_value) { $result = $default_value; }
      elseif (!$setting->value) { $result = $default_value; }
      elseif ($setting->value === 'false') { $result = false; }
      elseif ($setting->value === 'true') { $result = true; }
      else { $result = $setting->value; }

      return $result;
    }
  }
}
