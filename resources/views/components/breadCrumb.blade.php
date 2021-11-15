<!-- パンくずリスト -->
@if(\Route::currentRouteName() == 'dashboard')
@if(request('find'))
<div class="flex items-center py-2 px-8 bg-white shadow border-t-2 border-gray-200">
    <div class="lg:container m-auto">
        <a href="{{route('dashboard')}}" class="text-blue-500 pr-3">工事物品管理トップ</a>
        <i class="fas fa-chevron-right text-gray-500 mr-3"></i>
        <a href="{{route('dashboard', ['find' => request('find')])}}" class="text-blue-500 pr-3">絞り込み : {{request('find')}}</a>
    </div>
</div>
@endif
@else
<div class="flex items-center py-2 px-8 bg-white shadow border-t-2 border-gray-200">
    <div class="lg:container m-auto">
        <a href="{{route('dashboard')}}" class="text-blue-500 pr-3">工事物品管理トップ</a>
        @if(strpos(url()->previous(), 'calender'))
        <i class="fas fa-chevron-right text-gray-500 mr-3"></i>
        <a href="{{url()->previous()}}" class="text-blue-500 pr-3">カレンダー表示</a>
        @elseif(strpos(url()->previous(), 'find'))
        <i class="fas fa-chevron-right text-gray-500 mr-3"></i>
        <a href="{{url()->previous()}}" class="text-blue-500 pr-3">絞り込み: {{str_replace('find=', '', strstr(url()->previous(), 'find='))}}</a>
        @elseif(strpos(url()->previous(), 'edit') && \Route::currentRouteName() != 'edit')
        <i class="fas fa-chevron-right text-gray-500 mr-3"></i>
        <a href="{{url()->previous()}}" class="text-blue-500 pr-3">案件編集</a>
        @endif
        <i class="fas fa-chevron-right text-gray-500 mr-3"></i>
        <a href="{{url()->full()}}" class="text-blue-500 pr-3">
            @if(\Route::currentRouteName() == 'edit')
            @if($construction->status == 3)
            削除済み案件
            @else
            案件編集
            @endif
            @elseif(\Route::currentRouteName() == 'add')
            案件作成
            @elseif(\Route::currentRouteName() == 'alerts')
            アラート一覧
            @elseif(\Route::currentRouteName() == 'delete')
            案件削除
            @elseif(\Route::currentRouteName() == 'logs')
            操作履歴一覧
            @endif
        </a>
    </div>
</div>
@endif