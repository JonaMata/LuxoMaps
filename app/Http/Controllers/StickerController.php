<?php

namespace App\Http\Controllers;

use App\Models\Sticker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StickerController extends Controller
{
    public function show() {
        $stickers = Sticker::all();

        return Inertia::render('Stickers', [
            'stickers' => $stickers,
        ]);
    }

    public function embed() {
        $stickers = Sticker::all();

        return Inertia::render('StickersEmbed', [
            'stickers' => $stickers,
        ]);
    }

    public function create(Request $request) {
        $this->authorize('create', Sticker::class);

        $validated = $request->validate([
            'lat' => ['required', 'numeric', 'min:-90', 'max:90'],
            'lng' => ['required', 'numeric', 'min:-180', 'max:180'],
        ]);

        $sticker = new Sticker([
            'latitude' => $validated['lat'],
            'longitude' => $validated['lng'],
        ]);
        $sticker->user()->associate(Auth::user());
        $sticker->save();

        return to_route('home');
    }

    public function delete(Sticker $sticker) {
        $this->authorize('delete', $sticker);
        $sticker->delete();
        return to_route('home');
    }
}
