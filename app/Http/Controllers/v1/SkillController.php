<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\SkillCollection;
use App\Http\Resources\v1\SkillResource;
use App\Models\Skill;
use App\Http\Requests\v1\skill\StoreSkillRequest;
use App\Http\Requests\v1\skill\UpdateSkillRequest;
use App\Traits\APIResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Request;

class SkillController extends Controller
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
    public function store(StoreSkillRequest $request)
    {
        // --- validated request ---
        $validated = $request->validated();

        // --- saving in db ---
        $skill = Skill::create([
            "user_id" => Auth::user()->id,
            "label" => $validated["label"],
            "description" => $validated["description"],
            "icon" => $validated["icon"],
            "type" => $validated["type"],
            "major" => $validated["major"]
        ]);

        // --- logging ---
        Log::stack(['single', 'devLog', 'activityLog'])
            ->debug(
                'skill record created',
                [
                    'user-id' => Auth::user()->id,
                    'record-id' => $skill->id
                ]
            );

        // --- returning new skill data ---
        return $this->success(SkillResource::make($skill), 200, 'skill record successfully created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Skill $skill)
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
        $skills = Skill::where("major", $major)->get();

        // --- logging ---
        Log::stack(['single', 'devLog', 'activityLog'])
            ->debug(
                'skill record(s) fetched'
            );

        return $this->success(new SkillCollection($skills), 200, "success");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Skill $skill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSkillRequest $request, Skill $skill, string $major, string $id)
    {
        // --- validated request ---
        $validated = $request->validated();

        // --- look up skill record ---
        $skill = Skill::findOrFail($id);

        // --- updating db ---
        $skill->update($validated);

        // --- logging ---
        Log::stack(['single', 'devLog', 'activityLog'])
            ->debug(
                'tech record updated',
                [
                    'user-id' => Auth::user()->id,
                    'record-id' => $skill->id,
                    'columns-updated' => array_keys($validated)
                ]
            );

        return $this->success(SkillResource::make($skill), 200, "skill record updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skill $skill)
    {
        //
    }
}
