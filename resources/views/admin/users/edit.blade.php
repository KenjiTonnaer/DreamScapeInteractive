@extends('layouts.base')

@section('title', 'Edit User')

@section('content')
    @include('admin.partials.nav')
    <livewire:admin-user-create-page :user-id="$user->id" />
@endsection
