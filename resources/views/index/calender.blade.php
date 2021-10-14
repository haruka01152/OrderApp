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
        <div class="bg-white lg:container m-auto justify-between p-10 shadow">
            <div class="flex justify-between">
                <div class="flex justify-between mr-20">
                    <div class="flex items-center">
                        <a href="{{route('calender', ['year' => $previousYear, 'month' => $previousMonth])}}" class="pr-10"><i class="fas fa-chevron-left fa-3x text-gray-500"></i></a>
                    </div>
                    <div>
                        <div class="pb-5">
                            <span class="text-3xl font-bold text-original-blue">{{$year}}年{{$month}}月</span>
                        </div>
                        <table class="table table-bordered text-xl m-auto font-bold">
                            <thead>
                                <tr>
                                    @foreach (['日', '月', '火', '水', '木', '金', '土'] as $key => $dayOfWeek)
                                    <th class="py-8 px-12 border border-gray-600 bg-gray-200 {{$key == 0 ? 'text-red-500' : ''}} {{$key == 6 ? 'text-blue-500' : ''}}">{{ $dayOfWeek }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dates as $date)
                                @if ($date->dayOfWeek == 0)
                                <tr>
                                    @endif
                                    <td class="w-full h-20 pl-1 align-top border border-gray-600 {{$date->month != $month ? 'opacity-30' : ''}} {{$date->dayOfWeek == 0 ? 'text-red-500' : 'text-gray-700'}} {{$date->dayOfWeek == 6 ? 'text-blue-500' : 'text-gray-700'}}">
                                        {{ $date->day }}
                                    </td>
                                    @if ($date->dayOfWeek == 6)
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="flex items-center">
                        <a href="{{route('calender', ['year' => $nextYear, 'month' => $nextMonth])}}" class="pl-10"><i class="fas fa-chevron-right fa-3x text-gray-500"></i></a>
                    </div>
                </div>

                <form action="" method="get" class="pt-14 w-full mx-auto">
                    @csrf
                    <div class="text-center">
                        <p class="bg-original-blue text-white text-lg p-2 w-7/12 mx-auto text-center">指定のカレンダーを表示</p>
                        <div class="pt-5 text-center">
                            <select name="year" id="year" class="w-6/12">
                                @foreach (range($year-3, $year+10) as $targetYear)
                                <option value="{{$targetYear}}" {{$targetYear == $year ? 'selected' : ''}}>{{$targetYear}}</option>
                                @endforeach
                            </select>
                            <label for="year">年</label>
                        </div>

                        <div class="pt-2 text-center">
                            <select name="month" id="month" class="w-6/12">
                                @foreach (range(1,12) as $targetMonth)
                                <option value="{{$targetMonth}}" {{$targetMonth == $month ? 'selected' : ''}}>{{$targetMonth}}</option>
                                @endforeach
                            </select>
                            <label for="year">月</label>
                        </div>
                        <input type="submit" value="表示" class="cursor-pointer mt-5 py-1 px-6 rounded-md text-lg border border-gray-400">
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>