<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-800 dark:text-white leading-tight">
            {{ __('Terms and Conditions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 shadow-2xl rounded-3xl border border-gray-100 dark:border-gray-800 overflow-hidden">
                <div class="p-8 md:p-16 prose dark:prose-invert max-w-none">
                    <h1 class="text-4xl font-black text-gray-900 dark:text-white mb-6 uppercase tracking-tight">{{ __('Terms and Conditions of Use') }}</h1>
                    <p class="text-gray-500 dark:text-gray-400 mb-10 text-lg font-medium italic border-l-4 border-blue-500 pl-4">
                        {{ __('Effective Date: April 28, 2026. Please read these terms carefully before using our platform.') }}
                    </p>

                    <div class="space-y-12 text-gray-700 dark:text-gray-300">
                        <!-- Section 1 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('1. Agreement to Terms') }}</h2>
                            <p class="leading-relaxed">
                                {{ __('These Terms and Conditions constitute a legally binding agreement made between you, whether personally or on behalf of an entity ("you") and StockProNex ("we," "us," or "our"), concerning your access to and use of the StockProNex website as well as any other media form, media channel, mobile website or mobile application related, linked, or otherwise connected thereto (collectively, the "Site"). You agree that by accessing the Site, you have read, understood, and agreed to be bound by all of these Terms and Conditions. IF YOU DO NOT AGREE WITH ALL OF THESE TERMS AND CONDITIONS, THEN YOU ARE EXPRESSLY PROHIBITED FROM USING THE SITE AND YOU MUST DISCONTINUE USE IMMEDIATELY.') }}
                            </p>
                        </section>

                        <!-- Section 2 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('2. Intellectual Property Rights') }}</h2>
                            <p class="leading-relaxed">
                                {{ __('Unless otherwise indicated, the Site is our proprietary property and all source code, databases, functionality, software, website designs, audio, video, text, photographs, and graphics on the Site (collectively, the "Content") and the trademarks, service marks, and logos contained therein (the "Marks") are owned or controlled by us or licensed to us, and are protected by copyright and trademark laws and various other intellectual property rights and unfair competition laws of the United States, foreign jurisdictions, and international conventions.') }}
                            </p>
                            <p class="mt-4">
                                {{ __('The Content and the Marks are provided on the Site "AS IS" for your information and personal use only. Except as expressly provided in these Terms and Conditions, no part of the Site and no Content or Marks may be copied, reproduced, aggregated, republished, uploaded, posted, publicly displayed, encoded, translated, transmitted, distributed, sold, licensed, or otherwise exploited for any commercial purpose whatsoever, without our express prior written permission.') }}
                            </p>
                        </section>

                        <!-- Section 3 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('3. User Representations') }}</h2>
                            <p class="leading-relaxed">
                                {{ __('By using the Site, you represent and warrant that:') }}
                            </p>
                            <ul class="list-disc pl-8 mt-4 space-y-3 font-medium">
                                <li>{{ __('All registration information you submit will be true, accurate, current, and complete.') }}</li>
                                <li>{{ __('You will maintain the accuracy of such information and promptly update such registration information as necessary.') }}</li>
                                <li>{{ __('You have the legal capacity and you agree to comply with these Terms and Conditions.') }}</li>
                                <li>{{ __('You are not a minor in the jurisdiction in which you reside.') }}</li>
                                <li>{{ __('You will not access the Site through automated or non-human means, whether through a bot, script, or otherwise.') }}</li>
                                <li>{{ __('You will not use the Site for any illegal or unauthorized purpose.') }}</li>
                                <li>{{ __('Your use of the Site will not violate any applicable law or regulation.') }}</li>
                            </ul>
                        </section>

                        <!-- Section 4 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('4. Prohibited Activities') }}</h2>
                            <p class="leading-relaxed">
                                {{ __('You may not access or use the Site for any purpose other than that for which we make the Site available. The Site may not be used in connection with any commercial endeavors except those that are specifically endorsed or approved by us. Prohibited activities include, but are not limited to:') }}
                            </p>
                            <ul class="list-disc pl-8 mt-4 space-y-3">
                                <li>{{ __('Systematically retrieving data or other content from the Site to create or compile, directly or indirectly, a collection, compilation, database, or directory without written permission from us.') }}</li>
                                <li>{{ __('Trick, defraud, or mislead us and other users, especially in any attempt to learn sensitive account information such as user passwords.') }}</li>
                                <li>{{ __('Circumvent, disable, or otherwise interfere with security-related features of the Site.') }}</li>
                                <li>{{ __('Disparage, tarnish, or otherwise harm, in our opinion, us and/or the Site.') }}</li>
                                <li>{{ __('Use any information obtained from the Site in order to harass, abuse, or harm another person.') }}</li>
                                <li>{{ __('Make improper use of our support services or submit false reports of abuse or misconduct.') }}</li>
                                <li>{{ __('Use the Site in a manner inconsistent with any applicable laws or regulations.') }}</li>
                                <li>{{ __('Upload or transmit (or attempt to upload or to transmit) viruses, Trojan horses, or other material, including excessive use of capital letters and spamming.') }}</li>
                            </ul>
                        </section>

                        <!-- Section 5 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('5. User Generated Contributions') }}</h2>
                            <p class="leading-relaxed">
                                {{ __('The Site may invite you to chat, contribute to, or participate in blogs, message boards, online forums, and other functionality, and may provide you with the opportunity to create, submit, post, display, transmit, perform, publish, distribute, or broadcast content and materials to us or on the Site, including but not limited to text, writings, video, audio, photographs, graphics, comments, suggestions, or personal information or other material (collectively, "Contributions").') }}
                            </p>
                        </section>

                        <!-- Section 6 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('6. Subscription and Cancellation') }}</h2>
                            <p class="leading-relaxed">
                                {{ __('All paid subscriptions are billed in advance on a recurring and periodic basis. At the end of each billing cycle, your subscription will automatically renew under the exact same conditions unless you cancel it or StockProNex cancels it.') }}
                            </p>
                            <p class="mt-4">
                                {{ __('You may cancel your subscription renewal either through your online account management page or by contacting our customer support team. You will not receive a refund for the fees you already paid for your current subscription period.') }}
                            </p>
                        </section>

                        <!-- Section 7 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('7. Limitation of Liability') }}</h2>
                            <p class="leading-relaxed font-bold italic text-red-600 dark:text-red-400">
                                {{ __('IN NO EVENT WILL WE OR OUR DIRECTORS, EMPLOYEES, OR AGENTS BE LIABLE TO YOU OR ANY THIRD PARTY FOR ANY DIRECT, INDIRECT, CONSEQUENTIAL, EXEMPLARY, INCIDENTAL, SPECIAL, OR PUNITIVE DAMAGES, INCLUDING LOST PROFIT, LOST REVENUE, LOSS OF DATA, OR OTHER DAMAGES ARISING FROM YOUR USE OF THE SITE, EVEN IF WE HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.') }}
                            </p>
                        </section>

                        <!-- Section 8 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('8. Indemnification') }}</h2>
                            <p class="leading-relaxed">
                                {{ __('You agree to defend, indemnify, and hold us harmless, including our subsidiaries, affiliates, and all of our respective officers, agents, partners, and employees, from and against any loss, damage, liability, claim, or demand, including reasonable attorneys’ fees and expenses, made by any third party due to or arising out of: (1) your Contributions; (2) use of the Site; (3) breach of these Terms and Conditions; (4) any breach of your representations and warranties set forth in these Terms and Conditions; (5) your violation of the rights of a third party, including but not limited to intellectual property rights; or (6) any overt harmful act toward any other user of the Site with whom you connected via the Site.') }}
                            </p>
                        </section>

                        <!-- Section 9 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('9. Modifications and Interruptions') }}</h2>
                            <p class="leading-relaxed">
                                {{ __('We reserve the right to change, modify, or remove the contents of the Site at any time or for any reason at our sole discretion without notice. However, we have no obligation to update any information on our Site. We also reserve the right to modify or discontinue all or part of the Site without notice at any time. We will not be liable to you or any third party for any modification, price change, suspension, or discontinuance of the Site.') }}
                            </p>
                        </section>

                        <!-- Section 10 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('10. Contact Us') }}</h2>
                            <p class="leading-relaxed">
                                {{ __('In order to resolve a complaint regarding the Site or to receive further information regarding use of the Site, please contact us at:') }}
                            </p>
                            <div class="mt-6 p-6 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-blue-100 dark:border-blue-900/40">
                                <p class="font-bold text-gray-900 dark:text-white">StockProNex Support Team</p>
                                <p class="text-blue-600 dark:text-blue-400 font-medium">allipatel33@gmail.com</p>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest">{{ __('Your Stock Management Partner') }}</p>
                            </div>
                        </section>
                    </div>

                    <div class="mt-16 pt-10 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center">
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 font-bold hover:underline">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            {{ __('Back to Settings') }}
                        </a>
                        <p class="text-sm text-gray-400 dark:text-gray-500">© {{ date('Y') }} StockProNex. {{ __('All Rights Reserved.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
