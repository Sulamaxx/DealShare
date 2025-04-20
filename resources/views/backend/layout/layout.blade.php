<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

@include('backend.partials.head')


<body class="dark:bg-neutral-800 bg-neutral-100 dark:text-white">

    <!-- ..::  header area start ::.. -->
    @include('backend.partials.sidebar')
    <!-- ..::  header area end ::.. -->

    <main class="dashboard-main">

        <!-- ..::  navbar start ::.. -->
        @include('backend.partials.navbar')
        <!-- ..::  navbar end ::.. -->
        <div class="dashboard-main-body">

            <!-- ..::  breadcrumb  start ::.. -->
            @include('backend.partials.breadcrumb', [
                'title' => $title ?? '',
                'subTitle' => $subTitle ?? '',
            ])
            <!-- ..::  header area end ::.. -->

            @yield('content')

        </div>
        <!-- ..::  footer  start ::.. -->
        @include('backend.partials.footer')
        <!-- ..::  footer area end ::.. -->

    </main>

    <!-- ..::  scripts  start ::.. -->
    @include('backend.partials.script', ['script' => $script ?? ''])
    <!-- ..::  scripts  end ::.. -->

</body>

</html>
