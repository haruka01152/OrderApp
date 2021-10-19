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

    * {
        box-sizing: border-box;
    }
</style>

<x-app-layout>
@include('components.header')


    <!-- パンくずリスト -->
    <div class="flex items-center py-2 px-8 bg-white shadow border-t-2 border-gray-200">
        <div class="lg:container m-auto">
        <a href="{{route('dashboard')}}" class="text-blue-500 pr-3">工事物品管理トップ</a>
        <i class="fas fa-chevron-right text-gray-500 mr-3"></i>
        <a href="{{route('edit', ['id' => $construction->id])}}" class="text-blue-500 pr-3">案件編集（削除済み）</a>
        </div>
    </div>

    <div class="pt-12 pb-28">
        <div class="flex justify-between mx-auto lg:container">
            <form class="w-8/12 bg-white overflow-hidden shadow py-8 px-16" action="{{route('restore', ['id' => $construction->id])}}" method="post">
                @csrf
                <div>
                        <h3 class="text-xl border-b border-l-8 pl-3 border-gray-500">案件情報</h3>

                    <div class="flex pt-10">
                        <div class="flex flex-col">
                            <label for="contract_date">契約日</label>
                            <input type="date" id="contract_date" name="contract_date" value="@if($construction->contract_date){{$construction->contract_date}}@endif" class="mt-1" readonly>
                        </div>
                        <div class="flex flex-col ml-10">
                            <label for="construction_date">工事日</label>
                            <input type="date" id="construction_date" name="construction_date" value="@if($construction->construction_date){{$construction->construction_date}}@endif" class="mt-1" readonly>
                        </div>
                    </div>

                    <div class="flex flex-col pt-10 w-2/4">
                        <label for="customer_name">お客様名</label>
                        <input type="text" id="customer_name" name="customer_name" value="@if($construction->customer_name){{$construction->customer_name}}@endif" class="mt-1" readonly>
                    </div>
                    <div class="flex flex-col pt-10 w-2/4">
                        <label for="">案件名</label>
                        <input type="text" id="construction_name" name="construction_name" value="@if($construction->construction_name){{$construction->construction_name}}@endif" class="mt-1" readonly>
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
                            <div class="table-cell"><a href="{{asset(str_replace('public/','storage/',$order->path))}}" class="pdf cursor-pointer text-blue-500">{{{Str::limit($order->image, 30, '…')}}}</a></div>
                            <div class="table-cell p-0">
                                <input type="text" name="orders[{{$order->id}}][memo]" value="{{$order->memo}}" class="block w-full border-none text-center" readonly>
                            </div>
                            <div class="table-cell p-0">
                                <input type="checkbox" name="orders[{{$order->id}}][arrive_status]" id="order{{$order->id}}" value="1" @if($order->arrive_status == 1)checked @endif disabled>
                                <label for="order{{$order->id}}" class="block w-full h-full" style="border:none;">
                                    <i class="fas fa-check fa-2x text-gray-500"></i>
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
                                <input type="radio" name="alert_config" value="{{$alert_config->period}}" id="{{$alert_config->name}}" {{ $construction->alert_config == $alert_config->period ? 'checked' : '' }} disabled>
                            </div>
                            @endforeach
                        </div>

                        <p class="text-sm pt-3 text-right">※すべて土日祝を除いた日数</p>
                    </div>
                </div>

                <div class="py-7 text-center block fixed -inset-x-0 -bottom-0 z-10" style="background:rgba(0,0,0,.2);">
                    <input type="button" onclick="submit();" value="復元" class="bg-yellow-600 text-white text-xl rounded-lg py-2 px-8 cursor-pointer">
                </div>
            </form>

            @include('components.logs')
        </div>
    </div>

</x-app-layout>