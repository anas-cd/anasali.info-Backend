<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\ProjectResource;
use App\Models\Project;
use App\Http\Requests\v1\project\StoreProjectRequest;
use App\Http\Requests\v1\project\UpdateProjectRequest;
use App\Traits\APIResponseTrait;
use Illuminate\Support\Facades\Auth;
use Request;

class ProjectController extends Controller
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
    public function store(StoreProjectRequest $request)
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
        $project = Project::create([
            "user_id" => Auth::user()->id,
            "major" => $validated["major"],
            "name" => $validated["name"],
            "image_path" => $path,
            "repo_url" => $validated["codeLink"],
            "demo_url" => $validated["demoLink"],
            "description" => $validated["description"],
            "tags" => $validated["tags"]
        ]);

        // --- returning new project data ---
        return $this->success(ProjectResource::make($project), 200, 'project record successfully created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
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
        $project = Project::where("major", $major)->get();

        return $this->success(ProjectResource::collection($project), 200, "success");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project, string $major, string $id)
    {
        // --- validated request ---
        $validated = $request->validated();
        // --- look up project record of user with specified major
        $project = Project::where("major", $major)->where("id", $id)->first();
        // --- update record ---
        $project->fill($validated);

        if (isset($validated['image']) && $request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $filename, 'public');
            $project->image_path = $path;
        }

        // -- saving changes in db --
        $project->save();

        return $this->success(projectResource::make($project), 200, "project record updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
