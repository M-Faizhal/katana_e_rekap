@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    <!-- Sidebar Component -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 overflow-hidden">
        <!-- Header Component -->
        @include('components.header')

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('page-content')
        </main>
    </div>
</div>
@endsection
