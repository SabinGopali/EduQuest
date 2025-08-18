<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\College;
use App\Models\CourseDetail;
use App\Models\Inquiry;
use App\Models\Students;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;


class CollegeDashboardController extends Controller
{
    //

    public function count()
    {
        $college = Auth::guard('college')->user();

        // Count distinct courses that this college has provided details for
        $coursecount = CourseDetail::where('college_id', $college->id)
            ->distinct('course_id')
            ->count('course_id');

        // Get CourseDetails for the current college
        $coursedetailcount = CourseDetail::where('college_id', $college->id)->count();

        // Get Inquiries for the current college
        $inquirycount = Inquiry::where('college_id', $college->id)->count();

        return view('college.dashboard', compact('coursecount', 'coursedetailcount', 'inquirycount'));
    }
}
