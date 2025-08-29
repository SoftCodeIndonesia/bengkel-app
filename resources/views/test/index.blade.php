<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activation Success</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex flex-col items-center p-6">

    <!-- Success Message -->
    <div class="text-center mt-10">
        <h1 class="text-xl font-semibold text-gray-800">
            Your registration was successful 🎉
        </h1>
        <a href="#hbo" class="text-blue-600 hover:underline text-sm mt-2 inline-block">
            Activate your HBO Max account now.
        </a>
    </div>

    <!-- Content Box -->
    <div class="bg-white rounded-xl shadow-sm w-full max-w-3xl mt-8 border">
        <!-- Tabs -->
        <div class="flex border-b">
            <button id="tab-onestream" onclick="showTab('onestream')"
                class="flex-1 py-3 text-sm font-medium text-red-600 border-b-2 border-red-500">
                Onestream
            </button>
            <button id="tab-hbo" onclick="showTab('hbo')"
                class="flex-1 py-3 text-sm font-medium text-gray-500 hover:text-gray-700">
                HBO Max 🌟
            </button>
        </div>

        <!-- Onestream Content -->
        <div id="content-onestream" class="p-6">
            <h2 class="text-xl font-bold text-gray-800">Access OneStream & Enjoy Movies</h2>
            <p class="text-gray-500 mt-2 text-sm">
                Open the OneStream app and watch thousands of premium movies
                and series in HD quality. Easy streaming, unlimited entertainment!
            </p>
            <div class="mt-6 flex justify-center">
                <img src="your-onestream-image.png" alt="Onestream TV" class="rounded-lg shadow-md max-h-72">
            </div>
        </div>

        <!-- HBO Content -->
        <div id="content-hbo" class="p-6 hidden">
            <h2 class="text-xl font-bold text-gray-800">Access HBO Max & Enjoy Premium Content</h2>
            <p class="text-gray-500 mt-2 text-sm">
                Activate your HBO Max account to explore exclusive HBO Originals, movies,
                and series in the highest quality available.
            </p>
            <div class="mt-6 flex justify-center">
                <img src="your-hbo-image.png" alt="HBO Max" class="rounded-lg shadow-md max-h-72">
            </div>
        </div>
    </div>

    <script>
        function showTab(tab) {
            // Reset tabs
            document.getElementById("tab-onestream").classList.remove("text-red-600", "border-b-2", "border-red-500");
            document.getElementById("tab-hbo").classList.remove("text-red-600", "border-b-2", "border-red-500");
            document.getElementById("tab-onestream").classList.add("text-gray-500");
            document.getElementById("tab-hbo").classList.add("text-gray-500");

            // Hide content
            document.getElementById("content-onestream").classList.add("hidden");
            document.getElementById("content-hbo").classList.add("hidden");

            if (tab === "onestream") {
                document.getElementById("tab-onestream").classList.add("text-red-600", "border-b-2", "border-red-500");
                document.getElementById("tab-onestream").classList.remove("text-gray-500");
                document.getElementById("content-onestream").classList.remove("hidden");
            } else {
                document.getElementById("tab-hbo").classList.add("text-red-600", "border-b-2", "border-red-500");
                document.getElementById("tab-hbo").classList.remove("text-gray-500");
                document.getElementById("content-hbo").classList.remove("hidden");
            }
        }
    </script>

</body>

</html>
