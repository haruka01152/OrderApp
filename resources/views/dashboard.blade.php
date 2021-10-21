<!-- CSS -->
<style>
    th,
    td {
        border: 1px solid gray;
        padding: 0;
    }

    tbody {
        width: 100%;
        display: table;
    }

    th {
        cursor: default;
    }

    tr {
        cursor: pointer;
    }

    tr:hover {
        background-color: rgba(243, 244, 246, 1);
    }

    th,
    .td-link {
        padding: .7rem 1.5rem;
    }

    .td-link {
        display: inline-block;
        width: 100%;
        height: 100%;
    }

    /* モーダルウィンドウ */
    .modal-window {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        height: 300px;
        border-radius: 5px;
        background: white;
        z-index: 11;
        padding: 2rem;
    }

    /* 閉じるボタン */
    .button-close {
        position: absolute;
        bottom: 1rem;
        left: 50%;
        transform: translate(-50%, 0);
        width: 150px;
        padding: .5em;
        color: #eaeaea;
        border-radius: 20rem;
        cursor: pointer;
    }

    .modal-window {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 550px;
        height: 380px;
        background-color: white;
        border-radius: 5px;
        z-index: 11;
        padding: 2rem;
    }

    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.3);
        width: 100%;
        height: 100%;
        z-index: 10;
    }
</style>

<x-app-layout>

    @if(Session::has('message'))
    <div id="target_msg_box" class="message-box relative bg-red-400 text-white text-lg py-3">
        <p class="message-text lg:container m-auto">{{session('message')}}</p>
    </div>
    @endif
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

    <!-- パンくずリスト -->
    @if(request('find'))
    <div class="flex items-center py-2 px-8 bg-white shadow border-t-2 border-gray-200">
        <div class="lg:container m-auto">
            <a href="{{route('dashboard')}}" class="text-blue-500 pr-3">工事物品管理トップ</a>
            <i class="fas fa-chevron-right text-gray-500 mr-3"></i>
            <a href="{{route('dashboard', ['find' => request('find')])}}" class="text-blue-500 pr-3">絞り込み : {{request('find')}}</a>
        </div>
    </div>
    @endif

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
                            <p class="text-gray-400 pl-10">{{$constructions->firstItem()}} - {{$constructions->lastItem()}} 件を表示しています（全{{$constructions->total()}}件）</p>
                            <div class="-mt-1">
                                {{$constructions->appends(request()->query())->links()}}
                            </div>
                        </div>
                        @endif
                    </div>

                    @if(count($constructions) > 0)
                    <table class="text-center m-auto block overflow-x-scroll whitespace-nowrap w-full">
                        <tr class="bg-gray-200 hover:bg-gray-200">
                            <th class="w-1/12">発注状況</th>
                            <th title="契約日で並べ替え">@sortablelink('contract_date', '契約日')</th>
                            <th title="工事日で並べ替え">@sortablelink('construction_date', '工事日')</th>
                            <th title="お客様名で並べ替え">@sortablelink('customer_name', 'お客様名')</th>
                            <th title="案件名で並べ替え">@sortablelink('construction_name', '案件名')</th>
                            <th>物品到着状況</th>
                        </tr>
                        @foreach($constructions as $construction)
                        <tr class="{{$construction->status == 3 ? 'bg-gray-300 hover:bg-gray-300' : ''}}">
                            <td><a class="td-link" href="{{route('edit', ['id' => $construction->id])}}">{{$construction->order_statuses->name}}</a></td>
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