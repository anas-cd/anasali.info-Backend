<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ProfileResource;
use App\Models\Profile;
use App\Http\Requests\v1\profile\StoreprofileRequest;
use App\Http\Requests\v1\profile\UpdateprofileRequest;
use App\Traits\APIResponseTrait;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;
use Request;

class ProfileController extends Controller
{
    use APIResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return $this->success(ProfileResource::collection(Profile::all()), 200, 'success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreprofileRequest $request)
    {
        // --- validated request ---
        $validated = $request->validated();

        // --- saving in db ---
        $profile = Profile::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "speciality" => $validated["speciality"],
            "phone" => $validated["phone"],
            "biography" => $validated["biography"],
            "social" => $validated["social"],
            "resume_link" => $validated["resume_link"],
            "major" => $validated["major"],
            "user_id" => Auth::user()->id,
        ]);

        // --- logging ---
        Log::stack(['single', 'devLog', 'activityLog'])
            ->debug(
                'profile record created',
                [
                    'user-id' => Auth::user()->id,
                    'record-id' => $profile->id
                ]
            );

        // --- returning new profile data ---
        return $this->success(ProfileResource::make($profile), 200, 'profile successfully created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(profile $profile)
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
        $profile = Profile::where("major", $major)->first();

        // --- logging ---
        Log::stack(['single', 'devLog', 'activityLog'])
            ->debug(
                'profile record(s) fetched'
            );

        return $this->success(ProfileResource::make($profile), 200, "success");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateprofileRequest $request, string $major)
    {
        // --- validate request data ---
        $validated = $request->validated();

        // --- look up profile of user with specified major ---
        $profile = Auth::user()->profiles()->where("major", $major)->first();

        // --- update profile with new data ---
        $profile->update($validated);

        // --- logging ---
        Log::stack(['single', 'devLog', 'activityLog'])
            ->debug(
                'profile record updated',
                [
                    'user-id' => Auth::user()->id,
                    'record-id' => $profile->id,
                    'columns-updated' => array_keys($validated)
                ]
            );

        return $this->success(ProfileResource::make($profile), 200, "profile updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(profile $profile)
    {
        //
    }
}
