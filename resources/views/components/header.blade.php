<x-slot name="header">
    <div class="flex items-center justify-between lg:container m-auto">
        <div class="flex items-center text-white">
            <a title="工事物品管理トップ" href="{{route('dashboard')}}" class="font-semibold text-xl leading-tight">
                工事物品管理
            </a>
            <a title="カレンダー表示" class="ml-10" href="{{route('calender', ['year' => date('Y'), 'month' => date('m')])}}"><i class="far fa-calendar-alt fa-2x"></i></a>
        </div>

        @if(\Route::currentRouteName() == 'dashboard')
        <form class="flex items-center m-0 w-8/12 justify-end" id="submit_form">
            @csrf
            <div class="flex pl-5">
                <div class="pl-3">
                    <select name="status" id="#submit_select" onchange="submit(this.form)">
                        @foreach($statuses as $status)
                        <option value="{{$status->id}}" {{request('status') == $status->id ? 'selected' : ''}}>{{$status->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <input type="text" name="find" value="{{request('find')}}" placeholder="案件名 or お客様名を検索 （複数キーワード可）" class="w-5/12 ml-5">
            <input title="検索" type="submit" value="&#xf002;" class="fas fa-lg text-gray-500 bg-gray-100 border-t border-r border-b border-gray-500 px-3 cursor-pointer" style="line-height:40px;">
        </form>

        @elseif(\Route::currentRouteName() == 'calender')
        <form class="flex m-0" action="" method="get">
            @csrf
            <div>
                <select name="year" id="year">
                    @foreach(range(date('Y')-3,date('Y')+3) as $y)
                    <option value="{{$y}}" {{$y == $year ? 'selected' : ''}}>{{$y}}</option>
                    @endforeach
                </select>
                <label class="text-white font-bold pr-1" for="year">年</label>
            </div>
            <div>
                <select name="month" id="month">
                    @foreach(range(1,12) as $m)
                    <option value="{{$m}}" {{$m == $month ? 'selected' : ''}}>{{$m}}</option>
                    @endforeach
                </select>
                <label class="text-white font-bold" for="year">月</label>
            </div>
            <input title="カレンダーを表示" type="submit" value="表示" class="cursor-pointer ml-5 py-1 px-5 text-lg rounded-lg border border-gray-500">
        </form>
        @endif
    </div>
</x-slot>