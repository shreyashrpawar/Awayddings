<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventManagementResource;
use App\Http\Resources\EventResource;
use App\Models\Artist;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\LightandSound;
use App\Models\TimeSlot;
use Symfony\Component\VarDumper\Cloner\Data;

class EventManagementController extends Controller
{
    public function event_details(Request $request)
    {
        $event = Event::with(['decorations','artists'])->where('status', 1)->get();

        $time_slot = TimeSlot::where('status', 1)->get();

        $light_sound = LightandSound::where('status', 1)->get();

        $additional_artist = Artist::where('status', 1)->get();

        $data =[
            'event' => $event,
            'light_decor' =>$light_sound,
            'time_slots' => $time_slot,
            'additional_artist' => $additional_artist
        ];

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => new EventManagementResource($data)
        ]);
    }
}
