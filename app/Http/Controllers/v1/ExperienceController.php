<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ExperienceResource;
use App\Models\Experience;
use App\Http\Requests\v1\experience\StoreExperienceRequest;
use App\Http\Requests\v1\experience\UpdateExperienceRequest;
use App\Traits\APIResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Request;

class ExperienceController extends Controller
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
    public function store(StoreExperienceRequest $request)
    {
        // --- validated request ---
        $validated = $request->validated();

        // --- handle employer image file uploade ---
        if (isset($validated['employer']['image'])) {
            $file = $validated['employer']['image'];
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $fileName, 'public'); // please specify the 'public' as the disk since it's not the default disk
        }

        // --- saving in db ---
        $experience = Experience::create([
            "user_id" => Auth::user()->id,
            "role" => $validated["role"],
            "major" => $validated["major"],
            "description" => $validated["description"],
            "start_date" => $validated["startDate"],
            "end_date" => $validated['endDate'] ?? null,
            "employer_name" => $validated["employer"]["name"],
            "employer_reference" => $validated["employer"]["refName"] ?? null,
            "employer_reference_contact" => $validated["employer"]["refContact"] ?? null,
            "employer_image_path" => $path,
            "employer_website" => $validated["employer"]["website"],
        ]);

        // --- logging ---
        Log::stack(['single', 'devLog', 'activityLog'])
            ->debug(
                'experience record created',
                [
                    'user-id' => Auth::user()->id,
                    'record-id' => $experience->id
                ]
            );

        // --- returning new experience data ---
        return $this->success(ExperienceResource::make($experience), 200, 'experience record successfully created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Experience $experience)
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
        $experience = Experience::where("major", $major)->get();

        // --- logging ---
        Log::stack(['single', 'devLog', 'activityLog'])
            ->debug(
                'experience record(s) fetched'
            );

        return $this->success(ExperienceResource::collection($experience), 200, "success");
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExperienceRequest $request, Experience $experience, string $major, string $id)
    {
        // --- validated request ---
        $validated = $request->validated();

        // --- look up experience record of user with specified major
        $experience = Experience::where("major", $major)->where("id", $id)->first();

        // --- update record ---
        // -- basic fields --
        $experience->fill($validated);
        // -- nested fields --
        if (isset($validated['employer'])) {
            $experience->employer_name = $validated['employer']['name'] ?? $experience->employer_name;
            $experience->employer_reference = $validated['employer']['refName'] ?? $experience->employer_ref_name;
            $experience->employer_reference_contact = $validated['employer']['refContact'] ?? $experience->employer_ref_contact;
            $experience->employer_website = $validated['employer']['website'] ?? $experience->employer_website;

            if (isset($validated['employer']['image']) && $request->hasFile('employer.image')) {
                $file = $request->file('employer.image');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('images', $filename, 'public');
                $experience->employer_image_path = $path;
            }
        }
        // -- saving changes in db --
        $experience->save();

        // --- logging ---
        Log::stack(['single', 'devLog', 'activityLog'])
            ->debug(
                'experience record updated',
                [
                    'user-id' => Auth::user()->id,
                    'record-id' => $experience->id,
                    'columns-updated' => array_keys($validated)
                ]
            );

        return $this->success(ExperienceResource::make($experience), 200, "experience record updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Experience $experience)
    {
        //
    }
}
