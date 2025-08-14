<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\College;
use App\Models\Inquiry;
use App\Models\Students;
use App\Models\CourseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('home.inquiry');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function inquiryFormStore(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'student_id' => 'required|integer',
            'college_id' => 'required|integer',
            'coursedetail_id' => 'required|integer',
            'message' => 'required|string|max:255', // Added max length for safety
        ]);

        // Create a new Inquiry record
        $inquiry = new Inquiry();
        $inquiry->student_id = $validatedData['student_id'];
        $inquiry->college_id = $validatedData['college_id'];
        $inquiry->coursedetail_id = $validatedData['coursedetail_id'];
        $inquiry->message = $validatedData['message'];

        // Save the Inquiry record and handle success/failure
        if ($inquiry->save()) {
            return redirect()->route('algorithm.recommend')->with('message', 'Inquiry successfully added');
        } else {
            return redirect()->back()->with('error', 'Failed to add the inquiry');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inquiry  $inqyiry
     * @return \Illuminate\Http\Response
     */
    public function show(Inquiry $inquiry)
        {

            $inquiry=Inquiry::all();
            return view('home.inquiryshow',['inquiry'=>$inquiry]);
        }

    public function showForAdmin(Inquiry $inquiry)
        {

            $inquiry=Inquiry::all();
            return view('admin.inquiryshow',['inquiry'=>$inquiry]);
        }

    public function showForCollege(Inquiry $inquiry)
    {
        $currentCollege = Auth::guard('college')->user();

        $inquiries = Inquiry::whereHas('courseDetail.college', function ($query) use ($currentCollege) {
            $query->where('colleges.id', $currentCollege->id);
        })->orderBy('created_at', 'desc')->get();

        return view('college.inquiry', ['inquiries' => $inquiries]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Inqyiry  $inqyiry
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inquiry=Inquiry::find($id);
        return view('home.inquiryedit', ['inquiry'=>$inquiry]);
    }
    public function editForCollege($id)
    {
        $inquiry=Inquiry::find($id);
        return view('college.inquiryedit', ['inquiry'=>$inquiry]);
    }
    public function editForAdmin($id)
    {
        $inquiry=Inquiry::find($id);
        return view('admin.inquiryedit', ['inquiry'=>$inquiry]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'message' => 'nullable|string',
            'reply' => 'nullable|string',
        ]);

        $inquiry = Inquiry::find($id);

        if (!$inquiry) {
            return redirect()->back()->with('error', 'Inquiry not found.');
        }

        $updateData = [];
        if (array_key_exists('message', $validatedData)) {
            $updateData['message'] = $validatedData['message'];
        }
        if (array_key_exists('reply', $validatedData)) {
            $updateData['reply'] = $validatedData['reply'];
        }

        if (!empty($updateData)) {
            $inquiry->update($updateData);
        }

        if (\Illuminate\Support\Facades\Auth::guard('college')->check()) {
            return redirect()->route('college.inquiryshow')->with('success', 'Inquiry updated successfully.');
        } elseif (\Illuminate\Support\Facades\Auth::guard('student')->check()) {
            return redirect()->route('student.getInquiries')->with('success', 'Inquiry updated successfully.');
        } else {
            return redirect()->route('inquiry.showForAdmin')->with('success', 'Inquiry updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inqyiry  $inqyiry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inquiry $inquiry, $id)
    {
        $college = Auth::guard('college')->user();
        $student = Auth::guard('student')->user();

        $inquiry=Inquiry::find($id);
        $inquiry->delete();
        if ($college) {
            // College is present, redirect to college inquiries route
            return redirect()->route('college.inquiryshow')->with('success', 'Inquiry deleted successfully');
        } else {
            // College is not present, redirect to student inquiries route
            return redirect()->route('student.getInquiries')->with('success', 'Inquiry deleted successfully');
        }
    }
    public function makeInquiry($id){
        if (!Auth::guard('student')->check()) {
            return redirect()->route('student.loginFrom')->with('error', 'Please log in to create an inquiry.');
        }
        $currentStudent = Auth::guard('student')->user();
        $inquiry = new Inquiry([
            'student_id' => $currentStudent->id,
            'coursedetail_id' => $id,
            'message' => "",
            'inquirydate' => Carbon::now(),
        ]);
        $inquiry->save();
        return redirect()->back()->with('success', 'Inquiry created successfully');
    }

    public function inquiryForm($coursedetail_id)
    {
        $coursedetail = CourseDetail::findOrFail($coursedetail_id);
        $college_id = $coursedetail->college_id;
        return view('home.inquiry', compact('coursedetail_id', 'college_id'));
    }

    public function getInquiriesByCollege($college_id)
    {
        // Using Eloquent Relationships
        $inquiries = Inquiry::whereHas('coursedetail', function ($query) use ($college_id) {
            $query->where('college_id', $college_id);
        })->get();

        // Return inquiries to a view
        return view('home.inquiry.form', compact('inquiries'));
    }

}

