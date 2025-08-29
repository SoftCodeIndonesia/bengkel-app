<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VSB Activation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-md rounded-lg p-8 w-[400px]">
        <!-- Title -->
        <h2 class="text-2xl font-bold text-center">VSB Activation</h2>
        <p class="text-gray-500 text-center mt-2">
            If you are already a member you can login<br />
            with your email address and password.
        </p>

        <!-- Form -->
        <form class="mt-6 space-y-4">
            <!-- Name -->
            <div>
                <label for="name" class="block text-gray-700 mb-1">Name</label>
                <input type="text" id="name" placeholder="Your name"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700 mb-1">Email address</label>
                <input type="email" id="email" value="afridac10@gmail.com" disabled
                    class="w-full border border-gray-300 bg-gray-100 text-gray-500 rounded-md px-3 py-2 cursor-not-allowed">
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-gray-700 mb-1">Phone Number</label>
                <div
                    class="flex items-center border border-gray-300 rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-blue-500">
                    <input type="tel" id="phoneInput" placeholder="Enter phone number" class="flex-1 outline-none"
                        oninput="toggleSendOtpBtn()" />
                    <!-- Button Send OTP -->
                    <button type="button" id="sendOtpBtn"
                        class="ml-2 px-3 py-1 bg-blue-500 text-white rounded-lg text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        Send OTP
                    </button>
                </div>
                <p id="errorMsgPhone" class="text-red-500 text-sm text-start mt-2">
                    Phone number is already registered. Please use a different number
                </p>
            </div>

            <!-- Device Serial Number -->
            <div>
                <label for="serial" class="block text-gray-700 mb-1">Device Serial Number</label>
                <div class="relative">
                    <input type="text" id="serial"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400 pr-10">
                    <!-- Scanner Icon -->
                    <button type="button" id="scanBtn"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-800">
                        <!-- Heroicons Outline: QrCode -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h5v2H5v3H3V4zm13-1h5a1 1 0 011 1v5h-2V5h-4V3zm6 13v5a1 1 0 01-1 1h-5v-2h4v-4h2zm-13 6H4a1 1 0 01-1-1v-5h2v4h4v2z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Device Name -->
            <div>
                <label for="deviceName" class="block text-gray-700 mb-1">Device Name</label>
                <input type="text" id="deviceName"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400">
            </div>

            <!-- Terms of Service -->
            <div class="flex items-start">
                <input id="terms" type="checkbox" class="mt-1 w-4 h-4 border border-gray-300 rounded">
                <label for="terms" class="ml-2 text-sm text-gray-600">
                    By clicking the button below, I agree to the
                    <a href="#" class="font-semibold text-gray-900 hover:underline">Terms of Service</a>
                </label>
            </div>

            <!-- reCAPTCHA -->
            <div class="g-recaptcha" data-sitekey="your-site-key"></div>

            <!-- Activate Button -->
            <div>
                <button type="submit" id="activateBtn"
                    class="w-full bg-red-500 text-white py-2 rounded-md font-medium transition-colors">
                    Activate Now
                </button>
            </div>
        </form>

        <div id="cameraModal" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center">
            <div class="bg-white rounded-lg p-4 w-[360px] relative">
                <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">✕</button>
                <h3 class="text-lg font-semibold mb-2">Scan Serial Number</h3>
                <video id="camera" autoplay playsinline class="w-full rounded-md border border-gray-300"></video>
            </div>
        </div>

        <div id="otpModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-[400px] p-6 relative">
                <!-- Close button -->
                <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                    ✕
                </button>

                <!-- Modal Content -->
                <h2 class="text-xl font-bold text-center">Verify Your Number</h2>
                <p class="text-gray-500 text-center mt-2">Enter the 6-digit OTP code that we have sent to<br>
                    <span id="phoneNumberText" class="font-medium text-gray-700">0877 8736 0508</span>
                </p>

                <!-- OTP Input -->
                <div id="otpInputs" class="flex justify-center gap-2 mt-6">
                    <input type="text" maxlength="1"
                        class="otp-input w-12 h-12 border rounded-lg text-center text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <input type="text" maxlength="1"
                        class="otp-input w-12 h-12 border rounded-lg text-center text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <input type="text" maxlength="1"
                        class="otp-input w-12 h-12 border rounded-lg text-center text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <input type="text" maxlength="1"
                        class="otp-input w-12 h-12 border rounded-lg text-center text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <input type="text" maxlength="1"
                        class="otp-input w-12 h-12 border rounded-lg text-center text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <input type="text" maxlength="1"
                        class="otp-input w-12 h-12 border rounded-lg text-center text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <!-- Error Message -->
                <p id="errorMsg" class="text-red-500 text-sm text-center mt-2 hidden">
                    Invalid OTP, please try again.
                </p>

                <!-- Verify Button -->
                <button id="verifyBtn"
                    class="w-full mt-6 bg-gray-400 text-white py-2 rounded-lg font-medium disabled:cursor-not-allowed"
                    disabled>
                    Verify
                </button>

                <!-- Resend -->
                <p class="text-center text-gray-500 text-sm mt-3" id="resendWrapper">
                    Didn’t receive the code? <span id="resendText">Resend in <span id="countdown">25</span>s</span>
                </p>
            </div>
        </div>

        <div id="promoModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-lg w-[420px] p-6 relative">
                <!-- Close button -->
                <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                    ✕
                </button>

                <!-- Icon -->
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 flex items-center justify-center rounded-full bg-blue-100">
                        🎁
                    </div>
                </div>

                <!-- Title -->
                <h2 class="text-xl font-bold text-center">Gratis 1 Bulan HBO Khusus untuk Anda!</h2>

                <!-- Description -->
                <p class="text-gray-600 text-center mt-3 text-sm">
                    Silakan periksa Gmail <span class="font-medium">ar******@gmail.com</span> untuk mendapatkan
                    username dan password,
                    lalu gunakan keduanya untuk login di
                    <a href="https://max.firstmedia.com/" target="_blank"
                        class="text-blue-500 underline">https://max.firstmedia.com/</a>.
                </p>

                <!-- Highlight Text -->
                <p class="text-center text-blue-600 font-medium mt-4">
                    Aktivasi sekarang untuk klaim paket gratis Anda!
                </p>

                <!-- Action Button -->
                <button onclick="closeModal()"
                    class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium">
                    Activate Now
                </button>
            </div>
        </div>

        <script>
            let countdownInterval;

            const promoModal = document.getElementById("promoModal");
            const form = document.querySelector("form");
            const activateBtn = document.getElementById("activateBtn");
            const inputs = form.querySelectorAll("input[type=text]");
            const terms = document.getElementById("terms");
            const scanBtn = document.getElementById("scanBtn");

            function toggleSendOtpBtn() {
                const phoneInput = document.getElementById("phoneInput");
                const sendOtpBtn = document.getElementById("sendOtpBtn");

                // Aktifkan tombol hanya jika input tidak kosong
                sendOtpBtn.disabled = phoneInput.value.trim().length === 0;
            }

            activateBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openModalPromo();
            });

            sendOtpBtn.addEventListener('click', openModal);

            function openModal() {
                const phoneInput = document.getElementById("phoneInput").value;
                document.getElementById("phoneNumberText").textContent = phoneInput;
                document.getElementById("otpModal").classList.remove("hidden");
                startCountdown();
                setupOtpInputs();
            }

            function closeModal() {
                document.getElementById("otpModal").classList.add("hidden");
            }

            function openModalPromo() {
                promoModal.classList.remove('hidden');
            }

            function closeModalPromo() {
                promoModal.classList.add('hidden');
            }

            function setupOtpInputs() {
                const inputs = document.querySelectorAll(".otp-input");
                const verifyBtn = document.getElementById("verifyBtn");
                const errorMsg = document.getElementById("errorMsg");

                inputs.forEach((input, index) => {
                    input.value = "";
                    input.addEventListener("input", () => {
                        if (input.value.length === 1 && index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }
                        checkAllFilled();
                    });

                    input.addEventListener("keydown", (e) => {
                        if (e.key === "Backspace" && !input.value && index > 0) {
                            inputs[index - 1].focus();
                        }
                    });
                });

                function checkAllFilled() {
                    const allFilled = Array.from(inputs).every(input => input.value.length === 1);
                    verifyBtn.disabled = !allFilled;
                    verifyBtn.classList.toggle("bg-red-500", allFilled);
                    verifyBtn.classList.toggle("bg-gray-400", !allFilled);
                    errorMsg.classList.add("hidden");
                }

                verifyBtn.onclick = () => {
                    const enteredOtp = Array.from(inputs).map(i => i.value).join("");
                    if (enteredOtp !== "123456") { // simulasi OTP salah
                        errorMsg.classList.remove("hidden");
                    } else {
                        alert("OTP Verified Successfully!");
                        closeModal();
                    }
                };
            }

            // Countdown Resend OTP
            function startCountdown() {
                let timeLeft = 25;
                const countdownEl = document.getElementById("countdown");
                const resendText = document.getElementById("resendText");

                resendText.innerHTML = `Resend in <span id="countdown">${timeLeft}</span>s`;

                countdownInterval = setInterval(() => {
                    timeLeft--;
                    countdownEl.textContent = timeLeft;
                    if (timeLeft <= 0) {
                        clearInterval(countdownInterval);
                        resendText.innerHTML =
                            `<button onclick="restartOtp()" class="text-red-500 font-medium">Resend OTP Now</button>`;
                    }
                }, 1000);
            }

            // Restart OTP
            function restartOtp() {
                startCountdown();
                alert("New OTP sent!");
            }

            // Open camera
            scanBtn.addEventListener("click", async () => {
                cameraModal.classList.remove("hidden");
                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: "environment"
                        }
                    });
                    video.srcObject = stream;
                } catch (err) {
                    alert("Camera access denied or not available.");
                }
            });

            // Close modal and stop camera
            closeModal.addEventListener("click", () => {
                cameraModal.classList.add("hidden");
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
            });

            function validateForm() {
                let filled = true;
                inputs.forEach(input => {
                    if (input.value.trim() === "") filled = false;
                });

                if (filled && terms.checked) {
                    activateBtn.disabled = false;
                    activateBtn.classList.remove("bg-gray-300", "cursor-not-allowed");
                    activateBtn.classList.add("bg-gray-800", "hover:bg-gray-900");
                } else {
                    activateBtn.disabled = true;
                    activateBtn.classList.add("bg-gray-300", "cursor-not-allowed");
                    activateBtn.classList.remove("bg-gray-800", "hover:bg-gray-900");
                }
            }

            inputs.forEach(input => input.addEventListener("input", validateForm));
            terms.addEventListener("change", validateForm);
        </script>

</body>

</html>
