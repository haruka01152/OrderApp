<x-app-layout>

    @include('components.header')

    @include('components.breadCrumb')

    @if(count($logs) > 0)
    <div class="bg-white p-10 lg:container mx-auto my-5">
    <div class="flex justify-between">
        <h3 class="w-full text-xl border-b border-l-8 pl-3 border-gray-500">操作履歴一覧</h3>
        <i title="すべての履歴の詳細を開く" id="view-all-logs" class="transition duration-500 ease-in-out mx-4 cursor-pointer fa-2x text-gray-400 fas fa-angle-double-down"></i>
    </div>

    @foreach($logs as $log)
    <div class="mt-11 px-4">
        <div class="my-4 flex items-center">
            <div>
                <span class="text-sm pb-1 block text-blue-600">{{$log->created_at}}</span>
                <p>{{$log->message}}</p>
            </div>
            @if($log->contents != null)
            <i title="この履歴の詳細を開く" class="view-log-button cursor-pointer mt-5 p-2 transition duration-500 ease-in-out text-gray-500 ml-auto fas fa-chevron-down"></i>
            @endif
        </div>
        @if($log->contents != null)
        <div class="relative">
            <div class="log-content hidden pb-5 text-gray-500 text-sm">
                <p class="leading-relaxed">{!! nl2br(e($log->contents)) !!}</p>
            </div>
        </div>
        @endif
        <hr>
    </div>
    @endforeach
    <div class="pt-5 md:mt-0 flex justify-center">
        {{$logs->links()}}
    </div>
    </div>
    @endif

</x-app-layout>