<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CourseDetail;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(Request $request, int $coursedetail_id)
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('student.loginFrom')->with('error', 'Please log in to book.');
        }

        $student = Auth::guard('student')->user();
        $courseDetail = CourseDetail::with('course')->findOrFail($coursedetail_id);

        $gpaLimit = $courseDetail->course->gpa_limit;
        if (!is_null($gpaLimit) && $student->gpa < $gpaLimit) {
            return back()->with('error', 'Your GPA does not meet the minimum requirement.');
        }

        if (!is_null($courseDetail->application_deadline)) {
            $deadline = Carbon::parse($courseDetail->application_deadline)->endOfDay();
            if (Carbon::now()->greaterThan($deadline)) {
                return back()->with('error', 'The application deadline has passed.');
            }
        }

        try {
            DB::beginTransaction();

            $existingCount = Booking::where('coursedetail_id', $courseDetail->id)->lockForUpdate()->count();
            $seats = (int)($courseDetail->seats ?? 0);
            if ($seats > 0 && $existingCount >= $seats) {
                DB::rollBack();
                return back()->with('error', 'No seats available.');
            }

            $alreadyBooked = Booking::where('student_id', $student->id)
                ->where('coursedetail_id', $courseDetail->id)
                ->exists();
            if ($alreadyBooked) {
                DB::rollBack();
                return back()->with('error', 'You have already booked this course.');
            }

            Booking::create([
                'student_id' => $student->id,
                'coursedetail_id' => $courseDetail->id,
                'status' => 'booked',
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Booking failed.');
        }

        return back()->with('success', 'Course booked successfully.');
    }

    public function indexForStudent()
    {
        $student = Auth::guard('student')->user();
        $bookings = Booking::with(['courseDetail.course', 'courseDetail.college'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('home.bookings', compact('bookings'));
    }

    public function indexForAdmin()
    {
        $bookings = Booking::with(['student', 'courseDetail.course', 'courseDetail.college'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.bookings', compact('bookings'));
    }

    public function indexForCollege()
    {
        $college = Auth::guard('college')->user();
        $bookings = Booking::with(['student', 'courseDetail.course'])
            ->whereHas('courseDetail', function ($q) use ($college) {
                $q->where('college_id', $college->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return view('college.bookings', compact('bookings'));
    }
}

