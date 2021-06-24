<?php

namespace Modules\DisposableAirlines\Listeners;

use App\Events\PirepFiled;
use App\Models\Aircraft;
use App\Models\Enums\AircraftState;
use Log;

class PirepFiledEventListener
{
  // Listen PirepFiled Event
  // To Change Aircraft State : PARKED
  public function handle(PirepFiled $event)
  {
    $pirep = $event->pirep;
    // Send Out Discord Message
    if(Dispo_Settings('dairlines.discord_pirepmsg')) {
      $this->SendPirepMessage($pirep);
    }
    // Change Aircraft State
    if(Dispo_Settings('dairlines.acstate_control') && $pirep->aircraft) {
      $aircraft = Aircraft::where('id', $pirep->aircraft->id)->first();
      if($aircraft) {
        $aircraft->state = AircraftState::PARKED;
        $aircraft->save();
        Log::debug("'Disposable Airlines: Pirep ".$event->pirep->id." FILED, Change STATE of ".$aircraft->registration." to ON GROUND'");
      }
    }
  }

  public function SendPirepMessage($pirep) {
    // Create new webhook in your Discord Server settings and copy&paste its URL here
    $webhookurl = Dispo_Settings('dairlines.discord_pirep_webhook');
    $msgposter = !empty(Dispo_Settings('dairlines.discord_pirep_msgposter')) ? Dispo_Settings('dairlines.discord_pirep_msgposter') : config('app.name');
    $avatar = !empty($pirep->user->avatar) ? $pirep->user->avatar->url : "https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y";
    $pirep_aircraft = !empty($pirep->aircraft) ? $pirep->aircraft->registration." (".$pirep->aircraft->icao.")" : "Not Reported";
    $json_data = json_encode([
      // Plain Text Message
      "content" => "New Flight Report Received",
      // Username for the WebHook (poster of the message)
      "username" => $msgposter,
      // Text-to-speech
      "tts" => false,
      // Embeds Array
      "embeds" => [
        // Main Content
        [
        // Embed Title
        "title" => "**Flight Details**",
        // Airline Logo will be used as Embed Image
        "image" => [ "url" => $pirep->airline->logo ],
        // Embed Type
        "type" => "rich",
        // Timestamp of embed must be formatted as ISO8601
        "timestamp" => date("c", strtotime($pirep->submitted_at)),
        // Embed left border color in HEX
        "color" => hexdec( "FF0000" ),
        // Pilot Avatar will be used as Embed Thumbnail
        "thumbnail" => [ "url" => $avatar ],
       // Pilot In Command will be used as the Author of the message
        "author" => [
          "name" => "Pilot In Command: ".$pirep->user->name_private,
          "url" => route('frontend.profile.show', [$pirep->user->id]),
        ],
        // Additional Fields (Rows can display max 3 items)
        "fields" => [
          // Flight Number
          [
            "name" => "__Flight #__",
            "value" => $pirep->airline->code.$pirep->flight_number,
            "inline" => true
          ],
          // Departure
          [
            "name" => "__Origin__",
            "value" => $pirep->dpt_airport_id,
            "inline" => true
          ],
          // Arrival
          [
            "name" => "__Destination__",
            "value" => $pirep->arr_airport_id,
            "inline" => true
          ],
          // Distance
          [
            "name" => "__Distance__",
            "value" => $pirep->distance." nm",
            "inline" => true
          ],
          // Block Time
          [
            "name" => "__Block Time__",
            "value" => Dispo_TimeConvert($pirep->flight_time),
            "inline" => true
          ],
            // Equipment
          [
            "name" => "__Equipment__",
            "value" => $pirep_aircraft,
            "inline" => true
          ],
        ],
        // End Fields Array
      ],
      // End Embeds Array
      ]
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
    $ch = curl_init($webhookurl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    if($response) {
      Log::debug('Discord WebHook | Pirep Msg Response: '.$response);
    }
    curl_close($ch);
  }
}
