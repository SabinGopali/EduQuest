<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\College;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class InquiryRank extends Component
{
    public $items;
    public $showAll;

    /**
     * Create a new component instance.
     *
     * @param bool $showAll Whether to show all colleges or just top 6
     */
    public function __construct($showAll = false)
    {
        $this->showAll = $showAll;
        $this->loadData();
    }

    /**
     * Load the inquiry rank data
     */
    private function loadData()
    {
        $approvedColleges = College::where('status', 'APPROVED')->get();

        // Count bookings per college by joining through course details
        $bookingCounts = Booking::join('coursedetail', 'bookings.coursedetail_id', '=', 'coursedetail.id')
            ->where('bookings.status', 'booked')
            ->select('coursedetail.college_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('coursedetail.college_id')
            ->pluck('cnt', 'coursedetail.college_id');

        $ranked = $approvedColleges->map(function ($college) use ($bookingCounts) {
            $count = (int) ($bookingCounts[$college->id] ?? 0);
            return [
                'college' => $college,
                'bookings' => $count,
            ];
        })->sortByDesc('bookings')->values();

        // Limit to top 6 if not showing all
        if (!$this->showAll) {
            $ranked = $ranked->take(6);
        }

        $this->items = $ranked;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.inquiry-rank');
    }
}
