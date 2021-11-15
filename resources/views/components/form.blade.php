        <form class="{{\Route::currentRouteName() == 'add' ? 'max-w-7xl lg:container m-auto' : ''}} w-8/12 bg-white overflow-hidden shadow py-8 px-16" action="" method="post" enctype="multipart/form-data">
            @csrf
            @if(isset($construction))
            <input type="hidden" name="construction_id" value="{{$construction->id}}">
            @endif
            <div>
                <div class="flex justify-between items-center">
                    <h3 class="{{\Route::currentRouteName() == 'edit' ? 'w-11/12' : 'w-full'}}  text-xl border-b border-l-8 pl-3 border-gray-500">案件情報</h3>
                    @if(\Route::currentRouteName() == 'edit')
                    <a title="この案件を削除する" href="{{route('delete', ['id' => $construction->id])}}"><i class="fas fa-trash-alt fa-2x text-gray-600 transition-all transform duration-300 hover:scale-110 hover:opacity-80"></i>
                    </a>
                    @endif
                </div>
                <div class="flex flex-col pt-10">
                    <label for="order_status">■ 発注状況</label>
                    <select name="order_status" id="order_status" class="mt-1 w-2/12">
                        @foreach($order_statuses as $status)
                        <option value="{{$status->id}}" {{old('order_status', isset($construction) && $construction->order_status == $status->id) == $status->id ? 'selected' : ''}}>{{$status->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex pt-10">
                    <div class="flex flex-col">
                        <label for="contract_date">■ 契約日</label>
                        <input type="date" id="contract_date" name="contract_date" value="{{old('contract_date',isset($construction) ? $construction->contract_date : '')}}" class="mt-1">
                    </div>
                    <div class="flex flex-col ml-10">
                        <label for="construction_date">■ 工事日</label>
                        <input type="date" id="construction_date" name="construction_date" value="{{request('date') == null ? old('construction_date',isset($construction) ? $construction->construction_date : '') : request('date')}}" class="mt-1">
                    </div>
                </div>
                @if(\Route::currentRouteName() == 'edit'|| request('date'))
                <p id="alert_notice" class="hidden error">※工事日の変更に伴い、アラート発信日が再設定されました。問題があれば変更してください。</p>
                @endif
                <div class="flex flex-col pt-10 w-2/4">
                    <label for="customer_name">■ お客様名</label>
                    <input type="text" id="customer_name" name="customer_name" value="{{old('customer_name',isset($construction) ? $construction->customer_name : '')}}" class="mt-1">
                </div>
                @error('customer_name')
                <p class="error">* {{$message}}</p>
                @enderror
                <div class="flex flex-col pt-10 w-2/4">
                    <label for="">■ 案件名</label>
                    <input type="text" id="construction_name" name="construction_name" value="{{old('construction_name',isset($construction) ? $construction->construction_name : '')}}" class="mt-1">
                </div>
                @error('construction_name')
                <p class="error">* {{$message}}</p>
                @enderror
            </div>

            <div>
                <h3 class="mt-16 text-xl border-b border-l-8 pl-3 border-gray-500">発注物品</h3>

                @if(\Route::currentRouteName() == 'edit' && count($orders) > 0)
                <h4 class="pt-10 pb-3 text-gray-800">■ 登録済みの注文書　<span>（到着状況：　{{isset($construction) && $construction->arrive_status == '✔' ? 'すべて到着' : $construction->arrive_status}}）</span></h4>

                <div class="table">
                    <div class="table-row bg-gray-200">
                        <div class="table-cell w-1/12">登録日</div>
                        <div class="table-cell">注文書データ</div>
                        <div class="table-cell w-4/12">備考</div>
                        <div class="table-cell w-2/12">到着状況<span class="text-xs"><br>※クリックしてチェック</span></div>
                        <div class="table-cell"><i class="fas fa-trash-alt fa-2x text-gray-600"></i></div>
                    </div>

                    @foreach($orders as $order)
                    <div class="table-row">
                        <div class="table-cell">{{date('m/d', strtotime($order->created_at))}}</div>
                        <div class="table-cell">
                            <a href="{{asset(str_replace('public/','storage/',$order->path))}}" class="pdf cursor-pointer text-blue-500">{{{Str::limit($order->image, 30, '…')}}}</a>
                        </div>
                        <div class="table-cell" style="padding:0;">
                            <input type="text" name="orders[order{{$order->id}}_memo]" value="@if($order->memo){{$order->memo}}@endif" class="block w-full border-none text-center">
                        </div>
                        <div class="table-cell p-0">
                            <input type="checkbox" name="orders[order{{$order->id}}_arrive_status]" class="ordersCheck" id="order{{$order->id}}" value="1" @if($order->arrive_status == 1)checked @endif>
                            <label for="order{{$order->id}}" class="ordersLabel block w-full h-full" style="border:none;">
                                <i class="fas fa-check fa-2x text-gray-500"></i>
                            </label>
                        </div>
                        <div class="table-cell p-0">
                            <a href="{{route('deleteOrder')}}?id={{$construction->id}}&orderId={{$order->id}}" class="bg-red-400 text-white w-5 h-3 py-1 px-2">削除</a>
                        </div>
                    </div>
                    @endforeach
                </div>

                @error('orders.*.*')
                <p class="error">* {{$message}}</p>
                @enderror
                @endif

                <div>
                    <h4 class="pt-10 pb-3 text-gray-800">■ 物品未着のアラート発信日</h4>

                    <div class="flex items-center">
                        @if(\Route::currentRouteName() == 'edit')
                        <input type="date" name="alert_config" id="alert_config" value="{{old('alert_config', $construction->alert_config != null ? $construction->alert_config : '')}}" {{$construction->alert_config == null ? 'disabled class=bg-gray-200' : ''}}>
                        @elseif(\Route::currentRouteName() == 'add')
                        <input type="date" name="alert_config" id="alert_config" value="{{old('alert_config', request('date') ? $carbon::createFromFormat('Y-m-d', request('date'))->subDays(5)->format('Y-m-d') : '')}}" {{old('notAlert') != null ? 'disabled class=bg-gray-200' : ''}}>
                        @endif
                        <input type="checkbox" name="notAlert" id="notAlert" value="notAlert" class="ml-10 mr-2" @if(old('notAlert') || (isset($construction) && $construction->alert_config == null))checked @endif>
                        <label for="notAlert">アラートを設定しない</label>
                    </div>
                    @error('alert_config')
                    <p class="error">* {{$message}}</p>
                    @enderror
                </div>

                <div>
                    <h4 class="pt-16 pb-3 text-gray-800">■ 案件・発注備考</h4>
                    <textarea name="remarks" class="w-full" rows="3">{{old('remarks', isset($construction) ? $construction->remarks : '')}}</textarea>
                </div>

                <div>
                    <h4 class="pt-16 pb-3 text-gray-800">■ 注文書登録</h4>
                    @error('images.*')
                    <p class="error">* {{$message}}</p>
                    @enderror

                    <label for="image" class="relative block bg-blue-50 border-2 border-blue-200 border-dashed w-full mb-10 text-center py-24 m-auto text-gray-600">
                        <input type="file" id="image" name="images[]" style="padding-top:285px; width:100%; border:0; outline:0; color:black;" multiple>
                        ここにファイルをドロップ<br>or<br>下のボタンをクリックして選択
                    </label>
                </div>
            </div>

            <div class="py-7 text-center block fixed -inset-x-0 -bottom-0 z-10" style="background:rgba(0,0,0,.2);">
                <input type="button" onclick="submit();" value="確定" class="bg-original-blue text-white text-xl rounded-lg py-2 px-8 cursor-pointer">
            </div>
        </form>