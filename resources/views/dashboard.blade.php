<x-app-layout>
@section('title', 'ダッシュボード')
@include('components.messageBox')
    <div class="modal-window">
        <h3 class="text-xl pb-8 text-center font-bold">ヘルプ　—　物品到着状況について</h3>
        <div class="pt-5 px-3">
            <p>＊ 空欄　→　注文書登録なし</p><br>
            <p>＊ 数字/数字　→　登録された注文書のうち、いくつ到着済かを表示<br>
                　 <span class="text-sm pl-1">例：1/5　→　5つ注文書が登録されていて、そのうちの1つが到着済</span></p><br>
            <p>＊ ✔　→　登録された注文書すべてが到着済</p>
        </div>
        <a class="js-close button-close bg-red-500 text-center">× 閉じる</a>
    </div>
    <div id="overlay" class="overlay"></div>
    @include('components.header')

    @include('components.breadCrumb')
    <div class="py-6">
        <div class="mx-auto lg:container">
            <div>

                @if(count($alerts) > 0)
                @include('components.alertBoard')
                @endif

                <div class="bg-white overflow-hidden shadow p-8">
                    <div class="flex items-center text-blue-500 text-lg mb-7">
                        <a href="{{route('add')}}" class="inline-block border border-blue-500 rounded-lg py-2 px-4 hover:bg-blue-100">
                            <i class="fas fa-pencil-alt"></i>
                            案件作成
                        </a>
                        <a title="ヘルプ" class="cursor-pointer js-open button-open inline-block w-7 h-7 flex justify-center items-center text-xl ml-10 border border-blue-500 rounded-full hover:bg-blue-100" style="padding-top:3px;">
                            ？
                        </a>

                        @if(count($constructions) > 0)
                        <div class="ml-auto flex items-center">
                            <p class="text-gray-600 pl-10">{{$constructions->firstItem()}} - {{$constructions->lastItem()}} 件を表示しています（全{{$constructions->total()}}件）</p>
                            <div class="-mt-1">
                                {{$constructions->appends(request()->query())->links()}}
                            </div>
                        </div>
                        @endif
                    </div>

                    @if(count($constructions) > 0)
                    <table class="text-center m-auto block overflow-x-scroll whitespace-nowrap w-full">
                        <tr class="bg-yellow-200 hover:bg-yellow-200">
                            <th class="w-1/12">発注状況</th>
                            <th title="契約日で並べ替え" class="w-1/12">@sortablelink('contract_date', '契約日')</th>
                            <th title="工事日で並べ替え" class="w-1/12">@sortablelink('construction_date', '工事日')</th>
                            <th class="w-3/12">お客様名</th>
                            <th class="w-4/12">案件名</th>
                            <th>物品到着状況</th>
                        </tr>
                        @foreach($constructions as $construction)
                        <tr class="{{$construction->status == 3 ? 'bg-gray-300 hover:bg-gray-300' : ''}}">
                            <td><a class="td-link relative" href="{{route('edit', ['id' => $construction->id])}}">
                                @if($construction->order_status == 2)
                                <i class="fas fa-exclamation-circle text-yellow-500 absolute left-1 top-1"></i>
                                @elseif($construction->order_status == 3)
                                <i class="fas fa-exclamation-circle text-red-500 absolute left-1 top-1"></i>
                                @endif
                                {{$construction->order_statuses->name}}</a></td>
                            <td><a class="td-link" href="{{route('edit', ['id' => $construction->id])}}">{{$construction->contract_date}}</a></td>
                            <td><a class="td-link" href="{{route('edit', ['id' => $construction->id])}}">{{$construction->construction_date}}</a></td>
                            <td><a class="td-link" href="{{route('edit', ['id' => $construction->id])}}">{{Str::limit($construction->customer_name, 35, '…')}}</a></td>
                            <td><a class="td-link" href="{{route('edit', ['id' => $construction->id])}}">{{Str::limit($construction->construction_name, 50, '…')}}</a></td>
                            <td><a class="td-link" href="{{route('edit', ['id' => $construction->id])}}">{{$construction->arrive_status}}</a></td>
                        </tr>
                        @endforeach
                    </table>
                    @else

                    <div class="text-center">
                        <p class="py-10">データがありません。</p>
                    </div>

                    @endif
                </div>

                <div class="fixed right-0 bottom-0">
                    <a title="各種設定" href="{{route('profile.show')}}" class="bg-white inline-block h-20 w-20 flex items-center justify-center" style="border-radius:100px 0 10px 0;"><i class="pt-3 pl-3 fas fa-cogs text-original-blue fa-3x transition duration-500 ease-in-out transform hover:-translate-x-4 hover:-translate-y-1 hover:-rotate-12"></i></a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>