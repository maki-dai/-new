<?php
namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView{

  private $carbon;
  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  function render(){
    $html = [];
    // $html[] = '<div class="calendar ">';
    $html[] = '<table class="table text-center border" style="table-layout:fixed;">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="border">月</th>';
    $html[] = '<th class="border">火</th>';
    $html[] = '<th class="border">水</th>';
    $html[] = '<th class="border">木</th>';
    $html[] = '<th class="border">金</th>';
    $html[] = '<th class="border">土</th>';
    $html[] = '<th class="border">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();
    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';

      $days = $week->getDays();
      foreach($days as $day){
        $startDay = $this->carbon->copy()->format("Y-m-01");
        $toDay = $this->carbon->copy()->format("Y-m-d");

        // 過去日色変更ここ
        if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
          $html[] = '<td class="past-day border" style="vertical-align:middle;">';
        }else{
          $html[] = '<td class="border '.$day->getClassName().'" style="vertical-align:middle;">';
        }
        $html[] = $day->render();

        // 予約した
        if(in_array($day->everyDay(), $day->authReserveDay())){
          $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;
          if($reservePart == 1){
            $reservePart = "リモ1部";
          }else if($reservePart == 2){
            $reservePart = "リモ2部";
          }else if($reservePart == 3){
            $reservePart = "リモ3部";
          }

          // 予約した中で過去日　参加した部表示
          if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
            $html[] = '<p class=" p-0 w-75" style="font-size:12px;margin:10px">'.$reservePart.'</p>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
            // 過去日じゃない予約した日　表示（キャンセルボタン）
          }else{

            // キャンセルボタン
            $html[] = '<button type="submit" class="btn btn-danger delete-modal-open p-0 w-75" name="delete_date" delete_date="'. $day->authReserveDate($day->everyDay())->first()->setting_reserve .'" delete_part="'. $reservePart .'" style="font-size:12px;margin:10px" value="'. $day->authReserveDate($day->everyDay())->first()->setting_reserve .'">'. $reservePart .'</button>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
            // モーダル中身
            $html[] = '<div class="modal js-modal">
  <div class="modal__bg js-modal-close"></div>
  <div class="modal__content">
      <div class="w-100">
      <div class="modal-text">
        <div class="modal-inner-date w-50 m-auto">
予約日：
<p class="modal_delete_date"></p>
        </div>
        <div class="modal-inner-part w-50 m-auto pt-3 pb-3">
時間：
<p class="modal_delete_part"></p>
        </div>
        <div class="modal-inner-message w-50 m-auto pt-3 pb-3">
          <p>上記の予約をキャンセルしてもよろしいですか？</p>
        </div>
        </div>
        <div class="w-50 m-auto edit-modal-btn d-flex">
          <a class="js-modal-close btn btn-primary d-inline-block" href="">閉じる</a>
          <input type="hidden" class="delete_date-modal-hidden" name="delete_date" value="" form="deleteParts">
          <input type="hidden" class="delete_part-modal-hidden" name="delete_part" value="" form="deleteParts">


          <input type="submit" class="btn btn-danger d-block" value="キャンセル" form="deleteParts">
        </div>
      </div>
  </div>';
          }
          // 予約してない日
        }else{
          // ※if文いれちゃうとなぜか値送るときにエラーになる、いれなきゃ大丈夫

          // // 予約してない過去日
          if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
          $html[] = '<p class="p-0 w-75" style="font-size:12px;margin:10px">受付終了</p>';
          $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          // 予約してない未来日　選択表示
          }else{
          $html[] = $day->selectPart($day->everyDay());
        }
      }
        $html[] = $day->getDate();
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    // $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">'.csrf_field().'</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">'.csrf_field().'</form>';

    return implode('', $html);
  }

  protected function getWeeks(){
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while($tmpDay->lte($lastDay)){
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
