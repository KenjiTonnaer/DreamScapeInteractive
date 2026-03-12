@extends('layouts.base')

@section('title', 'Create Item')

@section('content')
    @include('admin.partials.nav')
    <livewire:admin-item-form-page />
@endsection
