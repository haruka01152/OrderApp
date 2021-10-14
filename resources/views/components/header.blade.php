<x-slot name="header">
    <div class="flex items-center justify-between lg:container m-auto">
        <div class="flex items-center text-white">
            <a href="{{route('dashboard')}}" class="font-semibold text-xl leading-tight">
                工事物品管理
            </a>
            @if(\Route::currentRouteName() == 'dashboard')
            <a class="ml-10" href="{{route('calender', ['year' => $currentYear, 'month' => $currentMonth])}}"><i class="far fa-calendar-alt fa-2x"></i></a>
            @endif
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
            <input type="submit" value="&#xf002;" class="fas fa-lg text-gray-500 bg-gray-100 border-t border-r border-b border-gray-500 px-3 cursor-pointer" style="line-height:40px;">
        </form>
        @endif
    </div>
</x-slot>