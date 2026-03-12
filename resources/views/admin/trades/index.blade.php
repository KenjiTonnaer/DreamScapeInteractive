@extends('layouts.base')

@section('title', 'Admin Trades')

@section('content')
    @include('admin.partials.nav')
    <livewire:admin-trades-page />
@endsection
