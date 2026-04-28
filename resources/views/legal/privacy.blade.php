<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-3xl text-gray-800 dark:text-white leading-tight">
            {{ __('Privacy Policy') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 shadow-2xl rounded-3xl border border-gray-100 dark:border-gray-800 overflow-hidden">
                <div class="p-8 md:p-16 prose dark:prose-invert max-w-none">
                    <h1 class="text-4xl font-black text-gray-900 dark:text-white mb-6 uppercase tracking-tight">{{ __('Privacy Policy') }}</h1>
                    <p class="text-gray-500 dark:text-gray-400 mb-10 text-lg font-medium italic border-l-4 border-green-500 pl-4">
                        {{ __('Last Updated: April 28, 2026. Your privacy is our top priority.') }}
                    </p>

                    <div class="space-y-12 text-gray-700 dark:text-gray-300">
                        <!-- Section 1 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('1. Introduction') }}</h2>
                            <p class="leading-relaxed">
                                {{ __('StockProNex ("we," "us," or "our") is committed to protecting your personal information and your right to privacy. If you have any questions or concerns about our policy, or our practices with regards to your personal information, please contact us at allipatel33@gmail.com.') }}
                            </p>
                            <p class="mt-4">
                                {{ __('When you visit our website and use our services, you trust us with your personal information. We take your privacy very seriously. In this privacy notice, we describe our privacy policy. We seek to explain to you in the clearest way possible what information we collect, how we use it, and what rights you have in relation to it.') }}
                            </p>
                        </section>

                        <!-- Section 2 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('2. What Information Do We Collect?') }}</h2>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-4 italic underline">{{ __('Personal Information You Disclose to Us') }}</h3>
                            <p class="mt-2 leading-relaxed">
                                {{ __('We collect personal information that you voluntarily provide to us when registering at the Services, expressing an interest in obtaining information about us or our products and services, when participating in activities on the Services or otherwise contacting us.') }}
                            </p>
                            <ul class="list-disc pl-8 mt-4 space-y-3">
                                <li>{{ __('Name and Contact Data: We collect your first and last name, email address, postal address, phone number, and other similar contact data.') }}</li>
                                <li>{{ __('Credentials: We collect passwords, password hints, and similar security information used for authentication and account access.') }}</li>
                                <li>{{ __('Business Information: We collect business names, tax identification numbers (GST/VAT), business addresses, and industry types to provide tailored stock management features.') }}</li>
                                <li>{{ __('Payment Data: We collect data necessary to process your payment if you make purchases, such as your payment instrument number (such as a credit card number), and the security code associated with your payment instrument. All payment data is stored by our payment processors (Razorpay/PayPal).') }}</li>
                            </ul>
                        </section>

                        <!-- Section 3 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('3. How Do We Use Your Information?') }}</h2>
                            <p class="leading-relaxed">
                                {{ __('We use personal information collected via our Services for a variety of business purposes described below. We process your personal information for these purposes in reliance on our legitimate business interests, in order to enter into or perform a contract with you, with your consent, and/or for compliance with our legal obligations.') }}
                            </p>
                            <ul class="list-disc pl-8 mt-4 space-y-3">
                                <li>{{ __('To facilitate account creation and logon process.') }}</li>
                                <li>{{ __('To send you marketing and promotional communications.') }}</li>
                                <li>{{ __('To send administrative information to you (e.g., changes to our terms, conditions, and policies).') }}</li>
                                <li>{{ __('To fulfill and manage your orders and subscriptions.') }}</li>
                                <li>{{ __('To generate business reports and provide analytics on your inventory performance.') }}</li>
                                <li>{{ __('To enable user-to-user communications (e.g., sharing reports with your Chartered Accountant).') }}</li>
                                <li>{{ __('To enforce our terms, conditions, and policies.') }}</li>
                                <li>{{ __('To respond to legal requests and prevent harm.') }}</li>
                            </ul>
                        </section>

                        <!-- Section 4 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('4. Will Your Information Be Shared With Anyone?') }}</h2>
                            <p class="leading-relaxed font-bold">
                                {{ __('We only share information with your consent, to comply with laws, to provide you with services, to protect your rights, or to fulfill business obligations.') }}
                            </p>
                            <p class="mt-4">
                                {{ __('We may process or share data based on the following legal basis:') }}
                            </p>
                            <ul class="list-disc pl-8 mt-4 space-y-3 italic">
                                <li><strong>{{ __('Consent:') }}</strong> {{ __('We may process your data if you have given us specific consent to use your personal information for a specific purpose.') }}</li>
                                <li><strong>{{ __('Legitimate Interests:') }}</strong> {{ __('We may process your data when it is reasonably necessary to achieve our legitimate business interests.') }}</li>
                                <li><strong>{{ __('Performance of a Contract:') }}</strong> {{ __('Where we have entered into a contract with you, we may process your personal information to fulfill the terms of our contract.') }}</li>
                                <li><strong>{{ __('Legal Obligations:') }}</strong> {{ __('We may disclose your information where we are legally required to do so in order to comply with applicable law, governmental requests, a judicial proceeding, court order, or legal process.') }}</li>
                            </ul>
                        </section>

                        <!-- Section 5 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('5. Do We Use Cookies and Other Tracking Technologies?') }}</h2>
                            <p class="leading-relaxed">
                                {{ __('We may use cookies and similar tracking technologies (like web beacons and pixels) to access or store information. Specific information about how we use such technologies and how you can refuse certain cookies is set out in our Cookie Policy.') }}
                            </p>
                        </section>

                        <!-- Section 6 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('6. How Long Do We Keep Your Information?') }}</h2>
                            <p class="leading-relaxed">
                                {{ __('We will only keep your personal information for as long as it is necessary for the purposes set out in this privacy notice, unless a longer retention period is required or permitted by law (such as tax, accounting or other legal requirements). No purpose in this notice will require us keeping your personal information for longer than the period of time in which users have an account with us.') }}
                            </p>
                        </section>

                        <!-- Section 7 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('7. How Do We Keep Your Information Safe?') }}</h2>
                            <p class="leading-relaxed">
                                {{ __('We have implemented appropriate technical and organizational security measures designed to protect the security of any personal information we process. However, please also remember that we cannot guarantee that the internet itself is 100% secure. Although we will do our best to protect your personal information, transmission of personal information to and from our Services is at your own risk. You should only access the services within a secure environment.') }}
                            </p>
                        </section>

                        <!-- Section 8 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('8. What Are Your Privacy Rights?') }}</h2>
                            <p class="leading-relaxed">
                                {{ __('In some regions (like the European Economic Area), you have certain rights under applicable data protection laws. These may include the right (i) to request access and obtain a copy of your personal information, (ii) to request rectification or erasure; (iii) to restrict the processing of your personal information; and (iv) if applicable, to data portability. In certain circumstances, you may also have the right to object to the processing of your personal information.') }}
                            </p>
                        </section>

                        <!-- Section 9 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('9. Controls for Do-Not-Track Features') }}</h2>
                            <p class="leading-relaxed">
                                {{ __('Most web browsers and some mobile operating systems and mobile applications include a Do-Not-Track (“DNT”) feature or setting you can activate to signal your privacy preference not to have data about your online browsing activities monitored and collected. No uniform technology standard for recognizing and implementing DNT signals has been finalized.') }}
                            </p>
                        </section>

                        <!-- Section 10 -->
                        <section>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider border-b pb-2 border-gray-100 dark:border-gray-800 mb-4">{{ __('10. Do We Make Updates to This Notice?') }}</h2>
                            <p class="leading-relaxed">
                                {{ __('Yes, we will update this notice as necessary to stay compliant with relevant laws. The updated version will be indicated by an updated “Revised” date and the updated version will be effective as soon as it is accessible. If we make material changes to this privacy notice, we may notify you either by prominently posting a notice of such changes or by directly sending you a notification.') }}
                            </p>
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
