<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\EducationResource;
use App\Models\Education;
use App\Http\Requests\v1\education\StoreEducationRequest;
use App\Http\Requests\v1\education\UpdateEducationRequest;
use App\Traits\APIResponseTrait;
use Illuminate\Support\Facades\Auth;
use Request;

class EducationController extends Controller
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
    public function store(StoreEducationRequest $request)
    {
        // --- validated request ---
        $validated = $request->validated();

        // --- saving in db ---
        $education = Education::create([
            "user_id" => Auth::user()->id,
            "name" => $validated["name"],
            "source" => $validated["source"],
            "country" => $validated["country"],
            "major" => $validated["major"],
            "date" => array_key_exists("date", $validated) ? $validated["date"] : '2022-07-01 00:00:00',
            "courses" => $validated["courses"],
        ]);

        // --- returning new eduation data ---
        return $this->success(EducationResource::make($education), 200, 'education record successfully created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Education $education)
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
        $education = Education::where("major", $major)->first();
        return $this->success(EducationResource::make($education), 200, "success");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEducationRequest $request, Education $education, string $major)
    {
        // --- validated request ---
        $validated = $request->validated();

        // --- look up education record of user with specified major ---
        $education = Auth::user()->educations()->where("major", $major)->first();

        // --- update record ---
        $education->update($validated);

        return $this->success(EducationResource::make($education), 200, "education record updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Education $education)
    {
        //
    }
}
