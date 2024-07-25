<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\TechStackCollection;
use App\Http\Resources\v1\TechStackResource;
use App\Models\TechStack;
use App\Http\Requests\v1\techStack\StoreTechStackRequest;
use App\Http\Requests\v1\techStack\UpdateTechStackRequest;
use App\Traits\APIResponseTrait;
use Illuminate\Support\Facades\Auth;
use Request;

class TechStackController extends Controller
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
    public function store(StoreTechStackRequest $request)
    {
        // --- validated request ---
        $validated = $request->validated();

        // --- handle tech image file uploade ---
        if (isset($validated['img'])) {
            $file = $validated['img'];
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $fileName, 'public'); // please specify the 'public' as the disk since it's not the default disk
        }

        // --- saving in db ---
        $tech = TechStack::create([
            "user_id" => Auth::user()->id,
            "major" => $validated["major"],
            "label" => $validated["label"],
            "category" => $validated["category"],
            "type" => $validated["type"] ?? null,
            "url" => $validated["link"],
            "progress" => $validated["progress"] ?? null,
            "tip" => $validated["tip"] ?? null,
            "image_path" => $path,
        ]);

        // --- returning new tech data ---
        return $this->success(TechStackResource::make($tech), 200, 'tech record successfully created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TechStack $techStack)
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
        $tech = TechStack::where("major", $major)->get();

        return $this->success(new TechStackCollection($tech), 200, "success");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TechStack $techStack)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTechStackRequest $request, TechStack $techStack, string $major, string $id)
    {
        // --- validated request ---
        $validated = $request->validated();
        // --- look up tech record of user with specified major
        $tech = TechStack::where("major", $major)->where("id", $id)->first();
        // --- update record ---
        $tech->fill($validated);

        if (isset($validated['img']) && $request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $filename, 'public');
            $tech->image_path = $path;
        }

        // -- saving changes in db --
        $tech->save();

        return $this->success(TechStackResource::make($tech), 200, "tech record updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TechStack $techStack)
    {
        //
    }
}
