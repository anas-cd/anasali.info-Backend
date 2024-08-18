<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\InterestResource;
use App\Models\Interest;
use App\Http\Requests\v1\interest\StoreInterestRequest;
use App\Http\Requests\v1\interest\UpdateInterestRequest;
use App\Traits\APIResponseTrait;
use Illuminate\Support\Facades\Auth;
use Request;

class InterestController extends Controller
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
    public function store(StoreInterestRequest $request)
    {
        // --- validated request ---
        $validated = $request->validated();

        // --- handle interest image file uploade ---
        $path = null;
        if (isset($validated['image'])) {
            $file = $validated['image'];
            $fileName = time() . '-interests.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $fileName, 'public'); // please specify the 'public' as the disk since it's not the default disk
        }

        // --- saving in db ---
        $interest = Interest::create([
            "user_id" => Auth::user()->id,
            "major" => $validated["major"],
            "name" => $validated["name"],
            "sub_text" => $validated["subText"] ?? null,
            "description" => $validated["description"],
            "url" => $validated["link"] ?? null,
            "image_path" => $path ?? null
        ]);

        // --- returning new hobby data ---
        return $this->success(InterestResource::make($interest), 200, 'interest record successfully created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Interest $interest)
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
        $interest = Interest::where("major", $major)->get();

        return $this->success(InterestResource::collection($interest), 200, "success");
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Interest $interest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInterestRequest $request, Interest $interest, string $major, string $id)
    {
        // --- validated request ---
        $validated = $request->validated();
        // --- look up hobby record of user with specified major
        $interest = Interest::where("major", $major)->where("id", $id)->first();
        // --- update record ---
        $interest->fill($validated);

        if (isset($validated['image']) && $request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $filename, 'public');
            $interest->image_path = $path;
        }

        // -- saving changes in db --
        $interest->save();

        return $this->success(InterestResource::make($interest), 200, "interest record updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Interest $interest)
    {
        //
    }
}
