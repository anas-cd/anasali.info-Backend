<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\HobbyResource;
use App\Models\Hobby;
use App\Http\Requests\v1\hobby\StoreHobbyRequest;
use App\Http\Requests\v1\hobby\UpdateHobbyRequest;
use App\Traits\APIResponseTrait;
use Illuminate\Support\Facades\Auth;
use Request;

class HobbyController extends Controller
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
    public function store(StoreHobbyRequest $request)
    {
        // --- validated request ---
        $validated = $request->validated();

        // --- handle hobby image file uploade ---
        if (isset($validated['image'])) {
            $file = $validated['image'];
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $fileName, 'public'); // please specify the 'public' as the disk since it's not the default disk
        }

        // --- saving in db ---
        $hobby = Hobby::create([
            "user_id" => Auth::user()->id,
            "major" => $validated["major"],
            "name" => $validated["name"],
            "image_path" => $path
        ]);

        // --- returning new hobby data ---
        return $this->success(HobbyResource::make($hobby), 200, 'hobby record successfully created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Hobby $hobby)
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
        $hobby = Hobby::where("major", $major)->get();

        return $this->success(HobbyResource::collection($hobby), 200, "success");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hobby $hobby)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHobbyRequest $request, Hobby $hobby, string $major, string $id)
    {
        // --- validated request ---
        $validated = $request->validated();
        // --- look up hobby record of user with specified major
        $hobby = Hobby::where("major", $major)->where("id", $id)->first();
        // --- update record ---
        $hobby->fill($validated);

        if (isset($validated['image']) && $request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $filename, 'public');
            $hobby->image_path = $path;
        }

        // -- saving changes in db --
        $hobby->save();

        return $this->success(HobbyResource::make($hobby), 200, "hobby record updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hobby $hobby)
    {
        //
    }
}
