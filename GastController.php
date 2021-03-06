<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;
use App\Testimonial;
use App\Directory;
use DB;
use App\MembershipPlan;
use App\News;
use DateTime;


class GastController extends Controller
{
    

	public function index(){

		$testimonials=Testimonial::where('lang',app()->getLocale())->orderBy('id','DESC')->limit(10)->get();
        $data = array(
            'currency_heading'=>'yes',
            'calculator_heading'=>'yes','testimonials'=>$testimonials);

        return view('welcome',$data);
	}

	public function pages($slug){ 

         $page= Page::where('slug',$slug)->first();
        
            
        if($slug=='how-shopini-world-works')
        {
            
            return view('front.pages.howshopiniworld',['calculator_heading'=>'no','currency_heading'=>'no','page'=>$page]);
        }
        elseif($slug=='shopping-directory')
        {
            $country_id = request('id');
            $countries = DB::table('world_countries')->whereIn('id', [76, 68, 167])->pluck("name","id");
            $categories = DB::table('categories')->where('status',1)->get();

            $directories = DB::table('directories')->where('status',1)->get();
            
            return view('front.pages.shopping_directory',compact('countries','directories','categories'),['calculator_heading'=>'no','currency_heading'=>'no','page'=>'Shopping Directory']);
        }
        else
        {
            
             return view('front.pages.page',['calculator_heading'=>'no','currency_heading'=>'no','page'=>$page]);
        }
       

    }

    public function membership()
    {
       
        $membership=membershipPlan::where(['lang'=>app()->getLocale(),'status'=>1])->orderBy('id','ASC')->limit(3)->get();

         return view('front.subscribe',['calculator_heading'=>'no','currency_heading'=>'no','memberships'=>$membership]);
    }

    public function contactus(){
         return view('front.pages.contact');
    }

    public function careers(){

        return view('front.pages.career',['calculator_heading'=>'no','currency_heading'=>'no','page'=>'']);
    }
    public function privacy_policy()
    {
        return view('front.pages.privacy_policy',['calculator_heading'=>'no','currency_heading'=>'no','page'=>'']);
    }
    public function success_register(){

        return view('front.success');
    }

    public function rate_countries_list()
    {
        return DB::table('weight_rates')->get();
    }
    public function currency_country_list()
    {
        return DB::table('currency_converters')->get();
    }

    public function rate_calculates(Request $request)
    {   
    
        $fromShip       = $request->fromShip;
        $toShip         = $request->toShip;
        $weightToship   = $request->weightToship;
        $unit           = $request->unit;
        
        $weight_rates = DB::table('weight_rates')
        ->where('country_from', $fromShip)
        ->where('country_to', $toShip)
        ->first();
        $total_rate = ($weight_rates->rate * $weightToship);
        return number_format($total_rate,2);;
    }

    public function exchange_currency(Request $request)
    {
        $fromCurr       = $request->fromCurr;
        $toCurr         = $request->toCurr;
        $amount         = $request->amount;

        $from = DB::table('currency_converters')
        ->where('code', $fromCurr)
        ->first();

        $to = DB::table('currency_converters')
        ->where('code', $toCurr)
        ->first();

        $exchange_rate = ($to->rate / $from->rate) * ($amount);
        return number_format($exchange_rate,2);
    }



   

    public function adminLogin()
    {
        return view('admin.login');
    }


    function shopping_directory_ajaxcall($country_id){

        if($country_id == 0){ 
             $directories = DB::table('directories')->where('status',1)->get();
        }else{
             $directories = DB::table('directories')->where('status',1)->Where('country_id', $country_id)->get();
        }

        $categories = DB::table('categories')->where('status',1)->get();
     
         foreach($directories as $row){
            $id[] = $row->category_id;
          }


            if(count($directories)>0){

           foreach($categories as $category){
              if (in_array($category->id, $id)){
                  echo '<div class="col-md-12"> <div class="cat_heading">'.  $category->name.' </div></div>';
                      
                   foreach($directories as $directory){
                      if($category->id ==  $directory->category_id){
                       echo '<div class="col-md-6"><div class="cat_name"><a href="'.$directory->url .'" target="_blank">'.$directory->name.' </a> </div> <div class="cat_logo"> 
                              <a href="'.$directory->url.'" target="_blank"> <img src="'.url("img/front/logo/".$directory->logo).'" width="40" height="40" alt="logo"></a></div></div>';
                     }
                   }
                  
                }
             }  
        }
    }


    public function announcements()
    {   
        $lang = app()->getLocale();    
        $news= News::where(['status'=>1,'lang'=>$lang])->paginate('5');
        return view('front.pages.announcements',[
            'calculator_heading'=>'no',
            'currency_heading'=>'no',
            'page'=>'',
            'announcements'=>$news
        ]);
    }

    public function announcement_details($id)
    {
        $lang = app()->getLocale();    
        $news= News::where(['status'=>1,'lang'=>$lang, 'id'=>$id])->first();
        return view('front.pages.announcement_details',[
            'calculator_heading'=>'no',
            'currency_heading'=>'no',
            'page'=>'',
            'announcements'=>$news
        ]);
    }

}
