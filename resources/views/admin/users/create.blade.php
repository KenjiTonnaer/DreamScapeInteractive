@extends('layouts.base')

@section('title', 'Create User')

@section('content')
    @include('admin.partials.nav')
    <livewire:admin-user-create-page />
@endsection
