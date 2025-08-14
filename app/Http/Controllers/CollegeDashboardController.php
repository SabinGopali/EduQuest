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

        $coursecount = Course::count();

        // Get CourseDetails for the current college
        $coursedetailcount = CourseDetail::where('college_id', $college->id)->count();

        // Get Inquiries for the current college
        $inquirycount = Inquiry::whereHas('courseDetail', function ($query) use ($college) {
            $query->where('college_id', $college->id);
        })->count();

        return view('college.dashboard', compact('coursecount', 'coursedetailcount', 'inquirycount'));
    }
}
