@extends('admin.app')
@section('title', 'Disposable Airlines')

@section('content')
  <div class="card border-blue-bottom" style="margin-left:5px; margin-right:5px; margin-bottom:5px;">
    <div class="content">
      <p>This module is designed to provide some new pages/views for your phpVms v7; Airlines, Airline Details, Fleet, SubFleet, Aircraft Details and All Pireps.</p>
      <p>If you have DisposableTools Module installed, views will detect it and may use some of its widgets automatically too</p>
      <br>
      <p>
          If you want to display Aircraft pictures or screenshots, create two folders (<b>image/aircraft</b> and <b>image/subfleet</b>)under your public image folder
          (public or public_html according to your installation), then place your images under them.<br>
          Aircraft images should be named by registration like <b>d-ispo.jpg</b> (all lowercase) and Subfleet images by their type like <b>a320neo-dsp.jpg</b> (all lowercase)<br>
          When viewing aircraft details page, this image will be displayed in a card and aircraft images have priority over subfleet images.<br><br>
          Example final path will be like <b>public/image/aircraft/d-ispo.jpg</b> or <b>public_html/image/subfleet/a320neo-dsp.jpg</b>
      </p>
      <p>
        If you need to customize the layouts according to your template please copy relevant blade files from <b>modules\DisposableAirlines\Resources\views</b>
        to a folder under your theme folder named <b>modules\DisposableAirlines</b> (case sensitive) and edit files there</p>
      <p>
        Example Source: <b>phpvms root\Modules\DisposableAirlines\Resources\views\fleet.blade.php</b><br>
        Example Target: <b>phpvms root\Resources\views\layouts\default\modules\DisposableAirlines\fleet.blade.php</b><br>
        Example Target: <b>phpvms root\Resources\views\layouts\Disposable_v1\modules\DisposableAirlines\fleet.blade.php</b>
      </p>
      <p>* Do <b>NOT</b> edit source files, so you can update the module easily and keep your changes safe</p>
      <p>&nbsp;</p>
      <p>Module Developed by <a href="https://github.com/FatihKoz" target="_blank">B.Fatih KOZ</a> &copy; 2021</p>
    </div>
  </div>
@endsection
