<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Employer;
use App\Models\Job;
use App\Models\User;
use App\Models\Review;
use App\Models\GeneralSetting;
use App\Models\JobApply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class RatingsController extends Controller
{
  public function index()
  {
    $pageTitle= "Ratings";
    $emptyMessage = "No data found";
      $all_rating = Review::select( 'users.*',DB::raw('SUM(reviews.rating) As rating'))
         ->leftJoin('job_applies', 'job_applies.id', '=', 'reviews.job_apply_id')
         ->leftJoin('users', 'job_applies.user_id', '=', 'users.id')
         ->where('comment','rating by employer')
         ->groupBy('users.id')
         ->get();
    $bronze_array = array();
    $silver_array = array();
    $gold_array = array();
    $i=0;
    $j=0;
    $k=0;
    foreach ($all_rating as $key)
    {
      if(getmedal($key['rating']) == 'Bronze')
      {
        $bronze_array[$i]['username'] =  ucfirst($key['firstname']).' '.ucfirst($key['lastname']);
        $bronze_array[$i]['rating'] =  $key['rating'];
        $bronze_array[$i]['image'] =  $key['image'];
        $i++;
      }
      elseif(getmedal($key['rating']) == 'Silver')
      {
        $silver_array[$j]['username'] =  ucfirst($key['firstname']).' '.ucfirst($key['lastname']);
        $silver_array[$j]['rating'] =  $key['rating'];
        $silver_array[$j]['image'] =  $key['image'];
        $j++;
      }
      elseif(getmedal($key['rating']) == 'Gold')
      {
        $gold_array[$k]['username'] =  ucfirst($key['firstname']).' '.ucfirst($key['lastname']);
        $gold_array[$k]['rating'] =  $key['rating'];
        $gold_array[$k]['image'] =  $key['image'];
        $k++;
      }
    }
    return view('admin.ratings.index', compact('pageTitle', 'emptyMessage','all_rating','bronze_array','silver_array','gold_array'));
  }
}
