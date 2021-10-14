@if(count($logs) > 0)
<div class="bg-white overflow-hidden shadow py-8 px-5 h-full" style="width:30%;">
    <h3 class="text-xl border-b border-l-8 pl-3 border-gray-500">この案件の操作履歴</h3>

    @foreach($logs as $log)
    <div class="mt-11 px-4">
        <div class="my-4 pb-3 flex items-center">
            <div>
                <span class="text-sm pb-1 block text-blue-600">{{$log->created_at}}</span>
                <p class="pb-3">{{$log->message}}</p>
            </div>
            <i id="view-log-button" class="text-gray-500 ml-auto fas fa-chevron-down"></i>
        </div>
        <div class="border-b border-gray-300 relative">
            <div id="log-content" class="hidden">
                <p>{{$log->message}}</p>
            </div>
        </div>
    </div>
    @endforeach
    <div class="mt-5 md:mt-0 flex justify-center">
        {{$logs->appends(request()->query())->links()}}
    </div>
</div>

@endif