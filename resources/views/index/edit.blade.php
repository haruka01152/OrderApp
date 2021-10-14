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
            @include('components.logs')
        </div>
    </div>

</x-app-layout>