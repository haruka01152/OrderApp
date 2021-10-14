<x-app-layout>

    @if(Session::has('message'))
    <div id="target_msg_box" class="message-box relative bg-red-400 text-white text-lg py-3">
        <p class="message-text lg:container m-auto">{{session('message')}}</p>
    </div>
    @endif
    @include('components.errorBar')


    <div id="overlay" class="overlay"></div>

    @include('components.header')


    <!-- パンくずリスト -->
    <div class="flex items-center py-2 px-8 bg-white shadow border-t-2 border-gray-200">
        <div class="lg:container m-auto">
            <a href="{{route('dashboard')}}" class="text-blue-500 pr-3">工事物品管理トップ</a>
            @if($previousUrl != 0)
            <i class="fas fa-chevron-right text-gray-500 mr-3"></i>
            <a href="{{$previousUrl}}" class="text-blue-500 pr-3">絞り込み : {{$find}}</a>
            @endif
            <i class="fas fa-chevron-right text-gray-500 mr-3"></i>
            <a href="{{route('edit', ['id' => $construction->id])}}" class="text-blue-500 pr-3">案件編集</a>
        </div>
    </div>

    <div class="pt-12 pb-28">
        <div class="flex justify-between mx-auto lg:container">
            @include('components.form')
            @if(count($logs) > 0)
            <div class="bg-white overflow-hidden shadow py-8 px-5 h-full" style="width:30%;">
                <h3 class="text-xl border-b border-l-8 pl-3 border-gray-500">この案件の操作履歴</h3>

                @foreach($logs as $log)
                <!-- <div class="pt-10">
                    <div>
                        <span class="text-sm pb-1 block text-blue-600">{{$log->created_at}}</span>
                        <p class="pb-3">{{$log->message}}</p>
                    </div>
                        <input id="acd-check{{$log->id}}" class="acd-check" type="checkbox">
                        <label class="acd-label" for="acd-check{{$log->id}}"><i class="fas fa-chevron-down"></i></label>
                    <div class="acd-content">
                        <p>{{$log->message}}</p>
                    </div>
                </div> -->

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
        </div>
    </div>

</x-app-layout>