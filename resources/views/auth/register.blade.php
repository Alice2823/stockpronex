<x-guest-layout>

<div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white dark:bg-gray-900 shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-800 transition-colors duration-300">

    <style>
        .dropdown-menu {
            transform: translateY(10px);
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .dropdown-menu.show {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }
        .dropdown-item {
            transition: all 0.2s ease;
        }
        .dropdown-item:hover {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            padding-left: 1.25rem;
        }
        .dark .dropdown-item:hover {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
        }
    </style>

    <div class="flex flex-col items-center mb-8 border-b border-gray-100 dark:border-gray-800 pb-4">
        <h2 class="text-3xl font-black text-gray-900 dark:text-white">Create Account</h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Join the StockProNex community</p>
    </div>

    <div id="errorBox" class="mb-4 text-red-600 font-bold hidden"></div>
    <div id="successBox" class="mb-4 text-green-600 font-bold hidden"></div>

    <form onsubmit="return false;">

        @csrf

        <div>
            <x-label value="First Name"/>
            <x-input id="first_name" class="block mt-1 w-full"/>
        </div>

        <div class="mt-4">
            <x-label value="Last Name"/>
            <x-input id="last_name" class="block mt-1 w-full"/>
        </div>

        <div class="mt-4">
            <x-label value="Email"/>
            <div class="flex gap-2">
                <x-input id="email" class="block mt-1 w-full"/>
                <button type="button" onclick="sendOtp()"
                    class="bg-blue-500 text-white px-4 rounded">
                    Send OTP
                </button>
            </div>
        </div>

        <div class="mt-4">
            <x-label value="OTP"/>
            <x-input id="otp" class="block mt-1 w-full"/>
        </div>

        <div class="mt-4 relative" id="business_dropdown_container">
            <x-label value="Business Type (Module)"/>
            
            <!-- Custom Dropdown Trigger -->
            <div id="dropdown_trigger" class="mt-1 w-full flex justify-between items-center px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer shadow-sm select-none hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-300">
                <span id="selected_text" class="text-gray-700 dark:text-gray-300 font-medium">General Inventory (Default)</span>
                <svg class="h-5 w-5 text-gray-400 transition-transform duration-300" id="dropdown_arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            <!-- Custom Dropdown Menu -->
            <div id="dropdown_menu" class="dropdown-menu absolute z-50 mt-2 w-full bg-white dark:bg-gray-800 backdrop-blur-xl border border-gray-100 dark:border-gray-700 rounded-2xl shadow-2xl overflow-hidden max-h-60 overflow-y-auto">
                <div class="p-1">
                    <div class="dropdown-item px-4 py-2 text-sm cursor-pointer rounded-lg text-blue-600 dark:text-blue-400 font-bold bg-blue-50 dark:bg-blue-900/30" data-value="General Inventory">General Inventory (Default)</div>
                    <div class="dropdown-item px-4 py-2 text-sm cursor-pointer rounded-lg text-gray-700 dark:text-gray-300" data-value="Gold / Jewellery">Gold & Jewellery Business</div>
                    <div class="dropdown-item px-4 py-2 text-sm cursor-pointer rounded-lg text-gray-700 dark:text-gray-300" data-value="Electronics">Electronics Store</div>
                    <div class="dropdown-item px-4 py-2 text-sm cursor-pointer rounded-lg text-gray-700 dark:text-gray-300" data-value="Grocery">Grocery / Supermarket</div>
                    <div class="dropdown-item px-4 py-2 text-sm cursor-pointer rounded-lg text-gray-700 dark:text-gray-300" data-value="Clothing">Clothing / Fashion Store</div>
                    <div class="dropdown-item px-4 py-2 text-sm cursor-pointer rounded-lg text-gray-700 dark:text-gray-300" data-value="Medical Store">Medical / Pharmacy Store</div>
                    <div class="dropdown-item px-4 py-2 text-sm cursor-pointer rounded-lg text-gray-700 dark:text-gray-300" data-value="Hardware">Hardware / Construction Materials</div>
                    <div class="dropdown-item px-4 py-2 text-sm cursor-pointer rounded-lg text-gray-700 dark:text-gray-300" data-value="Mobile Shop">Mobile Phone Shop</div>
                    <div class="dropdown-item px-4 py-2 text-sm cursor-pointer rounded-lg text-gray-700 dark:text-gray-300" data-value="Automobile parts">Automobile Parts Store</div>
                    <div class="dropdown-item px-4 py-2 text-sm cursor-pointer rounded-lg text-gray-700 dark:text-gray-300" data-value="Furniture">Furniture Store</div>
                    <div class="dropdown-item px-4 py-2 text-sm cursor-pointer rounded-lg text-gray-700 dark:text-gray-300" data-value="Cosmetic">Cosmetic & Beauty Store</div>
                    <div class="dropdown-item px-4 py-2 text-sm cursor-pointer rounded-lg text-gray-700 dark:text-gray-300" data-value="Book Store">Book Store / Stationery</div>
                    <div class="dropdown-item px-4 py-2 text-sm cursor-pointer rounded-lg text-gray-700 dark:text-gray-300" data-value="Restaurant">Restaurant / Food Inventory</div>
                    <div class="dropdown-item px-4 py-2 text-sm cursor-pointer rounded-lg text-gray-700 dark:text-gray-300" data-value="Agricultural">Agricultural Products</div>
                    <div class="dropdown-item px-4 py-2 text-sm cursor-pointer rounded-lg text-gray-700 dark:text-gray-300" data-value="Wholesale">Wholesale Distributor</div>
                </div>
            </div>

            <input type="hidden" id="business_type" value="General Inventory">
            <p class="text-xs text-gray-500 mt-2 italic">This will customize your dashboard and product fields.</p>
        </div>

        <div class="mt-4">
            <x-label value="Password"/>
            <x-password-input id="password" class="block mt-1 w-full"/>
        </div>

        <div class="mt-4">
            <x-label value="Confirm Password"/>
            <x-password-input id="password_confirmation" class="block mt-1 w-full"/>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-8">

            <a href="{{ route('login') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">
                Already registered?
            </a>

            <button type="button" onclick="registerUser()"
                class="w-full sm:w-auto bg-gray-900 dark:bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-black dark:hover:bg-blue-700 transform active:scale-[0.98] transition-all duration-200 shadow-lg shadow-gray-200 dark:shadow-none">
                Verify OTP & Register
            </button>

        </div>

    </form>

</div>


<script>

function showError(msg){
    let box = document.getElementById('errorBox');
    box.innerText = msg;
    box.classList.remove('hidden');
}

function showSuccess(msg){
    let box = document.getElementById('successBox');
    box.innerText = msg;
    box.classList.remove('hidden');
}


function sendOtp(){

    let email = document.getElementById('email').value;

    fetch(window.location.origin + "/register", {

        method: "POST",

        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },

        body: JSON.stringify({
            email: email
        })

    })

    .then(res => res.json())

    .then(data => {

        if(data.success){
            showSuccess(data.message);
        }else{
            showError(data.message);
        }

    })

    .catch(err => {
        showError("OTP send failed");
        console.log(err);
    });

}



function registerUser(){

    fetch(window.location.origin + "/register", {

        method: "POST",

        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },

        body: JSON.stringify({

            first_name: document.getElementById('first_name').value,
            last_name: document.getElementById('last_name').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            otp: document.getElementById('otp').value,
            business_type: document.getElementById('business_type').value

        })

    })

    .then(res => res.json())

    .then(data => {

        if(data.success){

            window.location = data.redirect;

        }else{

            showError(data.message);

        }

    })

    .catch(err => {

        showError("Registration failed");
        console.log(err);

    });

}


// Custom Dropdown Logic
document.addEventListener('DOMContentLoaded', () => {
    const trigger = document.getElementById('dropdown_trigger');
    const menu = document.getElementById('dropdown_menu');
    const arrow = document.getElementById('dropdown_arrow');
    const hiddenInput = document.getElementById('business_type');
    const selectedText = document.getElementById('selected_text');
    const items = document.querySelectorAll('.dropdown-item');

    trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = menu.classList.contains('show');
        
        // Close other dropdowns if any (not applicable here but good practice)
        menu.classList.toggle('show');
        arrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    });

    items.forEach(item => {
        item.addEventListener('click', (e) => {
            const value = item.getAttribute('data-value');
            const text = item.textContent;

            // Update visible text and hidden input
            selectedText.textContent = text;
            hiddenInput.value = value;

            // Update styling
            items.forEach(i => {
                i.classList.remove('bg-blue-50', 'text-blue-600', 'font-bold', 'dark:bg-blue-900/30', 'dark:text-blue-400');
                i.classList.add('text-gray-700', 'dark:text-gray-300');
            });
            item.classList.remove('text-gray-700', 'dark:text-gray-300');
            item.classList.add('bg-blue-50', 'text-blue-600', 'font-bold', 'dark:bg-blue-900/30', 'dark:text-blue-400');

            // Close menu
            menu.classList.remove('show');
            arrow.style.transform = 'rotate(0deg)';
        });
    });

    // Close on click outside
    document.addEventListener('click', () => {
        if (menu.classList.contains('show')) {
            menu.classList.remove('show');
            arrow.style.transform = 'rotate(0deg)';
        }
    });
});
</script>

</x-guest-layout>