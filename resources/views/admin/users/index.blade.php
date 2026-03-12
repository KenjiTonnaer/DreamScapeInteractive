@extends('layouts.base')

@section('title', 'Admin Users')

@section('content')
    @include('admin.partials.nav')
    <livewire:admin-users-page />
@endsection
