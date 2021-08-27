<style>
    input[type="file"] {
    position:absolute;
    bottom:-50px;
    left:0;
}

*{
    outline: none;
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
        <a href="{{route('edit', ['id' => 1])}}" class="text-blue-500 pr-3">工事作成</a>
    </div>

    <div class="py-12">
        <div>
            <form class="max-w-7xl lg:container m-auto bg-white overflow-hidden shadow-xl py-8 px-16" action="" method="post">
                <div>
                    <h2 class="text-xl border-b border-l-8 pl-3 border-gray-500">工事情報</h2>

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
                    <h2 class="mt-16 text-xl border-b border-l-8 pl-3 border-gray-500">発注物品</h2>

                    <label for="image" class="relative block bg-blue-50 border-2 border-blue-200 border-dashed w-full my-10 text-center py-24 m-auto text-gray-600">
                        <input type="file" id="image" name="image" style="padding-top:285px; width:100%; border:0; outline:0; color:black;" multiple>
                        ここにファイルをドロップ<br>or<br>下のボタンをクリックして選択
                    </label>
                </div>

                <div class="pt-16 pb-5 text-center block">
                    <input type="submit" value="確定" class="bg-blue-500 text-white text-xl rounded-lg py-2 px-8 cursor-pointer">
                </div>
            </form>
        </div>
    </div>
</x-app-layout>