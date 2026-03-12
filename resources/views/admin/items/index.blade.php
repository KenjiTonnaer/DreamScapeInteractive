@extends('layouts.base')

@section('title', 'Admin Items')

@section('content')
    @include('admin.partials.nav')
    <livewire:admin-items-page />
@endsection
