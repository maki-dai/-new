@extends('layouts.sidebar')
@section('content')
<div class=" pt-5 pb-5" style="background:#ECF1F6;">
  <div class=" w-75 m-auto border pt-5 pb-5" style="border-radius:5px; background:#FFF;">
    <div class="w-75 m-auto" style="border-radius:5px;">
     <p class="text-center">{{ $calendar->getTitle() }}</p>
     <p>{!! $calendar->render() !!}</p>
     <div class="adjust-table-btn text-right">
      <input type="submit" class="btn btn-primary" value="登録" form="reserveSetting" onclick="return confirm('登録してよろしいですか？')">
     </div>
   </div>
  </div>
</div>
@endsection
