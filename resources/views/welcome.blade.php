@extends('layouts.base')

@section('title', 'Home')

@section('content')
    <section class="bg-white border border-gray-200 rounded-2xl p-8 md:p-12 shadow-sm">
        <div class="max-w-3xl">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Beheer, ontdek en verhandel jouw virtuele items
            </h1>

            <p class="text-gray-700 leading-relaxed mb-8">
                DreamScape Interactive bouwt een gebruiksvriendelijk platform voor inventarisbeheer en trading.
                Van wapens tot armor en accessoires: alles overzichtelijk op één plek.
            </p>

            <div class="flex flex-wrap gap-3">
                <a href="#inventaris" class="px-5 py-2.5 rounded-lg bg-black text-white hover:bg-gray-800 transition">
                    Bekijk Inventaris!
                </a>

                <a href="#traden" class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-800 hover:bg-gray-50 transition">
                    Begin hier met Traden!
                </a>
            </div>
        </div>
    </section>

    <section id="meer" class="grid md:grid-cols-2 gap-6 mt-8">
        <article class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h2 class="text-xl font-semibold mb-2">Slim inventarisbeheer</h2>
            <p class="text-gray-700">
                Bekijk al je items centraal, inclusief type, hoeveelheid en beschikbaarheid.
            </p>
        </article>

        <article class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h2 class="text-xl font-semibold mb-2">Veilig traden</h2>
            <p class="text-gray-700">
                Maak en volg trades met duidelijke status en itemoverzicht.
            </p>
        </article>

        <article class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm md:col-span-2">
            <h2 class="text-xl font-semibold mb-2">Uitgebreide itemcatalogus</h2>
            <p class="text-gray-700 mb-3">
                Ontdek een brede catalogus met wapens, armor, accessoires en meer.
            </p>
            <ul class="list-disc pl-6 text-gray-700 space-y-1">
                <li>Wapens</li>
                <li>Armor</li>
                <li>Accessoires</li>
                <li>Speciale en zeldzame items</li>
            </ul>
        </article>
    </section>
@endsection
