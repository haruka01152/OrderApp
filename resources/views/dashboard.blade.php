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
    <div class="modal-window">
        <h3 class="text-xl pb-8 text-center font-bold">ヘルプ　—　物品到着状況について</h3>
        <p>・　空欄　―　注文書登録なし</p><br>
        <p>・　未　―　注文書登録があるが、到着済みの注文書が1つもない</p><br>
        <p>・　数字/数字　―　登録された注文書のうち、いくつ到着済かを表示<br>
            　 <span class="text-sm pl-1">例：1/5　→　5つ注文書が登録されていて、そのうちの1つが到着済</span></p><br>
        <p>・　✔　―　登録された注文書すべてが到着済</p>

        <a class="js-close button-close bg-red-500 text-center">× 閉じる</a>
    </div>
    <div id="overlay" class="overlay"></div>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                工事物品管理トップ
            </h2>
            <a class="pl-10" href="{{route('dashboard')}}"><i class="far fa-calendar-alt fa-2x"></i></a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="flex mx-auto max-w-full">
            <div class="w-80 h-full bg-white overflow-hidden shadow-xl py-8">
                <form class="flex flex-col items-center w-56 m-auto">
                    @csrf
                    <div>
                        <input type="text" name="find" value="" placeholder="案件名 or お客様名を検索" class="w-full">
                    </div>

                    <div class="pt-5 mr-auto ml-2">
                        @foreach($statuses as $status)
                        <div class="pt-1">
                            <input type="radio" id="status{{$status->id}}" name="status" value="{{$status->id}}" @if(request('status')==$status->id || !request('status') && $status->id == 1)checked @endif>
                            <label for="status{{$status->id}}">{{$status->name}}</label>
                        </div>
                        @endforeach
                    </div>

                    <div class="text-center">
                        <input type="submit" value="検索" class="mt-5 w-52 py-2 border border-gray-400">
                    </div>
                </form>
            </div>

            <div class="mx-5 w-4/5">

                @if(count($alerts) > 0)
                <div class="mb-5 bg-white overflow-hidden shadow-xl p-7 border-4 border-gray-500 border-double">
                    <h3 class="text-xl border-b border-l-8 pl-3 border-gray-500">新着アラート</h3>

                    <div class="pt-11 px-3 table w-full">
                        @foreach($alerts as $alert)
                        <a href="{{route('edit', ['id' => $alert->construction_id])}}" class="table-row text-red-600 hover:bg-gray-100">
                            <div class="table-cell w-3/12">【未着物品あり】　―　{{date('m/d', strtotime($alert->constructions->construction_date))}}工事</div>
                            <div class="table-cell w-3/12">{{$alert->constructions->customer_name}}</div>
                            <div class="table-cell w-4/12">{{$alert->constructions->construction_name}}</div>
                            <div class="table-cell">{{$alert->created_at}}</div>
                        </a>
                        <div class="border-2 border-transparent"></div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="bg-white overflow-hidden shadow-xl p-8">
                    <div class="flex items-center text-blue-500 text-lg mb-7">
                        <a href="{{route('add')}}" class="inline-block border border-blue-500 rounded-lg py-2 px-4 hover:bg-blue-100">
                            <i class="fas fa-pencil-alt"></i>
                            工事作成
                        </a>
                        <a class="cursor-pointer js-open button-open inline-block w-7 h-7 flex justify-center items-center text-xl ml-10 border border-blue-500 rounded-full hover:bg-blue-100" style="padding-top:3px;">
                            ？
                        </a>

                    </div>

                    @if(count($constructions) > 0)
                    <table class="text-center m-auto block overflow-x-scroll whitespace-nowrap w-full">
                        <tr class="bg-gray-200 hover:bg-gray-200">
                            <th>@sortablelink('contract_date', '契約日')</a></th>
                            <th>@sortablelink('construction_date', '工事日')</th>
                            <th>@sortablelink('customer_name', 'お客様名')</th>
                            <th>@sortablelink('construction_name', '案件名')</th>
                            <th>物品到着状況</th>
                        </tr>
                        @foreach($constructions as $construction)
                        <tr>
                            <td><a class="td-link" href="{{route('edit', ['id' => $construction->id])}}">{{$construction->contract_date}}</a></td>
                            <td><a class="td-link" href="{{route('edit', ['id' => $construction->id])}}">{{$construction->construction_date}}</a></td>
                            <td><a class="td-link" href="{{route('edit', ['id' => $construction->id])}}">{{$construction->customer_name}}</a></td>
                            <td><a class="td-link" href="{{route('edit', ['id' => $construction->id])}}">{{$construction->construction_name}}</a></td>
                            <td><a class="td-link" href="{{route('edit', ['id' => $construction->id])}}">未</a></td>
                        </tr>
                        @endforeach
                    </table>

                    <div class="mt-5 md:mt-0">
                        {{$constructions->appends(request()->query())->links()}}
                    </div>

                    @else

                    <div class="text-center">
                        <p>データがありません。</p>
                    </div>

                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl p-8 h-full" style="width:25%;">
                <h3 class="text-xl border-b border-l-8 pl-3 border-gray-500">操作履歴</h3>

                <div class="mt-11 px-4">
                    <div class="my-4 pb-3 border-b border-gray-300">
                        <span class="text-sm pb-1 block">2021-08-24 15:36</span>
                        <p class="pb-3">user1さんが新規工事を作成しました。</p>
                        <a href="{{route('edit', ['id' => 1])}}" class="border-b border-red-600 text-sm text-red-600">―　株式会社京都虹彩　FUJITSU PRIMERGY...</a>
                    </div>
                    <div class="my-4 pb-3 border-b border-gray-300">
                        <span class="text-sm pb-1 block">2021-08-24 15:36</span>
                        <p class="pb-3">user1さんが工事内容を修正しました。</p>
                        <a href="{{route('edit', ['id' => 1])}}" class="border-b border-red-600 text-sm text-red-600">―　株式会社京都虹彩　FUJITSU PRIMERGY...</a>
                    </div>
                    <div class="my-4 pb-3 border-b border-gray-300">
                        <span class="text-sm pb-1 block">2021-08-24 15:36</span>
                        <p class="pb-3">user1さんが新規工事を作成しました。</p>
                        <a href="{{route('edit', ['id' => 1])}}" class="border-b border-red-600 text-sm text-red-600">―　株式会社京都虹彩　FUJITSU PRIMERGY...</a>
                    </div>
                    <div class="my-4 pb-3 border-b border-gray-300">
                        <span class="text-sm pb-1 block">2021-08-24 15:36</span>
                        <p class="pb-3">user1さんが新規工事を作成しました。</p>
                        <a href="{{route('edit', ['id' => 1])}}" class="border-b border-red-600 text-sm text-red-600">―　株式会社京都虹彩　FUJITSU PRIMERGY...</a>
                    </div>
                    <div class="my-4 pb-3 border-b border-gray-300">
                        <span class="text-sm pb-1 block">2021-08-24 15:36</span>
                        <p class="pb-3">user1さんが新規工事を作成しました。</p>
                        <a href="{{route('edit', ['id' => 1])}}" class="border-b border-red-600 text-sm text-red-600">―　株式会社京都虹彩　FUJITSU PRIMERGY...</a>
                    </div>
                    <div class="my-4 pb-3 border-b border-gray-300">
                        <span class="text-sm pb-1 block">2021-08-24 15:36</span>
                        <p class="pb-3">user1さんが新規工事を作成しました。</p>
                        <a href="{{route('edit', ['id' => 1])}}" class="border-b border-red-600 text-sm text-red-600">―　株式会社京都虹彩　FUJITSU PRIMERGY...</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>