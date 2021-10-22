@if(Session::has('message'))
    <div id="target_msg_box" class="message-box relative bg-red-400 text-white text-lg py-3">
        <p class="message-text lg:container m-auto">{{session('message')}}</p>
    </div>
@endif
