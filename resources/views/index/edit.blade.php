<style>
    input[type="file"] {
        position: absolute;
        bottom: -50px;
        left: 0;
    }

    th,
    td {
        border: 1px solid gray;
        padding: .7rem 1.5rem;
    }

    tbody {
        width: 100%;
        display: table;
    }

    input[type="checkbox"] {
        display: none;
        /* チェックボックスは非表示 */
    }

    /* --- チェックボックス直後のlabel --- */
    input[type="checkbox"]+label {
        display: inline-block;
        opacity: 0;
        /* 透明度       */
        cursor: pointer;
        /* カーソル設定 */
        transition: .2s;
        /* なめらか変化 */
        transform: scale(0.8, 0.8);
        /* 少し小さく   */
    }

    /* --- 選択されたチェックボックス直後のlabel --- */
    input[type="checkbox"]:checked+label {
        opacity: 1;
        /* 透明度       */
        transform: scale(1, 1);
        /* 原寸に戻す   */
    }

    .table,
    .table-cell {
        border: 1px solid gray;
        border-collapse: collapse;
    }

    .table {
        width: 100%;
        text-align: center;
    }

    .table-cell {
        padding: .7rem 1.5rem;
        vertical-align: middle;
    }
    *{
        box-sizing: border-box;
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            工事編集
        </h2>
    </x-slot>

    <!-- パンくずリスト -->
    <div class="flex items-center py-2 px-8 bg-white shadow-xl border-t-2 border-gray-200">
        <a href="{{route('dashboard')}}" class="text-blue-500 pr-3">工事物品管理トップ</a>
        <i class="fas fa-chevron-right text-gray-500 mr-3"></i>
        <a href="{{route('edit', ['id' => 1])}}" class="text-blue-500 pr-3">工事編集</a>
    </div>

    <div class="pt-12 pb-28">
        <div class="flex justify-between">
            <form class="mx-10 max-w-7xl lg:container m-auto bg-white overflow-hidden shadow-xl py-8 px-16" action="" method="post">
                @csrf
                <div>
                    <div class="flex justify-between">
                    <h3 class="w-11/12 text-xl border-b border-l-8 pl-3 border-gray-500">工事情報</h3>

                    <a href=""><i class="fas fa-trash-alt fa-2x text-gray-600 transition-all transform duration-300 hover:scale-110 hover:opacity-80"></i>
</a>
                    </div>

                    <div class="flex pt-10">
                        <div class="flex flex-col">
                            <label for="contract_date">契約日</label>
                            <input type="date" id="contract_date" name="contract_date" value="@if($construction->contract_date){{$construction->contract_date}}@else {{old('contract_date')}}@endif" class="mt-1">
                        </div>
                        <div class="flex flex-col ml-10">
                            <label for="construction_date">工事日</label>
                            <input type="date" id="construction_date" name="construction_date" value="@if($construction->construction_date){{$construction->construction_date}}@else {{old('construction_date')}}@endif" class="mt-1">
                        </div>
                    </div>

                    <div class="flex flex-col pt-10 w-1/4">
                        <label for="customer_name">お客様名</label>
                        <input type="text" id="customer_name" name="customer_name" value="@if($construction->customer_name){{$construction->customer_name}} @else {{old('customer_name')}} @endif" class="mt-1">
                    </div>
                    <div class="flex flex-col pt-10 w-2/4">
                        <label for="">案件名</label>
                        <input type="text" id="construction_name" name="construction_name" value="@if($construction->construction_name){{$construction->construction_name}} @else {{old('construction_name')}} @endif" class="mt-1">
                    </div>
                </div>

                <div>
                    <h3 class="mt-16 text-xl border-b border-l-8 pl-3 border-gray-500">発注物品</h3>

                    @if(count($orders) > 0)
                    <h4 class="pt-10 pb-3 text-gray-800">◆登録済みの注文書</h4>

                    <div class="table">
                        <div class="table-row bg-gray-200">
                            <div class="table-cell w-1/12">登録日</div>
                            <div class="table-cell">注文書データ</div>
                            <div class="table-cell w-4/12">備考</div>
                            <div class="table-cell w-2/12">到着状況<span class="text-xs"><br>※クリックしてチェック</span></div>
                        </div>
                        @foreach($orders as $order)
                        <input type="hidden" name="orders[{{$order->id}}][id]" value="{{$order->id}}">
                        <div class="table-row">
                            <div class="table-cell">{{date('m/d', strtotime($order->created_at))}}</div>
                            <div class="table-cell"><a href="">{{$order->image}}</a></div>
                            <div class="table-cell p-0">
                                <input type="text" name="orders[{{$order->id}}][memo]" value="@if($order->memo){{$order->memo}} @else {{old('memo')}} @endif" class="block w-full border-none text-center">
                            </div>
                            <div class="table-cell p-0">
                                <input type="checkbox" name="orders[{{$order->id}}][arrive_status]" id="order{{$order->id}}" value="1" @if($order->arrive_status == 1)checked @endif>
                                <label for="order{{$order->id}}" class="block w-full h-full" style="border:none;">
                                    <i class="fas fa-check fa-2x text-green-500"></i>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div>
                        <h4 class="pt-10 pb-3 text-gray-800">◆アラート設定</h4>

                        <div class="flex justify-around py-5 border-4 border-gray-500 border-double">
                        @foreach($alert_configs as $alert_config)
                        <div>
                            <label for="{{$alert_config->name}}">{{$alert_config->name}}</label>
                            <input type="radio" name="alert_config" value="{{$alert_config->period}}" id="{{$alert_config->name}}" @if(old('alert_config') == $alert_config->period)checked @elseif(env('DEFAULT_ALERT_CONFIG') == $alert_config->period) checked @endif>
                        </div>
                        @endforeach
                        </div>

                        <p class="text-sm pt-3 text-right">※すべて土日祝を除いた日数</p>
                    </div>

                    <div>
                    <h4 class="pt-10 pb-3 text-gray-800">◆注文書登録</h4>

                    <label for="image" class="relative block bg-blue-50 border-2 border-blue-200 border-dashed w-full mb-10 text-center py-24 m-auto text-gray-600">
                        <input type="file" id="image" name="image[]" style="padding-top:285px; width:100%; border:0; outline:0; color:black;" multiple>
                        ここにファイルをドロップ<br>or<br>下のボタンをクリックして選択
                    </label>

                    </div>
                </div>

                <div class="py-7 text-center block fixed -inset-x-0 -bottom-0" style="background:rgba(0,0,0,.2);">
                    <input type="button" onclick="submit();" value="確定" class="bg-blue-500 text-white text-xl rounded-lg py-2 px-8 cursor-pointer">
                </div>
            </form>

            <div class="bg-white overflow-hidden shadow-xl p-8 h-full" style="width:25%;">
                <h3 class="text-xl border-b border-l-8 pl-3 border-gray-500">この工事の操作履歴</h3>

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