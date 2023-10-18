<?php

namespace App\Http\Controllers;

use App\Models\Peertje;
use App\Models\PeertjeLocation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PeertjesController extends Controller
{

    public function show() {
        $peertjes = Peertje::all();

        return Inertia::render('Peertjes', [
            'peertjes' => $peertjes,
        ]);
    }

    public function postLocation(Peertje $peertje, Request $request) {

        $validated = $request->validate([
            'secret' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if($validated['secret'] !== config('peertjes.secret')) {
            return response()->json([
                'message' => 'Invalid secret',
            ], 403);
        }

        $location = new PeertjeLocation();
        $location->latitude = $validated['latitude'];
        $location->longitude = $validated['longitude'];
        $location->peertje_id = $peertje->id;
        $location->save();


        return response()->json([
            'message' => 'Location saved',
        ]);
    }
}
