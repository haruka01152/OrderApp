<x-app-layout>

@include('components.header')


    <!-- パンくずリスト -->
    <div class="flex items-center py-2 px-8 bg-white shadow border-t-2 border-gray-200">
        <div class="lg:container m-auto">
            <a href="{{route('dashboard')}}" class="text-blue-500 pr-3">工事物品管理トップ</a>
            <i class="fas fa-chevron-right text-gray-500 mr-3"></i>
            <a href="{{route('alerts')}}" class="text-blue-500 pr-3">アラート一覧</a>
        </div>
    </div>

    <div class="py-6">
        <div class="mx-auto lg:container">
            @include('components.alertBoard')
        </div>
    </div>
</x-app-layout>