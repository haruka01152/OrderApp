<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Modaal/0.4.4/css/modaal.min.css">
  @if(\Route::currentRouteName() == 'edit')
  <link rel="stylesheet" href="{{ asset('css/editStyle.css') }}">
  @elseif(\Route::currentRouteName() == 'dashboard')
  <link rel="stylesheet" href="{{ asset('css/dashboardStyle.css') }}">
  @elseif(\Route::currentRouteName() == 'add')
  <link rel="stylesheet" href="{{ asset('css/addStyle.css') }}">
  @endif


  @livewireStyles

  <!-- Scripts -->
  <script src="{{ mix('js/app.js') }}" defer></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Modaal/0.4.4/js/modaal.min.js"></script>
</head>

<body class="font-sans antialiased">
  <x-jet-banner />

  <div class="min-h-screen bg-yellow-100">
    <!-- Page Heading -->
    @if (isset($header))
    <header class="shadow bg-original-blue">
      <div class="lg:container mx-auto py-6">
        {{ $header }}
      </div>
    </header>
    @endif

    <!-- Page Content -->
    <main>
      {{ $slot }}
    </main>
  </div>

  @stack('modals')

  @livewireScripts

  <script src="{{asset('js/index.js')}}"></script>

</body>

</html>