@extends('layouts.sidebar')

@section('content')
<div class="vh-100 d-flex" style=" justify-content:center;">
<!-- align-items:center; -->
  <div class=" m-auto h-75" style="width:70%;margin-top: 80px;">
    <p><span>{{ $formatted_date }}</span><span class="ml-3">{{ $part }}部</span></p>
    <div class=" border" style="background: #fff; padding: 5px;border-radius: 10px;">

    <table class="w-100">
      @if ($reservePersons->isNotEmpty())
      @foreach($reservePersons as $reservePerson)
          <tr class="text-center table-category">
          <th class="t-id">ID</th>
          <th class="t-name">名前</th>
          <th class="t-place">場所</th>
        </tr>
         @foreach($reservePerson->users as $user )
        <tr class="text-center table-content">
          <td class="w-25">{{ $user->id}}</td>
          <td class="w-25">{{ $user->over_name}}{{ $user->under_name }}</td>
          <td class="w-25">リモート</td>
        </tr>
        @endforeach
        @endforeach
      </table>
      @else
    <p>予約はありません。</p>
    @endif
    </div>
  </div>
</div>
@endsection
