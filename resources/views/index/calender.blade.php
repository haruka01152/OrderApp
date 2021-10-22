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


    @include('components.breadCrumb')

    <div class="py-12">
        <div class="bg-white lg:container m-auto flex justify-between py-10 px-16 shadow">
            <div class="my-auto">
                <a href="{{route('calender', ['year' => $previousYear, 'month' => $previousMonth])}}" class="pr-10"><i class="fas fa-chevron-left fa-3x text-gray-500"></i></a>
            </div>
            <div class="w-full">
                <div class="pb-5 flex justify-between text-gray-700">
                    <span class="text-3xl font-bold text-original-blue">{{$year}}年{{$month}}月<span class="text-base text-gray-500">の工事予定</span></span>

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
                            <td class="relative p-0 pb-3 h-28 align-top border border-gray-600 {{$date->month != $month ? 'opacity-30' : ''}} {{$date->dayOfWeek == 0 ? 'text-red-500' : 'text-gray-700'}} {{$date->dayOfWeek == 6 ? 'text-blue-500' : 'text-gray-700'}} {{$date->format('Y-m-d') == date('Y-m-d') ? 'bg-green-100' : ''}}">
                                <a href="{{route('add', ['date' => $date->format('Y-m-d')])}}" class="w-full h-full hover:bg-gray-500 opacity-10 inline-block absolute bottom-0"></a>
                                    <span class="block mb-1 pl-1">{{ $date->day }}</span>
                                    <div class="text-center relative z-10">
                                        @foreach($constructions as $construction)
                                            @include('components.chips')
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
    </div>

</x-app-layout>