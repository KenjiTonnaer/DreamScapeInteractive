@extends('layouts.base')

@section('title', 'Home')

@section('content')
    <section class="bg-white border border-gray-200 rounded-2xl p-8 md:p-12 shadow-sm">
        <div class="max-w-3xl">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Manage, discover, and trade your virtual items
            </h1>

            <p class="text-gray-700 leading-relaxed mb-8">
                DreamScape Interactive builds a user-friendly platform for inventory management and trading.
                From weapons to armor and accessories, everything is organized in one place.
            </p>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('inventaris') }}" class="px-5 py-2.5 rounded-lg bg-black text-white hover:bg-gray-800 transition">
                    View Inventory
                </a>

                <a href="{{ route('trades') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-800 hover:bg-gray-50 transition">
                    Start Trading
                </a>
            </div>
        </div>
    </section>

    <section id="more" class="grid md:grid-cols-2 gap-6 mt-8">
        <article class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h2 class="text-xl font-semibold mb-2">Smart Inventory Management</h2>
            <p class="text-gray-700">
                View all your items in one place, including type, quantity, and availability.
            </p>
        </article>

        <article class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h2 class="text-xl font-semibold mb-2">Safe Trading</h2>
            <p class="text-gray-700">
                Create and track trades with clear statuses and item overviews.
            </p>
        </article>

        <article class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm md:col-span-2">
            <h2 class="text-xl font-semibold mb-2">Extensive Item Catalog</h2>
            <p class="text-gray-700 mb-3">
                Explore a broad catalog with weapons, armor, accessories, and more.
            </p>
            <ul class="list-disc pl-6 text-gray-700 space-y-1">
                <li>Weapons</li>
                <li>Armor</li>
                <li>Accessories</li>
                <li>Special and rare items</li>
            </ul>
        </article>
    </section>
@endsection
