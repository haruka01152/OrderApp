<x-app-layout>

    @include('components.messageBox')
    @include('components.errorBar')

    <div id="overlay" class="overlay"></div>

    @include('components.header')


    @include('components.breadCrumb')

    <div class="pt-12 pb-28">
        <div class="flex justify-between mx-auto lg:container">
            @include('components.form')
            @include('components.logs')
        </div>
    </div>

</x-app-layout>