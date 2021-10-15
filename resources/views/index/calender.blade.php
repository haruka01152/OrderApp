<style>
    * {
        box-sizing: border-box;
    }

    th,
    td {
        width: 80px;
    }
</style>

<x-app-layout>
    @include('components.header')


    <!-- パンくずリスト -->
    <div class="flex items-center py-2 px-8 bg-white shadow border-t-2 border-gray-200">
        <div class="lg:container m-auto">
            <a href="{{route('dashboard')}}" class="text-blue-500 pr-3">工事物品管理トップ</a>
            <i class="fas fa-chevron-right text-gray-500 mr-3"></i>
            <a href="{{url()->full()}}" class="text-blue-500 pr-3">カレンダー表示</a>
        </div>
    </div>

    <div class="pt-12 pb-28">
        <div class="bg-white lg:container m-auto flex justify-between py-10 px-16 shadow">
            <div class="my-auto">
                <a href="{{route('calender', ['year' => $previousYear, 'month' => $previousMonth])}}" class="pr-10"><i class="fas fa-chevron-left fa-3x text-gray-500"></i></a>
            </div>
            <div class="w-full">
                <div class="pb-5 flex justify-between text-gray-700">
                    <span class="text-3xl font-bold text-original-blue">{{$year}}年{{$month}}月<span class="text-base text-gray-500">の工事日を表示しています</span></span>

                    <div class="flex items-center">
                        <span class="rounded-full bg-red-400 h-8 w-8"></span>
                        <p>　・・・　物品未着の案件</p>
                        <span class="ml-10 rounded-full bg-gray-500 h-8 w-8"></span>
                        <p>　・・・　物品到着済の案件</p>
                    </div>
                </div>
                <table class="table text-xl m-auto w-full">
                    <thead>
                        <tr>
                            @foreach (['日', '月', '火', '水', '木', '金', '土'] as $key => $dayOfWeek)
                            <th class="py-1 pl-1 border border-gray-600 bg-gray-100 {{$key == 0 ? 'text-red-500' : 'text-gray-700'}} {{$key == 6 ? 'text-blue-500' : 'text-gray-700'}}">{{ $dayOfWeek }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dates as $date)
                        @if ($date->dayOfWeek == 0)
                        <tr>
                            @endif
                            <td class="h-28 pb-3 px-1 align-top border border-gray-600 {{$date->month != $month ? 'opacity-30' : ''}} {{$date->dayOfWeek == 0 ? 'text-red-500' : 'text-gray-700'}} {{$date->dayOfWeek == 6 ? 'text-blue-500' : 'text-gray-700'}} {{$date->format('Y-m-d') == date('Y-m-d') ? 'bg-green-100' : ''}}">
                                <span class="block mb-1">{{ $date->day }}</span>
                                <div class="text-center">
                                    @foreach($constructions as $construction)
                                    @if($construction->construction_date == $date->format('Y-m-d'))
                                    @if($construction->arrive_status == '✔')
                                    <a href="{{route('edit', ['id' => $construction->id])}}" class="w-11/12 inline-block mb-1 bg-gray-500 text-white text-center whitespace-wrap text-sm rounded-lg p-1">{{Str::limit($construction->customer_name, '20', '…')}}</a><br>
                                    @else
                                    <a href="{{route('edit', ['id' => $construction->id])}}" class="w-11/12 inline-block mb-1 bg-red-400 text-white text-center whitespace-wrap text-sm rounded-lg p-1">{{Str::limit($construction->customer_name, '20', '…')}}</a><br>
                                    @endif
                                    @endif
                                    @endforeach
                                </div>
                            </td>
                            @if ($date->dayOfWeek == 6)
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="my-auto">
                <a href="{{route('calender', ['year' => $nextYear, 'month' => $nextMonth])}}" class="pl-10"><i class="fas fa-chevron-right fa-3x text-gray-500"></i></a>
            </div>
        </div>

        <!-- <form action="" method="get" class="pt-14 w-full mx-auto">
                    @csrf
                    <div class="text-center">
                        <p class="bg-original-blue text-white text-lg p-2 w-7/12 mx-auto text-center">指定のカレンダーを表示</p>
                        <div class="pt-5">
                            <select name="year" id="year" class="w-56 ml-1">
                                @foreach (range($year-3, $year+10) as $targetYear)
                                <option value="{{$targetYear}}" {{$targetYear == $year ? 'selected' : ''}}>{{$targetYear}}</option>
                                @endforeach
                            </select>
                            <label for="year">年</label>
                        </div>

                        <div class="pt-2">
                            <select name="month" id="month" class="w-56 ml-1">
                                @foreach (range(1,12) as $targetMonth)
                                <option value="{{$targetMonth}}" {{$targetMonth == $month ? 'selected' : ''}}>{{$targetMonth}}</option>
                                @endforeach
                            </select>
                            <label for="year">月</label>
                        </div>
                        <input type="submit" value="表示" class="cursor-pointer mt-5 py-1 px-6 rounded-md text-lg border border-gray-400">
                    </div>
                </form> -->
    </div>

</x-app-layout>