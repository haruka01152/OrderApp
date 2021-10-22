@if($construction->construction_date == $date->format('Y-m-d'))
<div class="relative">
<a href="{{route('edit', ['id' => $construction->id])}}" class="a-text w-11/12 inline-block mb-1 bg-gray-500 text-white text-center whitespace-wrap text-sm rounded-lg p-1 {{$construction->status == 2 ? 'bg-gray-500' : 'bg-red-400'}}">{{Str::limit($construction->customer_name, '15', '…')}}</a>
<p class="hidden w-36 text-sm text-gray-700 fukidashi absolute z-20 top-7 left-0 bg-white border border-gray-500 rounded-lg p-1">{{$construction->construction_name}}</p><br>
</div>
@endif
