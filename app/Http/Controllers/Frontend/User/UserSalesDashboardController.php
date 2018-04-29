<?php

namespace App\Http\Controllers\Frontend\User;

use Carbon\Carbon;
use App\Models\Payment;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserSalesDashboardController extends Controller
{
    
    
    public function fetchSalesChartData(Request $request)
    {
        
        $filter = $request->period;
        $periods = collect(\Gabs::generateDateRange(Carbon::now()->subDays($filter), Carbon::now()));
        
        $times = [];
        foreach($periods as $p){
            $times[$p] = 0;
        }
        
        $data = [];
        // user's total sales
        $payments = Payment::where('created_at', '>=', Carbon::now()->subDays($filter))
                        ->whereHas('course', function($q) {
                            $q->where('user_id', auth()->id());
                        })
                        ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('sum(author_earning) as total'))
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get();
        $totals = [];
        foreach($payments as $val){
            $totals[$val->date] = (float)$val->total;
        }
        $totalSales = array_merge($times, $totals);
        array_push($data, [
            'name' => __('t.total-sales'),
            'data' => $totalSales
        ]);
        
        // author's promo sales
        $author_promo= Payment::where('created_at', '>=', Carbon::now()->subDays($filter))
                        ->whereHas('course', function($q) {
                            $q->where('user_id', auth()->id());
                        })
                        ->whereHas('coupon', function($q){
                            $q->where('sitewide', false);
                        })
                        ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('sum(author_earning) as total'))
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get();
        $totalPromo = [];
        foreach($author_promo as $val){
            $totalPromo[$val->date] = (float)$val->total;
        }
        $promoSales = array_merge($times, $totalPromo);
        
        array_push($data, [
            'name' => __('t.your-promo'),
            'data' => $promoSales
        ]);
        
        
        // affiliate network
        $affiliateEarning = Payment::where('created_at', '>=', Carbon::now()->subDays($filter))
                        ->whereHas('course', function($q) {
                            $q->where('user_id', auth()->id());
                        })
                        ->where('affiliate_earning', '>', 0)
                        ->whereNotNull('affiliate_earning')
                        ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('sum(author_earning) as total'))
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get();
        $totalAffiliate = [];
        foreach($affiliateEarning as $val){
            $totalAffiliate[$val->date] = (float)$val->total;
        }
        $affiliateSales = array_merge($times, $totalAffiliate);
        
        array_push($data, [
            'name' => __('t.affiliate-network'),
            'data' => $affiliateSales
        ]);
       
        return response()->json($data, 200); 
    }
    
    
    public function getUserSalesTable(Request $request)
    {
        $user = $request->user();
        $user_courses = Course::where('user_id', $user->id)->pluck('id');
        $all_user_earnings = Payment::whereIn('course_id', $user_courses)->latest()->get();
        
        $all_user_earnings->each(function($e){
            $e->created_at_formatted = $e->created_at->format('m/d/Y');
            $e->purchased_by = $e->user->name;
            $e->purchased_course = $e->course->title;
            $e->purchase_coupon = $e->coupon && !$e->coupon->sitewide ? $e->coupon->code : null;
            $e->paid_amount = \Gabs::currency($e->amount);
            $e->your_earning = \Gabs::currency($e->author_earning);
        });
        
        return response()->json($all_user_earnings, 200);
    }
    
    
    
    private function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];
    
        for($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }
    
        return $dates;
    }
}
