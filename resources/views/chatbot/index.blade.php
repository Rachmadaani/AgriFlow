<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Konsultan Pertanian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4 text-center">
                        <div class="text-6xl mb-2">🌾🤖</div>
                        <h3 class="text-lg font-semibold">Tanya Apapun tentang Pertanian</h3>
                        <p class="text-gray-600">Cabai, Timun, Pupuk, Hama, Harga Jual, dll</p>
                    </div>

                    <!-- Chat Messages Area -->
                    <div id="chatMessages" class="h-96 overflow-y-auto border rounded-lg p-4 mb-4 bg-gray-50">
                        <div class="text-center text-gray-500 mb-4">
                            Selamat datang! Silakan tanyakan masalah pertanian Anda.
                        </div>
                    </div>

                    <!-- Input Area -->
                    <div class="flex gap-2">
                        <input type="text"
                            id="questionInput"
                            placeholder="Contoh: Bagaimana cara mengatasi hama pada cabai?"
                            class="flex-1 shadow border rounded-lg py-2 px-3 focus:outline-none focus:shadow-outline">
                        <button id="sendBtn" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg">
                            Kirim
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Powered by DeepSeek AI (Free)</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const chatMessages = document.getElementById('chatMessages');
        const questionInput = document.getElementById('questionInput');
        const sendBtn = document.getElementById('sendBtn');

        function addMessage(text, isUser = true) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `mb-3 flex ${isUser ? 'justify-end' : 'justify-start'}`;

            const bubble = document.createElement('div');
            bubble.className = `max-w-[70%] rounded-lg px-4 py-2 ${isUser ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-800'}`;
            bubble.innerHTML = isUser ? text : text.replace(/\n/g, '<br>');

            messageDiv.appendChild(bubble);
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function addLoadingIndicator() {
            const loadingDiv = document.createElement('div');
            loadingDiv.id = 'loadingIndicator';
            loadingDiv.className = 'flex justify-start mb-3';
            loadingDiv.innerHTML = '<div class="bg-gray-200 text-gray-800 rounded-lg px-4 py-2">🤔 Memikirkan...</div>';
            chatMessages.appendChild(loadingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function removeLoadingIndicator() {
            const loading = document.getElementById('loadingIndicator');
            if (loading) loading.remove();
        }

        async function sendQuestion() {
            const question = questionInput.value.trim();
            if (!question) return;

            // Disable input
            questionInput.disabled = true;
            sendBtn.disabled = true;

            // Tampilkan pertanyaan user
            addMessage(question, true);
            questionInput.value = '';

            // Tampilkan loading
            addLoadingIndicator();

            try {
                const response = await fetch('{{ route("chatbot.ask") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        question: question
                    })
                });

                const data = await response.json();
                removeLoadingIndicator();
                addMessage(data.reply, false);
            } catch (error) {
                removeLoadingIndicator();
                addMessage('Maaf, terjadi kesalahan. Silakan coba lagi.', false);
            }

            // Enable input
            questionInput.disabled = false;
            sendBtn.disabled = false;
            questionInput.focus();
        }

        sendBtn.addEventListener('click', sendQuestion);
        questionInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendQuestion();
        });
    </script>
</x-app-layout>