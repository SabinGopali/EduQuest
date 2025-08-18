<?php

namespace App\Http\Controllers;

use App\Models\CourseDetail;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseDetailController extends Controller
{

    public function index()
    {
        return view('coursedetail.create');
    }

    public function courseDetailCreateForm()
    {
        $courses = Course::all();
        return view('college/courseDetailForm',['courses'=>$courses]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'college_id' => 'required|exists:colleges,id',
            'description' => 'required|string',
            'tuition_fee' => 'nullable|numeric',
            'seats' => 'nullable|integer',
            'eligibility' => 'nullable|string',
            'admission_process' => 'nullable|string',
            'placement' => 'nullable|string',
            'scholarship' => 'nullable|string',
            'hostel' => 'nullable|boolean',
            'application_deadline' => 'nullable|date',
        ]);

        $data['course_id'] = $request->input('course_id');
        $data['college_id'] = $request->input('college_id');
        $data['description'] = $request->input('description');
        $data['tuition_fee'] = $request->input('tuition_fee');
        $data['seats'] = $request->input('seats');
        $data['eligibility'] = $request->input('eligibility');
        $data['admission_process'] = $request->input('admission_process');
        $data['placement'] = $request->input('placement');
        $data['scholarship'] = $request->input('scholarship');
        $data['hostel'] = $request->input('hostel');
        $data['application_deadline'] = $request->input('application_deadline');
        $data['status'] = 'PENDING';

        CourseDetail::create($data);

        // return view('/college/course-detail');
        return redirect('/college/course-detail');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CourseDetail  $courseDetail
     * @return \Illuminate\Http\Response
     */
    public function show(CourseDetail $courseDetail)
    {
        $courseDetails = CourseDetail::with(['course', 'college'])->get();
        return view('home.coursedetailshow',['courseDetails'=>$courseDetails]);
    }

    // public function showForCollege($id)
    // {
    //     $courseDetails=CourseDetail::all();
    //     return view('college.coursedetailshow',['courseDetails'=>$courseDetails]);
    // }

    public function showForCollege()
    {
        // Get the currently logged-in college
        $college = Auth::guard('college')->user();

        // Retrieve the course details for the logged-in college
        $courseDetails = CourseDetail::with(['course', 'college'])
            ->where('college_id', $college->id)
            ->get();

        return view('college.coursedetailshow', ['courseDetails' => $courseDetails, 'college' => $college]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CourseDetail  $courseDetail
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $courseDetail=CourseDetail::find($id);
        return view('home.coursedetailedit', ['courseDetail'=>$courseDetail]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseDetail  $courseDetail
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, CourseDetail $courseDetail, $id)
    // {
    //     {
    //         $courseDetail=CourseDetail::find($id);
    //         $courseDetail->id=$id;
    //         $courseDetail->description=$request->get('description');
    //         $courseDetail->college_id=$request->get('college_id');
    //         $courseDetail->course_id=$request->get('course_id');


    //         $courseDetail->save();

    //         return redirect('coursedetail/show');
    //     }
    // }

    public function update(Request $request, $id)
    {
        $courseDetail = CourseDetail::find($id);
        $validated = $request->validate([
            'description' => 'required|string',
            'tuition_fee' => 'nullable|numeric',
            'seats' => 'nullable|integer',
            'eligibility' => 'nullable|string',
            'admission_process' => 'nullable|string',
            'placement' => 'nullable|string',
            'scholarship' => 'nullable|string',
            'hostel' => 'nullable|boolean',
            'application_deadline' => 'nullable|date',
        ]);

        $courseDetail->fill($validated);
        $courseDetail->save();

        return redirect()->route('coursedetail.show');
    }

    public function updateForCollege(Request $request, $id)
    {
        $courseDetail = CourseDetail::find($id);
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'description' => 'required|string',
            'tuition_fee' => 'nullable|numeric',
            'seats' => 'nullable|integer',
            'eligibility' => 'nullable|string',
            'admission_process' => 'nullable|string',
            'placement' => 'nullable|string',
            'scholarship' => 'nullable|string',
            'hostel' => 'nullable|boolean',
            'application_deadline' => 'nullable|date',
        ]);

        $courseDetail->course_id = $request->input('course_id');
        $courseDetail->fill($validated);
        $courseDetail->save();

        return redirect()->route('college.coursedetail.show');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourseDetail  $courseDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseDetail $courseDetail, $id)
    {
        $courseDetail=CourseDetail::find($id);
        $courseDetail->delete();
        return redirect('/college/course-detail');
    }

    public function destroyForAdmin(CourseDetail $courseDetail, $id)
    {
        $courseDetail=CourseDetail::find($id);
        $courseDetail->delete();
        return redirect('/admin/course-detail/show');
    }

    public function getById($id)
    {
        $courseDetail = CourseDetail::with(['college', 'course'])->find($id);
        if (!$courseDetail || ($courseDetail->status ?? 'PENDING') !== 'APPROVED' || ($courseDetail->college->status ?? 'PENDING') !== 'APPROVED') {
            abort(404);
        }
        return view('home.viewdes', compact('courseDetail'));
    }

    // public function getCollegeByCourseId($id)
    // {
    //     // return view('home.courseCollegeView', compact('college'));
    //     return view('home.courseCollegeView');
    // }

    public function getCollegeByCourseId($id)
    {
        // Assuming $courseId is the course_id you want to filter by
        $courseDetails = CourseDetail::with(['college', 'course'])
            ->where('course_id', $id)
            ->whereHas('college', function ($q) {
                $q->where('status', 'APPROVED');
            })
            ->get();

        // You can return the course details or do something else with them
        return view('home.courseCollegeView', compact('courseDetails'));
    }

    public function editCourseDetail($id)
    {
        // Find the course detail by its ID
        $courseDetail = CourseDetail::find($id);

        // Get the list of available courses for the dropdown
        $courses = Course::all();

        // Check if the course detail exists
        if (!$courseDetail) {
            return redirect()->route('college.coursedetail.show')->with('error', 'Course Detail not found');
        }

        // Pass the course detail and course list to the editCourseDetail view
        return view('college.courseDetailEditForm', ['courseDetail' => $courseDetail, 'courses' => $courses]);
    }

    public function approve($id)
    {
        $courseDetail = CourseDetail::findOrFail($id);
        $courseDetail->status = 'APPROVED';
        $courseDetail->save();

        return redirect()->back()->with('success', 'Course detail approved successfully.');
    }

}