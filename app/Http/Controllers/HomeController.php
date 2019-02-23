<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Khsing\World\World;
use App\Page;
use App\Testimonial;
use App\User;
Use App\VirtualAddress;
use App\ShippingCompany;
use App\PaymentMod;
use App\MembershipPlan;
use App\AccountSetting;
use Auth;
use DB;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('auth');


    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //Role::create(['name' => 'guest']);
     //  Permission::create(['name' => 'view site']);

     //   $role =Role::findOrFail(1);
       // $permission = Permission::findOrFail(3);
      //  $role->givePermissionTo($permission);
      //  $permission->assignRole($role);


        //    auth()->user()->givePermissionTo('edit parcel');
       // auth()->user()->assignRole('user');
        //return auth()->user()->permissions;
       //return auth()->user()->getDirectPermissions(); 
     //  return auth()->user()->getAllPermissions();
       
            if(auth()->user()->type==='admin')
            {
                return view('admin.home');
            }
            else{
              
                return view('front.profile');
            }
    }

    public function main()
    {   
        
        if(auth()->check() && auth()->user()->type==='admin')
        {
            return view('admin.home');
        }
       
    }

    public function profile()
    {
       return view('front.profile',[
            'calculator_heading'=>'no',
            'currency_heading'=>'no']);
    }

    public function account_settings()
    {   
        $country_list =  country_list();
        $membership=membershipPlan::where(['lang'=>app()->getLocale(),'status'=>1,'id'=>auth()->user()->membership_plan_id])->limit(1)->get();
       
        return view('front.profilesettings',[
            'calculator_heading'=>'no',
            'currency_heading'=>'no',
            'page'=>'',
            'country_list'=>$country_list,
            'memberships'=>$membership
        ]);
    }

    public function addresses()
    {   
       
       $ids4Address[]='';
        //check for vaddress default
       $userData= User::where('id',auth()->user()->id)->first();
   
       if($userData['vitual_address_status'])
       {
        $addresses = VirtualAddress::where(['status'=>1])->get();
       }
       else
       { 
         $addressesIds=DB::table('user_virtual_address')->select('virtual_address_id')->where('user_id',auth()->user()->id)->get();

         
         foreach ($addressesIds as $data) {
            $ids4Address[]=$data->virtual_address_id;
         }
         if(count($ids4Address)>0)
         {
             $addresses = VirtualAddress::where(['status'=>1])->whereIn('id',$ids4Address)->get();
         }
          
        
       }
        
       
       
        return view('front.myaddresses',[
            'calculator_heading'=>'no',
            'currency_heading'=>'no',
            'page'=>'',
            'addresses'=>$addresses
        ]);
    }

    
    public function thankyou($slug)
    {
        Session::flush();
        return view('front.thankyou',['calculator_heading'=>'no','currency_heading'=>'no','page'=>""]);  
         
    }

     public function parcel_claim($id){
        return view('front.claim_parcel',['calculator_heading'=>'no','currency_heading'=>'no']);   
    }

    public function parcelsClaim(Request $request){ 
        $request->validate(
            [
               'id' => 'required|numeric',
               'description' => 'required',
                    
            ]);

        /*attachment Uplaod*/     
        $attachmentName = time().'.'.request()->attachment->getClientOriginalExtension();
        request()->attachment->move(public_path('img/front/attachments/'), $attachmentName);
    
        $claim = new Directory; 
        $claim->parcel_id = $request->id;
        $claim->user_id = auth()->user()->id;
        $claim->attachment = $attachmentName;
        $claim->description = $request->description;
        $claim->save();
        
        return  redirect('claim-parcel/'.$request->id)->with('success',__('alerts.backend.directory.created'));
    }

    // public function profile_settings()
    // {
    //     return view('front.profilesettings',['calculator_heading'=>'no','currency_heading'=>'no','page'=>'']);
    // }

    /*public function adminlogin(){
        if(auth()->user() && auth()->user()->type==='admin')
        {
             return redirect('backend/profile');
        }

       return view('admin.login');
    }*/
}
