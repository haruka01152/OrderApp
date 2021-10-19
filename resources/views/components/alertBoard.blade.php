<div class="mb-5 bg-white overflow-hidden shadow p-7 border-4 border-gray-500 border-double">
    <h3 class="text-xl border-b border-l-8 pl-3 border-gray-500">{{\Route::currentRouteName() == 'dashboard' ? '新着アラート' : 'アラート一覧'}}　（全{{count($all_alerts)}}件）</h3>

    @if(count($alerts) > 0)
    <div class="pt-9 px-3 table w-full">
        @foreach($alerts as $alert)
        @php
        $const_date = $carbon::createFromFormat('Y-m-d',$alert->constructions->construction_date);
        $diff_days = $carbon->diffInDays($const_date);
        $today = $carbon::now()->format('Y-m-d H:i:s');
        @endphp
        <a href="{{route('edit', ['id' => $alert->construction_id])}}" class="table-row text-red-600 hover:bg-gray-100">
            <div class="table-cell w-3/12">【@if($const_date == $today)工事日当日@elseif($const_date > $today)工事日まであと{{$diff_days}}日@else 工事日から{{$diff_days}}日経過@endif】　―　{{$const_date->format('m/d')}}工事</div>
            <div class="table-cell w-3/12">{{$alert->constructions->customer_name}}</div>
            <div class="table-cell w-6/12">{{Str::limit($alert->constructions->construction_name, 80, '…')}}</div>
        </a>
        <div class="border-2 border-transparent"></div>
        @endforeach
    </div>
    @if(\Route::currentRouteName() == 'alerts')
    <div class="mt-5 flex justify-center">
        {{$alerts->appends(request()->query())->links()}}
    </div>
    @endif
    @else
    <div class="text-center">
        <p class="py-10">データがありません。</p>
    </div>
    @endif
    @if(\Route::currentRouteName() == 'dashboard' && count($all_alerts) > 5)
    <div class="text-right pt-5 pr-5">
        <a href="{{route('alerts')}}" class="text-red-600">すべて見る >></a>
    </div>
    @endif
</div>