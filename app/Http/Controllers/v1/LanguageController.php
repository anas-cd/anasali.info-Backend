<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\LanguageResource;
use App\Models\language;
use App\Http\Requests\v1\language\StorelanguageRequest;
use App\Http\Requests\v1\language\UpdatelanguageRequest;
use App\Traits\APIResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Request;

class LanguageController extends Controller
{
    use APIResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorelanguageRequest $request)
    {
        // --- validated request ---
        $validated = $request->validated();

        // --- saving in db ---
        $language = Language::create([
            "user_id" => Auth::user()->id,
            "major" => $validated["major"],
            "language" => $validated["lang"],
            "level" => $validated["level"]
        ]);

        // --- logging ---
        Log::stack(['single', 'devLog', 'activityLog'])
            ->debug(
                'language record created',
                [
                    'user-id' => Auth::user()->id,
                    'record-id' => $language->id
                ]
            );

        // --- returning new hobby data ---
        return $this->success(LanguageResource::make($language), 200, 'language record successfully created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(language $language)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function showByMajor(Request $request, string $major)
    {
        /**
         * NOTE: this is not linked to any user since there is only one user.
         */
        $languages = Language::where("major", $major)->get();

        // --- logging ---
        Log::stack(['single', 'devLog', 'activityLog'])
            ->debug(
                'language record(s) fetched'
            );

        return $this->success(LanguageResource::collection($languages), 200, "success");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(language $language)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatelanguageRequest $request, language $language, string $major, string $id)
    {
        // --- validated request ---
        $validated = $request->validated();
        // --- look up language record of user with specified major and id (redundent currently)
        $language = Language::where("major", $major)->where("id", $id)->first();
        // --- update record ---
        // -- auto set matching data --
        $language->fill($validated);
        // -- set custom data --
        if (isset($validated["lang"])) {
            $language->language = $validated["lang"];
        }

        // -- save changes in db --
        $language->save();

        // --- logging ---
        Log::stack(['single', 'devLog', 'activityLog'])
            ->debug(
                'language record updated',
                [
                    'user-id' => Auth::user()->id,
                    'record-id' => $language->id,
                    'columns-updated' => array_keys($validated)
                ]
            );

        return $this->success(LanguageResource::make($language), 200, "language record updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(language $language)
    {
        //
    }
}
