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
            <div>

                <div class="mb-5 bg-white overflow-hidden shadow p-7 border-4 border-gray-500 border-double">
                    <h3 class="text-xl border-b border-l-8 pl-3 border-gray-500">すべてのアラート</h3>

                    <div class="pt-11 px-3 table w-full">
                        @foreach($alerts as $alert)
                        <a href="{{route('edit', ['id' => $alert->construction_id])}}" class="table-row text-red-600 hover:bg-gray-100">
                            <div class="table-cell w-3/12">【未着物品あり】　―　{{date('m/d', strtotime($alert->constructions->construction_date))}}工事</div>
                            <div class="table-cell w-3/12">{{Str::limit($alert->constructions->customer_name, 20, '…')}}</div>
                            <div class="table-cell w-4/12">{{Str::limit($alert->constructions->construction_name, 30, '…')}}</div>
                            <div class="table-cell">{{$alert->created_at}}</div>
                        </a>
                        <div class="border-2 border-transparent"></div>
                        @endforeach
                    </div>

                    <div class="mt-5 md:mt-0">
                        {{$alerts->appends(request()->query())->links()}}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>