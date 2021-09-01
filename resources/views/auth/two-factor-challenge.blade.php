<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <div x-data="{ recovery: false }">
            <div class="mb-4 text-sm text-gray-600" x-show="! recovery">
                認証アプリが発行するコードを入力してください。
            </div>

            <div class="mb-4 text-sm text-gray-600" x-show="recovery">
                リカバリーコードを入力してください。
            </div>

            <x-jet-validation-errors class="mb-4" />

            <form method="POST" action="/two-factor-challenge">
                @csrf

                <div class="mt-4" x-show="! recovery">
                    <x-jet-label value="Code" />
                    <x-jet-input class="block mt-1 w-full" type="text" name="code" autofocus x-ref="code" autocomplete="one-time-code" />
                </div>

                <div class="mt-4" x-show="recovery">
                    <x-jet-label value="リカバリーコード" />
                    <x-jet-input class="block mt-1 w-full" type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                                    x-show="! recovery"
                                    x-on:click="
                                        recovery = true;
                                        $nextTick(() => { $refs.recovery_code.focus() })
                                    ">
                        リカバリーコードを使う
                    </button>

                    <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                                    x-show="recovery"
                                    x-on:click="
                                        recovery = false;
                                        $nextTick(() => { $refs.code.focus() })
                                    ">
                        認証コードを使う
                    </button>

                    <x-jet-button class="ml-4">
                        ログイン
                    </x-jet-button>
                </div>
            </form>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>