@if($errors->any())
<div class="error-message-box relative bg-red-500 text-white text-lg py-3">
    <p class="message-text lg:container m-auto">※ エラーが発生しました。入力内容を確認してください。</p>
</div>
@endif

<style>
    .error-message-box {
        animation: flash 1s linear 3;
    }

    @keyframes flash {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }
    }
</style>