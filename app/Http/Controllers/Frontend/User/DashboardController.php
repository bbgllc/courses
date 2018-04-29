<?php

namespace App\Http\Controllers\Frontend\User;

use App\Models\Course;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('frontend.user.dashboard');
    }
    
    public function myCourses(Request $request)
    {
        $courses = auth()->user()->enrollments;
        return view('_User.enrolled-courses', compact('courses'));
    }
    
    public function myPurchases(Request $request)
    {
        
        $purchases = auth()->user()->purchases()->with('course', 'coupon')->paginate(10);
	    
        return view('_User.purchase-history', compact('purchases'));
    }
    
    public function myWishlist(Request $request)
    {
        $courses = $request->user()->bookmarks()->get();

        return view('_User.wishlist', compact('courses'));
    }
    
    
    public function myCertificates(Request $request)
    {
        $certificates = $request->user()->certificates()->get();

        return view('_User.certificates', compact('certificates'));
    }
    
    public function downloadCertificate(Request $request, Course $course)
    {
        if(!auth()->user()->hasCompletedCourse($course)){
            return back()->withFlashWarning(__('t.you-have-not-earned-certificate'));
        }
        $certificate = auth()->user()->certificates()->where('course_id', $course->id)->first();
        $file = str_slug($certificate->user->name) . '_certificate.pdf';
        $pdf = \PDF::loadView('downloads.certificate', compact('certificate'))->setPaper('a4', 'landscape');
       
        return $pdf->download($file);
       
    }
    
    public function downloadReceipt(Request $request, Course $course)
    {
        
        $payment = auth()->user()->purchases()->where('course_id', $course->id)->first();
        
        if(is_null($payment)){
            return back()->withFlashWarning(__('t.you-have-not-purchased-this-course'));
        }
        
        $file = str_slug($payment->user->name) . '_' . str_slug($payment->course->title) . '_receipt.pdf';
        $pdf = \PDF::loadView('downloads.receipt', compact('payment'))->setPaper('a5', 'landscape');
        
        return $pdf->download($file);
       
    }
    
}
