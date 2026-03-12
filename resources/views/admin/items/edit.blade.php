@extends('layouts.base')

@section('title', 'Edit Item')

@section('content')
    @include('admin.partials.nav')
    <livewire:admin-item-form-page :item-id="$item->id" />
@endsection
