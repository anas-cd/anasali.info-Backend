<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CourseResource;
use App\Models\Course;
use App\Http\Requests\v1\course\StoreCourseRequest;
use App\Http\Requests\v1\course\UpdateCourseRequest;
use App\Traits\APIResponseTrait;
use Illuminate\Support\Facades\Auth;
use Request;

class CourseController extends Controller
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
    public function store(StoreCourseRequest $request)
    {
        // --- validated request ---
        $validated = $request->validated();

        // --- saving in db ---
        $course = Course::create([
            "user_id" => Auth::user()->id,
            "name" => $validated["name"],
            "major" => $validated["major"],
            "description" => $validated["description"],
            "source_url" => $validated["sourceLink"],
            "source_name" => $validated["sourceName"]
        ]);

        // --- returning new eduation data ---
        return $this->success(CourseResource::make($course), 200, 'course record successfully created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
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
        $courses = Course::where("major", $major)->get();
        return $this->success(CourseResource::collection($courses), 200, "success");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course, string $major, string $id)
    {
        // --- validated request ---
        $validated = $request->validated();

        // --- look up course record of user with specified major ---
        $course = Course::where("major", $major)->where("id", $id)->first();

        // --- update record ---
        $course->update($validated);

        return $this->success(CourseResource::make($course), 200, "education record updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }
}
