<x-app-layout>
    @include('components.errorBar')
    @include('components.header')


    <!-- パンくずリスト -->
    <div class="flex items-center py-2 px-8 bg-white shadow border-t-2 border-gray-200">
        <div class="lg:container m-auto">
            <a href="{{route('dashboard')}}" class="text-blue-500 pr-3">工事物品管理トップ</a>
            <i class="fas fa-chevron-right text-gray-500 mr-3"></i>
            <a href="{{route('add')}}" class="text-blue-500 pr-3">案件作成</a>
        </div>
    </div>

    <div class="pt-12 pb-28">
    @include('components.form')
    </div>

</x-app-layout>