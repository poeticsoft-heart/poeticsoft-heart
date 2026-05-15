# Arquitectura de Voz en Tiempo Real (SSE)

Este documento describe el funcionamiento del circuito de comunicación de baja latencia entre los bloques `voicelistener` y `voicespeaker`.

## 1. Diagrama de Flujo de Datos

1.  **Captura:** `VoiceListener` (Browser) -> Web Speech API (Recognition) -> Texto.
2.  **Evento:** `VoiceListener` emite un `CustomEvent('psh-voice-input')` al nivel de `document`.
3.  **Transporte:** `VoiceSpeaker` captura el evento y realiza una petición `POST` (Fetch) a la REST API.
4.  **Streaming:** El servidor PHP devuelve un flujo `text/event-stream` (SSE).
5.  **Consumo:** `VoiceSpeaker` lee los chunks binarios, los decodifica y los muestra en tiempo real.
6.  **Salida:** `VoiceSpeaker` trocea el texto por frases y las envía progresivamente a la Web Speech API (Synthesis).

---

## 2. Comunicación Frontend (Pub/Sub)

Para desacoplar los bloques, se utiliza un sistema de eventos nativo de JavaScript.

### Evento: `psh-voice-input`
- **Origen:** `voicelistener/view.js`
- **Destino:** `voicespeaker/view.js`
- **Payload (`detail`):**
  - `text`: El string de texto reconocido.
  - `source`: ID del bloque origen.

---

## 3. Protocolo de Streaming (Backend)

El endpoint `/wp-json/psh/v1/voice/stream` implementa un protocolo SSE simplificado.

- **Cabeceras Críticas:**
  - `Content-Type: text/event-stream`
  - `X-Accel-Buffering: no` (Vital para que Nginx no cachee el stream).
- **Formato de Mensaje:**
  - Cada fragmento debe empezar por `data: ` y terminar con `\n\n`.
  - El mensaje final de control es `data: [DONE]\n\n`.

---

## 4. Estrategia de Latencia Cero (VoiceSpeaker)

El secreto de la fluidez reside en la **Cola de Síntesis Progresiva**:

- **Buffer de Texto:** El bloque acumula palabras en un string temporal.
- **Detección de Frases:** Se buscan delimitadores `[.!?\n]`.
- **Speech Queue:** Cuando se detecta una frase, se extrae del buffer y se añade a una cola (`speechQueue`).
- **Reproducción Paralela:** La síntesis de voz empieza a leer la frase 1 mientras el streaming sigue descargando la frase 2.

---

## 5. Limitaciones Conocidas

- **Navegadores:** El `VoiceListener` (Reconocimiento) **no es compatible con Firefox**. Requiere Chrome, Edge o Safari.
- **PHP:** Requiere que el servidor tenga habilitado `flush()` y no tenga capas de proxy (como Cloudflare) que bloqueen el streaming de larga duración sin configuración específica.
- **Sesiones:** Al ser una petición REST, WordPress se inicializa en cada ciclo. Para producciones de altísima carga, convendría optimizar la carga de plugins pesados durante estas peticiones.
