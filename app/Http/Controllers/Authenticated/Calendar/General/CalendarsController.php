<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\Users\User;
use Auth;
use DB;

class CalendarsController extends Controller
{
    public function show(){
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request){
        DB::beginTransaction();
        try{
            $getPart = $request->getPart;
            $getDate = $request->getDate;

            $reserveDays = array_filter(array_combine($getDate, $getPart));
            foreach($reserveDays as $key => $value){
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id());
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

    public function delete(Request $request){
         $getPart = $request->delete_part;
         $getDate = $request->delete_date;
         if($getPart == "リモ1部"){
            $delete_part = 1;
          }else if($getPart == "リモ2部"){
            $delete_part = 2;
          }else if($getPart == "リモ3部"){
            $delete_part = 3;
          }


        //  dd($getPart,$getDate);
        $reserve_settings = ReserveSettings::where('setting_reserve', $getDate)->where('setting_part', $delete_part)->first();
        // dd($reserve_settings);
        $reserve_settings->increment('limit_users');
        $reserve_settings->users()->detach(Auth::id());

        // DB::beginTransaction();
        // try{
        //     $getPart = $request->getPart;
        //     $getDate = $request->getData;

        //     $reserveDays = array_filter(array_combine($getDate, $getPart));
        //     foreach($reserveDays as $key => $value){
        //         $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
        //         $reserve_settings->increment('limit_users');
        //         $reserve_settings->users()->detach(Auth::id());
        //     }
        //     DB::commit();
        // }catch(\Exception $e){
        //     DB::rollback();
        // }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

    // 予約詳細画面遷移
    public function reserveDetail($date,$part){


        return view('authenticated.calendar.admin.reserve.detail', compact(''));
    }

}
