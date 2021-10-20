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

    .ordersCheck {
        display: none !important;
        /* チェックボックスは非表示 */
    }

    /* --- チェックボックス直後のlabel --- */
    .ordersLabel {
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
</style>

<x-app-layout>
@include('components.header')


    <!-- パンくずリスト -->
    <div class="flex items-center py-2 px-8 bg-white shadow border-t-2 border-gray-200">
        <div class="lg:container m-auto">
            <a href="{{route('dashboard')}}" class="text-blue-500 pr-3">工事物品管理トップ</a>
            <i class="fas fa-chevron-right text-gray-500 mr-3"></i>
            <a href="{{route('edit', ['id' => $construction->id])}}" class="text-blue-500 pr-3">案件編集</a>
            <i class="fas fa-chevron-right text-gray-500 mr-3"></i>
            <a href="{{route('delete', ['id' => $construction->id])}}" class="text-blue-500 pr-3">工事削除</a>
        </div>
    </div>

    <div class="pt-12 pb-28">
    <form id="deleteForm" class="max-w-7xl lg:container m-auto w-8/12 bg-white overflow-hidden shadow py-8 px-16" action="" method="post">
            @csrf
            <div>
                <p class="p-1 bg-red-600 text-white text-lg mb-10">　※ この案件を削除しようとしています。案件は後から復元可能ですが、関連するアラートは完全に削除されますのでご注意ください。</p>
                <div class="flex justify-between">
                    <h3 class="w-full text-xl border-b border-l-8 pl-3 border-gray-500">案件情報</h3>
                </div>
                <div class="flex flex-col pt-10">
                    <label for="order_status">ステータス</label>
                    <select name="order_status" id="order_status" class="mt-1 w-2/12">
                    @foreach($order_statuses as $status)
                        <option value="{{$status->id}}" {{$construction->order_status == $status->id ? 'selected' : ''}}>{{$status->name}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="flex pt-10">
                    <div class="flex flex-col">
                        <label for="contract_date">契約日</label>
                        <input type="date" id="contract_date" name="contract_date" value="{{$construction->contract_date}}" class="mt-1">
                    </div>
                    <div class="flex flex-col ml-10">
                        <label for="construction_date">工事日</label>
                        <input type="date" id="construction_date" name="construction_date" value="{{$construction->construction_date}}" class="mt-1">
                    </div>
                </div>
                <div class="flex flex-col pt-10 w-2/4">
                    <label for="customer_name">お客様名</label>
                    <input type="text" id="customer_name" name="customer_name" value="{{$construction->customer_name}}" class="mt-1">
                </div>
                <div class="flex flex-col pt-10 w-2/4">
                    <label for="">案件名</label>
                    <input type="text" id="construction_name" name="construction_name" value="{{$construction->construction_name}}" class="mt-1">
                </div>
            </div>

            <div>
                <h3 class="mt-16 text-xl border-b border-l-8 pl-3 border-gray-500">発注物品</h3>

                @if(count($orders) > 0)
                <h4 class="pt-10 pb-3 text-gray-800">◆登録済みの注文書　<span>（到着状況：　{{$construction->arrive_status == '✔' ? 'すべて到着' : $construction->arrive_status}}）</span></h4>

                <div class="table">
                    <div class="table-row bg-gray-200">
                        <div class="table-cell w-1/12">登録日</div>
                        <div class="table-cell">注文書データ</div>
                        <div class="table-cell w-4/12">備考</div>
                        <div class="table-cell w-2/12">到着状況<span class="text-xs"></span></div>
                    </div>

                    @foreach($orders as $order)
                    <input type="hidden" name="orders[{{$order->id}}][id]" value="{{$order->id}}">
                    <div class="table-row">
                        <div class="table-cell">{{date('m/d', strtotime($order->created_at))}}</div>
                        <div class="table-cell">
                            <a href="{{asset(str_replace('public/','storage/',$order->path))}}" class="pdf cursor-pointer text-blue-500">{{{Str::limit($order->image, 30, '…')}}}</a>
                        </div>
                        <div class="table-cell" style="padding:0;">
                            <input type="text" name="orders[{{$order->id}}][memo]" value="{{$order->memo}}" class="block w-full border-none text-center">
                        </div>
                        <div class="table-cell p-0">
                            <input type="checkbox" name="orders[{{$order->id}}][arrive_status]" class="ordersCheck" id="order{{$order->id}}" value="1" @if($order->arrive_status == 1)checked @endif disabled>
                            <label for="order{{$order->id}}" class="ordersLabel block w-full h-full" style="border:none;">
                                <i class="fas fa-check fa-2x text-gray-500"></i>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <div>
                    <h4 class="pt-10 pb-3 text-gray-800">◆アラート発信日</h4>

                    <div class="flex items-center">
                        <input type="date" name="alert_config" id="alert_config" value="@if($construction->alert_config){{$construction->alert_config}}@endif" {{$construction->alert_config == null ? 'disabled class=bg-gray-200' : ''}}>
                        <input type="checkbox" name="notAlert" id="notAlert" value="null" class="ml-10 mr-2" {{$construction->alert_config == null ? 'checked' : ''}}>
                        <label for="notAlert">アラートを設定しない</label>
                    </div>
                </div>

                <div>
                    <h4 class="pt-16 pb-3 text-gray-800">◆案件・発注備考</h4>
                    <textarea name="remarks" class="w-full" rows="3">{{$construction->remarks}}</textarea>
                </div>
            </div>

            <div class="py-7 text-center block fixed -inset-x-0 -bottom-0 z-10" style="background:rgba(0,0,0,.2);">
                <input id="deleteSubmit" type="button" onclick="submit();" value="内容を確認して削除" class="bg-red-600 text-white text-xl rounded-lg py-2 px-8 cursor-pointer">
            </div>
        </form>
    </div>

</x-app-layout>

<script>
    $('#deleteForm').find('input, textarea, select').not('#deleteSubmit, input:hidden').prop('disabled', true);
</script>