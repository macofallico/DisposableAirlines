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

  <div class="row text-center" style="margin-left:5px; margin-right:5px;">
    <div class="col-sm-12">
        <h5 style="margin:5px; padding:5px;"><b>Admin Functions</b></h5>
    </div>
  </div>

  <div class="row text-center" style="margin-left:5px; margin-right:5px;">
    <div class="col-sm-12">
      <div class="col-sm-4">
        <div class="card border-blue-bottom" style="padding:10px;">
          <b>Fix Aircraft State</b>
          <br><br>
          <form action="/admin/disposableairlines" id="fixacstate">
            <div class="row text-center">
              <div class="col-sm-12">
                <label for="parkac">Enter Aircraft Registration</label>
                <input class="form-control" type="text" id="parkac" name="parkac" placeholder="TC-DSP" maxlength="6">
              </div>
            </div>
            <input type="submit" value="Park Aircraft">
          </form>
          <br>
          <span class="text-danger"><b>If the aircraft has an active (in-progress) PIREP, it gets CANCELLED too !!!</b></span>
        </div>
      </div>
      <div class="col-sm-8">
        <div class="card border-blue-bottom" style="padding:10px;">
          <b>Configure Discord Webhook (for Pirep Filed Messages)</b>
          <br><br>
          <form action="/admin/disposableairlines" id="discordmessages">
            <input type="hidden" name="action" value="pirepmsg">
            <div class="row text-left">
              <div class="col-sm-8">
                <label for="mainsettings">Enable Messages</label>
                <input type="checkbox" id="mainsetting" name="mainsetting" value="true" @if(Dispo_Settings('dairlines.discord_pirepmsg')) checked @endif>
              </div>
            </div>
            <div class="row text-left">
              <div class="col-sm-12">
                <label for="webhookurl">Webhook URL (copy&paste from your Discord Server settings)</label>
                <input class="form-control" type="text" id="webhookurl" name="webhookurl" value="{{ Dispo_Settings('dairlines.discord_pirep_webhook') }}" maxlength="500">
              </div>
            </div>
            <div class="row text-left">
              <div class="col-sm-4">
                <label for="webhookname">Message Poster's Name</label>
                <input class="form-control" type="text" id="webhookname" name="webhookname" value="{{ Dispo_Settings('dairlines.discord_pirep_msgposter') }}" maxlength="50">
              </div>
            </div>
            <input type="submit" value="Save Settings">
          </form>
          <br>
          <span class="text-info">Create your webhook before enabling it here, also check laravel logs if the messages do not appear in your Discord</span>
        </div>
    </div>
  </div>
@endsection
