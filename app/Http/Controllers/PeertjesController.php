<?php

namespace App\Http\Controllers;

use App\Models\Peertje;
use App\Models\PeertjeLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PeertjesController extends Controller
{
    public function index() {
        $this->authorize('viewAny', Peertje::class);

        $peertjes = Peertje::query()->whereHas('locations', function ($query) {
            $query->where('created_at', '>', now()->subDays(7));
        })->get();

        return Inertia::render('Peertjes', [
            'peertjes' => $peertjes,
        ]);
    }

    public function show(Peertje $peertje) {
        $this->authorize('update', $peertje);


        return Inertia::render('Peertjes/Show', [
            'peertje' => $peertje,
        ]);
    }

    public function update(Request $request, Peertje $peertje) {
        $this->authorize('update', $peertje);

        $validated = $request->validate([
            'name' => 'required|string',
            'api_id' => 'integer|between:0,7|unique:peertjes',
        ]);

        $peertje->update($validated);

        return redirect()->back();
    }

    public function list() {
        if(!\Auth::user()->can('manage-peertjes')) {
            abort(403, 'Je hebt niet de rechten om peertjes te bewerken.');
        }

        $peertjes = Peertje::all();

        return Inertia::render('Peertjes/List', [
            'peertjes' => $peertjes,
        ]);
    }

    public function create(Request $request) {
        $this->authorize('create', Peertje::class);

        $validated = $request->validate([
            'name' => 'required|string',
            'api_id' => 'integer|between:0,7|unique:peertjes',
        ]);

        $peertje = new Peertje($validated);
        $peertje->save();

        return redirect()->back();
    }

    public function destroy(Request $request) {

        $validated = $request->validate([
            'id' => 'required|integer|exists:peertjes,id',
        ]);

        $peertje = Peertje::query()->findOrFail($validated['id']);

        $this->authorize('delete', $peertje);

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
