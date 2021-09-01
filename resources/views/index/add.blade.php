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
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            工事作成
        </h2>
    </x-slot>

    <!-- パンくずリスト -->
    <div class="flex items-center py-2 px-8 bg-white shadow-xl border-t-2 border-gray-200">
        <a href="{{route('dashboard')}}" class="text-blue-500 pr-3">工事物品管理トップ</a>
        <i class="fas fa-chevron-right text-gray-500 mr-3"></i>
        <a href="{{route('add')}}" class="text-blue-500 pr-3">工事作成</a>
    </div>

    <div class="pt-12 pb-28">
        <form class="max-w-7xl lg:container m-auto bg-white overflow-hidden shadow-xl py-8 px-16" action="" method="post">
            <div>
                <h3 class="text-xl border-b border-l-8 pl-3 border-gray-500">工事情報</h3>

                <div class="flex pt-10">
                    <div class="flex flex-col">
                        <label for="contract_date">契約日</label>
                        <input type="date" id="contract_date" name="contract_date" value="" class="mt-1">
                    </div>
                    <div class="flex flex-col ml-10">
                        <label for="construct_date">工事日</label>
                        <input type="date" id="construct_date" name="construct_date" value="" class="mt-1">
                    </div>
                </div>

                <div class="flex flex-col pt-10 w-1/4">
                    <label for="customer_name">お客様名</label>
                    <input type="text" id="customer_name" name="customer_name" value="" class="mt-1">
                </div>
                <div class="flex flex-col pt-10 w-2/4">
                    <label for="project_title">案件名</label>
                    <input type="text" id="project_title" name="project_title" value="" class="mt-1">
                </div>
            </div>

            <div>
                <h3 class="mt-16 text-xl border-b border-l-8 pl-3 border-gray-500">発注物品</h3>

                <div>
                    <h4 class="pt-10 pb-3 text-gray-800">◆アラート設定</h4>

                    <div class="flex justify-around py-5 border-4 border-gray-500 border-double">
                        <div>
                            <label for="">2週間前</label>
                            <input type="radio" name="alert" value="" id="" checked>
                        </div>
                        <div>
                            <label for="">1週間前</label>
                            <input type="radio" name="alert" value="" id="">
                        </div>
                        <div>
                            <label for="">5日前</label>
                            <input type="radio" name="alert" value="" id="">
                        </div>
                        <div>
                            <label for="">2日前</label>
                            <input type="radio" name="alert" value="" id="">
                        </div>
                    </div>

                    <p class="text-sm pt-3 text-right">※すべて土日祝を除いた日数</p>
                </div>

                <div>
                    <h4 class="pt-10 pb-3 text-gray-800">◆注文書登録</h4>

                    <label for="image" class="relative block bg-blue-50 border-2 border-blue-200 border-dashed w-full mb-10 text-center py-24 m-auto text-gray-600">
                        <input type="file" id="image" name="image" style="padding-top:285px; width:100%; border:0; outline:0; color:black;" multiple>
                        ここにファイルをドロップ<br>or<br>下のボタンをクリックして選択
                    </label>

                </div>
            </div>

            <div class="py-7 text-center block fixed -inset-x-0 -bottom-0" style="background:rgba(0,0,0,.2);">
                <input type="submit" value="確定" class="bg-blue-500 text-white text-xl rounded-lg py-2 px-8 cursor-pointer">
            </div>
        </form>


    </div>

</x-app-layout>