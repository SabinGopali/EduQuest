<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('admin.course');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function courseStore(Request $request)
    {
            $course = new Course();
            $course->name = $request->fname;
            $course->stream = $request->stream;
            $course->gpa_limit = $request->gpalimit;
            $course->shortName = $request->sname;
            $course->substream = $request->substream;
            $course->duration = $request->duration;
            $course->description = $request->description;
            $saved = $course->save();
            if($saved){
                return redirect()->route('admin.course.index')->with('message', 'category successfully added');
            }
            else{
                return redirect()->back()->with('message', 'category could not be add');
            }
        }

    public function courseShow(){
        $course=Course::all();
        return view('admin.courseshow', ['course'=>$course]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        $course=Course::all();
        return view('admin.courseshow',['course'=>$course]);
    }

    public function showForStudent(Course $course)
    {
        $course = Course::whereHas('courseDetails', function ($q) {
                $q->where('status', 'APPROVED')
                  ->whereHas('college', function ($c) {
                      $c->where('status', 'APPROVED');
                  });
            })
            ->get();
        // return view('home.courses',['course'=>$course]);
        return view('home.courses', compact('course'));
    }
    public function showForCollege(Course $course)
    {
        $course=Course::all();
        // return view('home.courses',['course'=>$course]);
        return view('college.course-show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //
    }
    public function getById($id)
    {
        $course = Course::find($id);
        return view('admin.viewcourse', compact('course'));
    }
    public function getByIdforStudent($id)
    {
        $course = Course::find($id);
        return view('home.viewcoursedes', compact('course'));
    }
    public function getByIdForCollege($id)
    {
        $course = Course::find($id);
        return view('college.view-course', compact('course'));
    }


}