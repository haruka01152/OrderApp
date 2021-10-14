<x-app-layout>
    @include('components.errorBar')
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
                <div class="flex items-center">
                <a href="{{route('calender', ['year' => $previousYear, 'month' => $previousMonth])}}"><i class="fas fa-chevron-left fa-3x text-gray-500"></i></a>
                </div>
                <div>
                    <div class="pb-5">
                        <span class="text-3xl">{{$year}}年{{$month}}月</span>
                    </div>
                <table class="table table-bordered text-2xl m-auto">
                    <thead>
                        <tr>
                            @foreach (['日', '月', '火', '水', '木', '金', '土'] as $dayOfWeek)
                            <th class="py-8 px-12 border border-gray-600 bg-gray-200">{{ $dayOfWeek }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dates as $date)
                        @if ($date->dayOfWeek == 0)
                        <tr>
                            @endif
                            <td class="p-8 px-12 border border-gray-600 {{$date->month != $month ? 'opacity-30' : ''}}">
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
                <a href="{{route('calender', ['year' => $nextYear, 'month' => $nextMonth])}}"><i class="fas fa-chevron-right fa-3x text-gray-500"></i></a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>