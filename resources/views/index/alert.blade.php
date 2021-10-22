<x-app-layout>

@include('components.header')


@include('components.breadCrumb')

    <div class="py-6">
        <div class="mx-auto lg:container">
            @include('components.alertBoard')
        </div>
    </div>
</x-app-layout>