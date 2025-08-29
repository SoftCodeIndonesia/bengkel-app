<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VSB Activation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-md rounded-lg p-8 w-[400px]">
        <!-- Title -->
        <h2 class="text-2xl font-bold text-center">VSB Activation</h2>
        <p class="text-gray-500 text-center mt-2">
            If you are already a member you can login<br />
            with your email address and password.
        </p>

        <!-- Error Alert -->
        <div id="errorAlert"
            class="hidden mt-6 bg-red-100 border border-red-200 text-red-700 text-sm rounded-md p-4 relative">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-9-3a1 1 0 012 0v4a1 1 0 01-2 0V7zm1 8a1 1 0 100-2 1 1 0 000 2z"
                        clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="font-semibold">Account Already Registered</p>
                    <p class="text-xs mt-1">Your Google account is already registered with VSB. Please use a different
                        Google account for activation.</p>
                </div>
            </div>
            <button onclick="hideError()" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                ✕
            </button>
        </div>

        <!-- Google button -->
        <div class="mt-6">
            <button onclick="showError()"
                class="w-full border border-gray-300 rounded-md py-2 flex items-center justify-center gap-2 hover:bg-gray-50">
                <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" class="w-5 h-5">
                <span class="text-gray-700 font-medium">Sign Up with Google</span>
            </button>
        </div>

        <!-- Divider -->
        <div class="flex items-center my-6">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="px-3 text-gray-500 text-sm">or</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <!-- Phone number -->
        <div>
            <label for="phone" class="block text-gray-700 mb-1">Phone Number</label>
            <input type="text" id="phone" placeholder="Enter your phone number"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400">
        </div>

        <!-- Send OTP button -->
        <div class="mt-6">
            <button id="sendOtpBtn" disabled
                class="w-full bg-gray-300 text-white py-2 rounded-md font-medium cursor-not-allowed transition-colors">
                Send OTP
            </button>
        </div>
    </div>

    <script>
        const phoneInput = document.getElementById("phone");
        const sendBtn = document.getElementById("sendOtpBtn");
        const errorAlert = document.getElementById("errorAlert");

        phoneInput.addEventListener("input", () => {
            if (phoneInput.value.trim() !== "") {
                sendBtn.disabled = false;
                sendBtn.classList.remove("bg-gray-300", "cursor-not-allowed");
                sendBtn.classList.add("bg-gray-800", "hover:bg-gray-900");
            } else {
                sendBtn.disabled = true;
                sendBtn.classList.add("bg-gray-300", "cursor-not-allowed");
                sendBtn.classList.remove("bg-gray-800", "hover:bg-gray-900");
            }
        });


        function showError() {
            errorAlert.classList.remove("hidden");
        }

        function hideError() {
            errorAlert.classList.add("hidden");
        }
        sendBtn.addEventListener('click', showError);
    </script>

</body>

</html>
