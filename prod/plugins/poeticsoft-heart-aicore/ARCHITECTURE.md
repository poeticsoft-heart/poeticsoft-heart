# Arquitectura de AICore: Orquestador de Inteligencia Artificial

Este documento describe la estructura y el funcionamiento del plugin `poeticsoft-heart-aicore`, encargado de gestionar la comunicación con modelos de IA, la gestión de contextos y la optimización mediante caché.

## 1. Estructura de Clases y Abstracción

El sistema utiliza un patrón de **Estrategia (Strategy)** para permitir el soporte de múltiples proveedores de IA sin modificar el código base.

### AI_Provider_Interface
Define el contrato mínimo que cualquier modelo (Gemini, OpenAI, etc.) debe cumplir:
- `set_config( array $config )`: Recibe API Keys, modelos y TTLs.
- `stream_chat( string $prompt, array $context_data, callable $callback )`: Ejecuta la petición por streaming.

### Gemini_Provider
Implementación específica para Google Gemini utilizando el SDK oficial.
- **Ubicación:** `classes/providers/class-gemini-provider.php`.
- **Librería:** Ubicada en `poeticsoft-heart-core` para centralizar dependencias.

---

## 2. Gestión de Contexto Dinámico (The Store)

El sistema alimenta al agente con información externa cargada dinámicamente desde el sistema de archivos.

### Estructura de Archivos
- **Ruta:** `prod/plugins/poeticsoft-heart-aicore/store/`.
- **Convención:** Los nombres de los archivos `.md` deben ir en **MAYÚSCULAS**.

### Organización por Secciones:
1.  **`store/prompt/`**: Contiene las instrucciones maestras del sistema.
    - Archivo principal: `SYSTEM-INSTRUCTIONS.md`.
2.  **`store/context/`**: Contiene piezas de conocimiento (Context Parts).
    - Cualquier archivo `.md` en esta carpeta se carga automáticamente como contexto adicional.

---

## 3. Optimización: Context Caching

Para reducir la latencia y el coste de tokens, AICore implementa **Context Caching** nativo de Gemini (vía SDK).

### Cómo funciona:
- Si el ajuste `psh_ai_cache_ttl` es mayor a 0, el sistema no envía las instrucciones y el contexto en cada petición.
- En su lugar, crea un objeto `CachedContent` en los servidores de Google.
- Las peticiones posteriores solo envían el nuevo `prompt`, haciendo referencia al ID del contexto ya cacheado.
- **TTL:** Definido en segundos desde el panel de administración de Credenciales.

---

## 4. Orquestación (AI_Manager)

La clase `AI_Manager` actúa como el punto de entrada único:
- **Carga de Credenciales:** Recupera automáticamente las API Keys y modelos configurados en el plugin de Credenciales.
- **Gestión de Store:** Instancia el `Store_Manager` para mapear los archivos de contexto.
- **Singleton Local:** Se inicializa en el hook `init` y se expone mediante la global `$psh_ai_manager`.

---

## 5. Integración con API REST

Los endpoints de voz consumen AICore de la siguiente manera:
1.  Piden el proveedor deseado al `AI_Manager`.
2.  Solicitan al `Store` las instrucciones y partes del contexto.
3.  Llaman a `stream_chat`, pasando un callback que vuelca los chunks directamente al buffer de salida del servidor (`text/event-stream`).

---

## 6. Mantenimiento
- Para añadir un nuevo modelo: Crear clase en `classes/providers/` e implementar la interfaz.
- Para cambiar la personalidad del agente: Editar `store/prompt/SYSTEM-INSTRUCTIONS.md`.
- Para ampliar el conocimiento: Añadir archivos `.md` en mayúsculas a `store/context/`.
