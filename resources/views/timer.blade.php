<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('assets/logo2425-white.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

    {{-- Sweet Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>


</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">TIMER</h1>
        <div id="countdown" class="text-center text-4xl font-mono text-gray-800 mb-6">
            00:00:00
        </div>
        <form id="timer-form" class="mb-6">
            <div class="flex space-x-4 mb-4">
                <div class="flex-1">
                    <label for="hours" class="block text-sm font-medium text-gray-700">Jam</label>
                    <input type="number" id="hours" name="hours" min="0" value="0"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex-1">
                    <label for="minutes" class="block text-sm font-medium text-gray-700">Menit</label>
                    <input type="number" id="minutes" name="minutes" min="0" max="59" value="0"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex-1">
                    <label for="seconds" class="block text-sm font-medium text-gray-700">Detik</label>
                    <input type="number" id="seconds" name="seconds" min="0" max="59" value="0"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div class="flex space-x-4">
                <button type="button" id="start-button"
                    class="flex-1 bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700 focus:outline-none">
                    Start
                </button>
                <button type="button" id="pause-button"
                    class="flex-1 bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600 focus:outline-none hidden">
                    Pause
                </button>
                <button type="button" id="reset-button"
                    class="flex-1 bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 focus:outline-none hidden">
                    Reset
                </button>
            </div>
        </form>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startButton = document.getElementById('start-button');
            const pauseButton = document.getElementById('pause-button');
            const resetButton = document.getElementById('reset-button');
            const countdownDisplay = document.getElementById('countdown');
            let countdownInterval = null;
            let remainingSeconds = 0;
            let isPaused = false;

            startButton.addEventListener('click', function() {
                clearInterval(countdownInterval);
                // if (countdownInterval && !isPaused) return;

                const hours = parseInt(document.getElementById('hours').value) || 0;
                const minutes = parseInt(document.getElementById('minutes').value) || 0;
                const seconds = parseInt(document.getElementById('seconds').value) || 0;

                //calculate total in seconds
                if (!isPaused) {
                    clearInterval(countdownInterval);
                    remainingSeconds = hours * 3600 + minutes * 60 + seconds;
                    isPaused = false;
                }

                if (remainingSeconds <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: "Empty Fields",
                        text: "Please enter the duration!",
                    })
                    return;
                }

                // Update countdown
                updateCountdownDisplay(remainingSeconds);

                toggleInputFields(true);

                //show pause and reset btns, hide start btn
                startButton.classList.add('hidden');
                pauseButton.classList.remove('hidden');
                resetButton.classList.remove('hidden');

                countdownInterval = setInterval(() => {
                    if (!isPaused) {
                        remainingSeconds--;
                        if (remainingSeconds <= 0) {
                            clearInterval(countdownInterval);
                            updateCountdownDisplay(0);
                            Swal.fire({
                                icon: 'success',
                                title: "Time's up!",
                                text: "The countdown has ended!",
                            })
                            // Reset all btns and input
                            toggleInputFields(false);
                            startButton.classList.remove('hidden');
                            pauseButton.classList.add('hidden');
                            resetButton.classList.add('hidden');
                        } else {
                            updateCountdownDisplay(remainingSeconds);
                        }
                    }
                }, 1000);
            });

            pauseButton.addEventListener('click', function() {
                if (isPaused) {
                    //resume
                    isPaused = false;
                    pauseButton.textContent = 'Pause';
                } else {
                    //pause
                    isPaused = true;
                    pauseButton.textContent = 'Resume';
                }
            });

            resetButton.addEventListener('click', function() {
                clearInterval(countdownInterval);
                countdownInterval = null;
                remainingSeconds = 0;
                isPaused = false;
                updateCountdownDisplay(0);
                pauseButton.textContent = 'Pause';
                //reset btns and input fields
                toggleInputFields(false);
                startButton.classList.remove('hidden');
                pauseButton.classList.add('hidden');
                resetButton.classList.add('hidden');
                document.getElementById('timer-form').reset();
            });

            function updateCountdownDisplay(totalSeconds) {
                const hrs = Math.floor(totalSeconds / 3600);
                const mins = Math.floor((totalSeconds % 3600) / 60);
                const secs = totalSeconds % 60;

                countdownDisplay.textContent =
                    `${pad(hrs)}:${pad(mins)}:${pad(secs)}`;
            }

            function pad(num) {
                return num.toString().padStart(2, '0');
            }

            function toggleInputFields(disable) {
                document.getElementById('hours').disabled = disable;
                document.getElementById('minutes').disabled = disable;
                document.getElementById('seconds').disabled = disable;
            }
        });
    </script>
</body>

</html>
