@extends('layouts.base')

@section('title', 'Contact')

@section('content')
    <section class="relative left-1/2 -translate-x-1/2 w-[calc(100vw-2rem)] max-w-[90rem] bg-white border border-gray-200 rounded-2xl p-8 md:p-12 shadow-sm">
        <div class="max-w-3xl mx-auto text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Contact DreamScape Interactive</h1>
            <p class="text-gray-700 leading-relaxed">
                Need help with your account, item issues, or trading problems? Send us a message and we will get back to you as quickly as possible.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
            <article class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">General Support</h2>
                <p class="text-sm text-gray-600 mb-3">For account help, login problems, and profile settings.</p>
                <p class="text-sm text-gray-800"><strong>Email:</strong> support@dreamscapeinteractive.com</p>
                <p class="text-sm text-gray-800"><strong>Response time:</strong> within 24 hours</p>
            </article>

            <article class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Trade & Inventory Help</h2>
                <p class="text-sm text-gray-600 mb-3">For missing items, failed offers, or locked trades.</p>
                <p class="text-sm text-gray-800"><strong>Email:</strong> trades@dreamscapeinteractive.com</p>
                <p class="text-sm text-gray-800"><strong>Response time:</strong> within 12 hours</p>
            </article>

            <article class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Business & Partnerships</h2>
                <p class="text-sm text-gray-600 mb-3">For collaboration requests and commercial inquiries.</p>
                <p class="text-sm text-gray-800"><strong>Email:</strong> business@dreamscapeinteractive.com</p>
                <p class="text-sm text-gray-800"><strong>Response time:</strong> 1-3 business days</p>
            </article>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 rounded-xl border border-gray-200 p-6 bg-white">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Send Us a Message</h2>

                <form class="space-y-4" onsubmit="event.preventDefault(); alert('Thank you! Your message has been prepared.');">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="contact-name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input id="contact-name" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400" placeholder="Your full name">
                        </div>

                        <div>
                            <label for="contact-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input id="contact-email" type="email" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400" placeholder="you@example.com">
                        </div>
                    </div>

                    <div>
                        <label for="contact-topic" class="block text-sm font-medium text-gray-700 mb-1">Topic</label>
                        <select id="contact-topic" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400">
                            <option>General Support</option>
                            <option>Trade Issue</option>
                            <option>Inventory Issue</option>
                            <option>Bug Report</option>
                            <option>Partnership</option>
                        </select>
                    </div>

                    <div>
                        <label for="contact-message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea id="contact-message" rows="6" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400" placeholder="Describe your question or issue..."></textarea>
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs text-gray-500">Do not share passwords or sensitive account details.</p>
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm rounded-md bg-black text-white hover:bg-gray-800 transition">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>

            <aside class="space-y-6">
                <section class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Support Hours</h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li><strong>Mon-Fri:</strong> 08:00 - 18:00 CET</li>
                        <li><strong>Sat:</strong> 10:00 - 14:00 CET</li>
                        <li><strong>Sun:</strong> Closed</li>
                    </ul>
                </section>

                <section class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Quick FAQ</h3>
                    <div class="space-y-3 text-sm text-gray-700">
                        <p><strong>My trade is stuck on pending.</strong><br>Check your offered trades overview; status updates appear there first.</p>
                        <p><strong>I cannot find an item.</strong><br>Use the inventory search and verify quantity is above 0.</p>
                        <p><strong>Offer button does not appear.</strong><br>The trade may already be assigned or you do not own wanted items.</p>
                    </div>
                </section>
            </aside>
        </div>
    </section>
@endsection
