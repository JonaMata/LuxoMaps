<?php

namespace App\Http\Controllers;

use App\Models\Peertje;
use App\Models\PeertjeLocation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PeertjesController extends Controller
{

    public function show() {
        $peertjes = Peertje::query()->whereHas('locations', function ($query) {
            $query->where('created_at', '>', now()->subDays(7));
        })->get();

        return Inertia::render('Peertjes', [
            'peertjes' => $peertjes,
        ]);
    }

    public function list() {
        $peertjes = Peertje::all();

        return Inertia::render('Peertjes/List', [
            'peertjes' => $peertjes,
        ]);
    }

    public function create(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $peertje = new Peertje();
        $peertje->name = $validated['name'];
        $peertje->save();

        return redirect()->route('peertjes.list');
    }

    public function destroy(Request $request) {
        $validated = $request->validate([
            'id' => 'required|integer|exists:peertjes,id',
        ]);

        $peertje = Peertje::query()->findOrFail($validated['id']);
        $peertje->delete();

        return redirect()->route('peertjes.list');
    }

    public function postLocation(Peertje $peertje, Request $request) {

        $validated = $request->validate([
            'secret' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'battery_percentage' => 'required|numeric|between:0,100',
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
        $location->battery_percentage = $validated['battery_percentage'];
        $location->save();


        return response()->json([
            'message' => 'Location saved',
        ]);
    }
}
