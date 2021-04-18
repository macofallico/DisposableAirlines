<?php

if (!function_exists('Dsp_Fuel')) {
  // Convert And Format Fuel Weight
  function Dsp_Fuel($fuel) {
    $unit = setting('units.fuel');
    if($unit === 'kg') {
      $fuel = $fuel / 2.20462262185;
    }
    $fuel = number_format($fuel)." ".$unit;
    return $fuel;
  }
}

if (!function_exists('Dsp_Weight')) {
  // Convert And Format Weight
  function Dsp_Weight($weight) {
    $unit = setting('units.weight');
    if($unit === 'kg') {
      $weight = $weight / 2.20462262185;
    }
    $weight = number_format($weight)." ".$unit;
    return $weight;
  }
}

if (!function_exists('Dsp_Distance')) {
  // Convert And Format Distances
  function Dsp_Distance($distance) {
    $unit = setting('units.distance');
    if($unit === 'km') {
      $distance = $distance * 1.852;
    } elseif($unit === 'mi') {
      $distance = $distance * 1.15078;
    }
    $distance = number_format($distance)." ".$unit;
    return $distance;
  }
}

if (!function_exists('Dsp_FuelCost')) {
  // Convert And Format Fuel Costs
  function Dsp_FuelCost($cost) {
    $unit = setting('units.fuel');
    $currency = setting('units.currency');
    if($unit === 'kg') {
      $cost = $cost / 2.20462262185;
    }
    $cost = number_format($cost,3)." ".$currency."/".$unit;
    return $cost;
  }
}

if (!function_exists('Dsp_PirepBadge')) {
  // Change Badge Color According to PirepState
  function Dsp_PirepBadge($state) {
    $color = 'badge-primary';
    if($state === 1 || $state === 5) { $color = 'badge-secondary'; }
    elseif($state === 2 ) { $color = 'badge-success'; }
    elseif($state === 3 || $state === 4 ) { $color = 'badge-warning'; }
    elseif($state === 6 ) { $color = 'badge-danger'; }
    return $color;
  }
}

if (!function_exists('Dsp_AcStateBadge')) {
  // Change Badge Color According to AircraftState
  function Dsp_AcStateBadge($state) {
    $color = 'badge-primary';
    if($state === 0) { $color = 'badge-success'; }
    elseif($state === 1) { $color = 'badge-info'; }
    elseif($state === 2) { $color = 'badge-warning'; }
    return $color;
  }
}

if (!function_exists('Dsp_AcStatusBadge')) {
  // Change Badge Color According to Aircraft Status
  function Dsp_AcStatusBadge($status) {
    $color = 'badge-primary';
    if($status === 'A') { $color = 'badge-success'; }
    elseif($status === 'S' || $status === 'R') { $color = 'badge-warning'; }
    elseif($status === 'C' || $status === 'W') { $color = 'badge-danger'; }
    return $color;
  }
}

if (!function_exists('Dsp_UserStateBadge')) {
  // Change Badge Color According to User State
  function Dsp_UserStateBadge($state) {
    $color = 'badge-primary';
    if($state === 0) { $color = 'badge-secondary'; }
    elseif($state === 1) { $color = 'badge-success'; }
    elseif($state === 3) { $color = 'badge-warning'; }
    elseif($state === 2 || $state === 4) { $color = 'badge-danger'; }
    return $color;
  }
}
