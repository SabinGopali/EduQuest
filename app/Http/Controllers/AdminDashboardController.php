<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\College;
use App\Models\CourseDetail;
use App\Models\Inquiry;
use App\Models\Students;
use App\Models\Contact;
use App\Models\Booking;

class AdminDashboardController extends Controller
{
    public function count()
    {
        $coursecount = Course::count();
        $collegecount = College::count();
        $coursedetailcount = CourseDetail::count();
        $inquirycount = Inquiry::count();
        $studentscount = Students::count();
        $contactcount = Contact::count();
        $bookingcount = Booking::count();
        $pendingBookingCount = Booking::where('status', 'booked')->count();
        $approvedBookingCount = Booking::where('status', 'approved')->count();
        $rejectedBookingCount = Booking::where('status', 'rejected')->count();

        // Pending approvals
        $pendingCollegeCount = College::where('status', 'PENDING')->count();
        $pendingCourseDetailCount = CourseDetail::where('status', 'PENDING')->count();

        // Pass multiple values to the view using compact
        return view('admin.dashboard', compact(
            'coursecount',
            'collegecount',
            'coursedetailcount',
            'inquirycount',
            'studentscount',
            'contactcount',
            'pendingCollegeCount',
            'pendingCourseDetailCount'
        ,
            'bookingcount',
            'pendingBookingCount',
            'approvedBookingCount',
            'rejectedBookingCount'
        ));
    }

}