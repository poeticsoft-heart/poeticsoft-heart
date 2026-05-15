import './view.scss';

document.addEventListener('DOMContentLoaded', () => {
    const voiceContainers = document.querySelectorAll('.psh-voice-interface-wrapper');

    voiceContainers.forEach(container => {
        const orb = container.querySelector('.psh-orb');
        const statusLabel = container.querySelector('.psh-voice-status');
        const transcriptDisplay = container.querySelector('.psh-voice-transcript');
        
        let recognition = null;
        let isListening = false;

        // Soporte para Web Speech API
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

        if (!SpeechRecognition) {
            statusLabel.textContent = 'Tu navegador no soporta reconocimiento de voz.';
            orb.style.opacity = '0.3';
            return;
        }

        recognition = new SpeechRecognition();
        recognition.continuous = true;
        recognition.interimResults = true;
        recognition.lang = 'es-ES';

        orb.addEventListener('click', () => {
            if (isListening) {
                stopListening();
            } else {
                startListening();
            }
        });

        function startListening() {
            try {
                recognition.start();
                isListening = true;
                container.classList.add('is-listening');
                statusLabel.textContent = 'Escuchando...';
                transcriptDisplay.textContent = '';
            } catch (e) {
                console.error('Error al iniciar el reconocimiento:', e);
            }
        }

        function stopListening() {
            recognition.stop();
            isListening = false;
            container.classList.remove('is-listening');
            container.classList.add('is-processing');
            statusLabel.textContent = 'Procesando...';
            
            // Simulación de "fin de proceso" por ahora
            setTimeout(() => {
                container.classList.remove('is-processing');
                statusLabel.textContent = container.dataset.placeholder;
            }, 3000);
        }

        recognition.onresult = (event) => {
            let interimTranscript = '';
            let finalTranscript = '';

            for (let i = event.resultIndex; i < event.results.length; ++i) {
                if (event.results[i].isFinal) {
                    finalTranscript += event.results[i][0].transcript;
                } else {
                    interimTranscript += event.results[i][0].transcript;
                }
            }

            transcriptDisplay.textContent = finalTranscript || interimTranscript;
            
            // Auto-scroll si el texto crece mucho
            transcriptDisplay.scrollTop = transcriptDisplay.scrollHeight;
        };

        recognition.onerror = (event) => {
            console.error('Speech recognition error:', event.error);
            stopListening();
        };

        recognition.onend = () => {
            if (isListening) {
                recognition.start(); // Mantener vivo si no lo hemos parado nosotros
            }
        };
    });
});
