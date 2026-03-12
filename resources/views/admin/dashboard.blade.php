@extends('layouts.base')

@section('title', 'Admin')

@section('content')
    @include('admin.partials.nav')
    <livewire:admin-dashboard />
@endsection
