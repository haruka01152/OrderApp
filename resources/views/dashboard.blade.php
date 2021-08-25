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
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            工事物品管理トップ
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="flex mx-auto max-w-full">
            <div class="w-80 bg-white overflow-hidden shadow-xl p-8">
                <form class="flex flex-col items-center">
                    @csrf
                    <div class="w-60">
                        <input type="text" name="find" value="" placeholder="案件名 or お客様名を検索" class="w-full">
                    </div>

                    <div class="pt-5 mr-auto ml-2">
                        <div>
                            <input type="radio" id="notchecked" name="view" value="" checked>
                            <label for="notchecked">物品未着</label>
                        </div>
                        <div>
                            <input type="radio" id="checked" name="view" value="">
                            <label for="checked">物品到着済</label>
                        </div>
                        <div>
                            <input type="radio" id="all" name="view" value="">
                            <label for="all">すべて</label>
                        </div>
                    </div>

                    <div class="text-center">
                        <input type="submit" value="検索" class="mt-5 w-60 py-2 border border-gray-400">
                    </div>
                </form>
            </div>

            <div class="ml-5 w-4/5 bg-white overflow-hidden shadow-xl p-8">
                <table class="text-center m-auto block overflow-x-scroll whitespace-nowrap w-full">
                    <tr class="bg-gray-200 hover:bg-gray-200">
                        <th>契約日</a></th>
                        <th>工事日</th>
                        <th>お客様名</th>
                        <th>案件名</th>
                        <th>物品到着</th>
                    </tr>
                    <tr>
                        <td><a class="td-link" href="{{route('edit', ['id' => 1])}}">2021-08-24</a></td>
                        <td><a class="td-link" href="{{route('edit', ['id' => 1])}}"></a></td>
                        <td><a class="td-link" href="{{route('edit', ['id' => 1])}}">株式会社京都虹彩</a></td>
                        <td><a class="td-link" href="{{route('edit', ['id' => 1])}}">FUJITSU PRIMERGY TX1310 M3 Server費用一式</a></td>
                        <td><a class="td-link" href="{{route('edit', ['id' => 1])}}"></a></td>
                    </tr>
                    <tr>
                        <td><a class="td-link" href="{{route('edit', ['id' => 1])}}">2021-08-24</a></td>
                        <td><a class="td-link" href="{{route('edit', ['id' => 1])}}">2021-08-24</a></td>
                        <td><a class="td-link" href="{{route('edit', ['id' => 1])}}">光本瓦店有限会社</a></td>
                        <td><a class="td-link" href="{{route('edit', ['id' => 1])}}">PC廃棄料一式</a></td>
                        <td><a class="td-link" href="{{route('edit', ['id' => 1])}}">✔</a></td>
                    </tr>
                    <tr>
                        <td><a class="td-link" href="{{route('edit', ['id' => 1])}}">2021-08-19</a></td>
                        <td><a class="td-link" href="{{route('edit', ['id' => 1])}}">2021-08-30</a></td>
                        <td><a class="td-link" href="{{route('edit', ['id' => 1])}}">株式会社ライフシード</a></td>
                        <td><a class="td-link" href="{{route('edit', ['id' => 1])}}">無線AP取付費用一式（同志社大学様分）</a></td>
                        <td><a class="td-link" href="{{route('edit', ['id' => 1])}}">1/5</a></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>