<?php

namespace App\Http\Controllers;

use App\Models\PikBal;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Nette\Utils\Image;
use Storage;

class PikBalController extends Controller
{
    function show() {
        return Inertia::render('CaptchaCheck');
    }

    function get()
    {
        $pikBals = PikBal::query()->select('id')->inRandomOrder()->limit(9)->get()->pluck('id')->toArray();
        $check = mt_rand(0, 1) === 0 ? 'pik' : 'bal';

        return response()->json([
            'check' => $check,
            'pikBals' => $pikBals,
        ]);
    }

    function image(PikBal $pikbal)
    {
        return response()->file(storage_path('app/'.$pikbal->path));
    }

    function check(Request $request) {
        $request->validate([
            'check' => 'required|in:pik,bal',
            'pikbals' => 'required|array|size:9',
            'selected' => 'array|between:0,9',
        ]);
        foreach ($request->pikbals as $pikbal) {
            $pikBal = PikBal::query()->find($pikbal);
            if ($pikBal->type !== $request->check && in_array($pikBal->id, $request->selected) ||
                $pikBal->type === $request->check && !in_array($pikBal->id, $request->selected)) {
                return response()->json([
                    'success' => false,
                ]);
            }
        }

        return response()->json([
            'success' => true,
        ]);
    }

    function create()
    {
        return Inertia::render('PikBal/Create');
    }

    function store(Request $request)
    {
        $request->validate([
            'pikbal' => 'required|file',
            'type' => 'required|in:pik,bal',
        ]);

        $filePath = $request->file('pikbal')->storeAs('tmp', 'upload');
        try {
            $image = Image::fromFile(storage_path('app/'.$filePath));
            $image->resize(200, 200, Image::Cover);
            $newPath = 'pikbals/'.str()->random(40);
            $image->save(storage_path('app/'.$newPath), 80, Image::JPEG);

            $pikBal = new PikBal();
            $pikBal->path = $newPath;
            $pikBal->filetype = $request->file('pikbal')->getMimeType();
            $pikBal->type = $request->type;
            $pikBal->save();
        } catch (\Exception $e) {
            Storage::delete($filePath);
            throw $e;
        }


        return redirect()->back();
    }
}
