<x-slot name="header">
    <div class="flex items-center lg:container m-auto">
        <div class="flex items-center text-white">
            <a title="工事物品管理トップ" href="{{route('dashboard')}}" class="font-semibold text-xl leading-tight">
                工事物品管理
            </a>
            <a title="カレンダー表示" class="ml-10" href="{{route('calender', ['year' => date('Y'), 'month' => date('m')])}}"><i class="far fa-calendar-alt fa-2x"></i></a>
            <a title="アラート" class="ml-10 relative" href="{{route('alerts')}}"><i class="far fa-bell fa-2x"></i>
                @if(count($all_alerts) > 0)
                <div class="absolute -top-1 -right-1">
                    <span class="inline-block bg-red-500 rounded-full relative text-center" style="font-size:12px; padding:1px 2px; min-width:18px;">
                        {{count($all_alerts) <= 99 ? count($all_alerts) : '99+'}}
                    </span>
                </div>
                @endif
            </a>
            <a title="操作履歴" href="{{route('logs')}}" class="ml-10"><i class="fas fa-history fa-2x"></i></a>
        </div>

        @if(\Route::currentRouteName() == 'dashboard')
        <form class="flex items-center ml-auto mb-0 w-8/12 justify-end">
            @csrf
            <div class="flex pl-5">
                <div class="pl-3">
                    <select name="status" onchange="submit(this.form)">
                        <option value="all" {{request('status') == 'all' ? 'selected' : ''}}>(物品到着状況すべて)</option>
                        <option value="1" {{request('status') == 1 || request('status') == null ? 'selected' : ''}}>物品未着</option>
                        @foreach($statuses as $status)
                        <option value="{{$status->id}}" {{request('status') == $status->id ? 'selected' : ''}}>{{$status->name}}</option>
                        @endforeach
                    </select>
                    <select name="order_status" onchange="submit(this.form)">
                        <option value="all" {{request('order_status') == 'all' ? 'selected' : ''}}>(発注状況すべて)</option>
                        @foreach($order_statuses as $order_status)
                        <option value="{{$order_status->id}}" {{request('order_status') == $order_status->id ? 'selected' : ''}}>{{$order_status->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <input type="text" name="find" value="{{request('find')}}" placeholder="案件名 or お客様名を検索 （複数キーワード可）" class="w-5/12 ml-5">
            <input title="検索" type="submit" value="&#xf002;" class="fas fa-lg text-gray-500 bg-gray-100 border-t border-r border-b border-gray-500 px-3 cursor-pointer" style="line-height:40px;">
        </form>

        @elseif(\Route::currentRouteName() == 'calender')
        <form class="flex ml-10 mb-0" action="" method="get">
            @csrf
            <div>
                <select name="year" id="year" onchange="submit();">
                    @foreach(range(date('Y')-3,date('Y')+3) as $y)
                    <option value="{{$y}}" {{$y == $year ? 'selected' : ''}}>{{$y}}</option>
                    @endforeach
                </select>
                <label class="text-white font-bold pr-1" for="year">年</label>
            </div>
            <div>
                <select name="month" id="month" onchange="submit();">
                    @foreach(range(1,12) as $m)
                    <option value="{{$m}}" {{$m == $month ? 'selected' : ''}}>{{$m}}</option>
                    @endforeach
                </select>
                <label class="text-white font-bold" for="year">月</label>
            </div>
        </form>

        @elseif(\Route::currentRouteName() == 'alerts')
        <form class="flex ml-10" action="" method="get">
            @csrf
            <div>
                <select name="class" onchange="submit();">
                    <option value="all" {{request('class') == null ? 'selected' : ''}}>(アラート分類すべて)</option>
                    <option value="1" {{request('class') == 1 ? 'selected' : ''}}>物品未着アラート</option>
                    <option value="2" {{request('class') == 2 ? 'selected' : ''}}>発注指示待ちアラート</option>
                    <option value="3" {{request('class') == 3 ? 'selected' : ''}}>発注指示アラート</option>
                </select>
            </div>
        </form>

        @elseif(\Route::currentRouteName() == 'profile.show')
        <form class="ml-auto" action="{{route('logout')}}" method="POST">
            @csrf
            <input type="submit" class="py-2 px-4 bg-red-500 border-2 border-white text-white rounded-lg cursor-pointer" value="ログアウト">
        </form>
        @endif
    </div>
</x-slot>