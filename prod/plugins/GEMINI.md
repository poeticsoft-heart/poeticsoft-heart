# Arquitectura de Plugin: Poeticsoft Heart

Este documento describe la arquitectura de plugins

## Estructura de Plugins
El ecosistema Poeticsoft Heart opera bajo una arquitectura de plugin único. Toda la lógica de negocio, servicios, API, bloques y telemetría reside en:

- **`poeticsoft-heart/`**: Plugin principal que centraliza todas las funcionalidades.

## Arquitectura Interna del Plugin `poeticsoft-heart`

La lógica de negocio está organizada bajo el namespace `Poeticsoft\Heart\` en el directorio `classes/`. A continuación se detalla su composición interna:

### Módulos de Clases (`/classes/`)

- **AI (`/classes/AI/`)**:
    - `Gemini.php`: Cliente para el modelo Gemini (via SDK oficial).
    - `Main.php`: Orquestador de servicios de IA.
    - `Provider.php`: Interfaz/Base para proveedores de IA.

- **API (`/classes/API/`)**:
    - `Main.php`: Registro y gestión de endpoints REST.
    - `Endpoint.php`: Clase base para controladores.
    - `Endpoints/`: Controladores para `Content`, `Mail`, `Prompts`, `System`, y `Voice`.
    - `Security/`: Implementación de `RateLimiter.php` para protección de API.

- **Blocks (`/classes/Blocks/`)**:
    - `Main.php`: Lógica de registro dinámico para bloques de Gutenberg.

- **Credentials (`/classes/Credentials/`)**:
    - `Admin.php`: Gestión de ajustes administrativos.
    - `Main.php`: Orquestador de credenciales.
    - `Page.php`: Clase base para páginas de configuración.
    - `Integrations/`: Lógica específica como `SMTP.php`.
    - `Pages/`: Implementación de interfaces para `AI.php`, `Communications.php` e `Instagram.php`.

- **Prompts (`/classes/Prompts/`)**:
    - `Main.php`: Gestor central de prompts.
    - `Markdown.php`: Lógica de procesamiento y transformación de Markdown.
    - `Optimizer.php`: Motor para mejorar y ajustar las interacciones de los prompts.

- **Telemetry (`/classes/Telemetry/`)**:
    - `Main.php`: Sistema de monitoreo y logging de eventos.

## Estándares de Desarrollo
1. **Namespace:** Toda clase debe seguir `Poeticsoft\Heart\[Modulo]`.
2. **Dependencias:** Las librerías de terceros se gestionan mediante Composer en el directorio `vendor/` del propio plugin.
3. **Autoloading:** Tras crear nuevas clases, es obligatorio ejecutar:
   `composer dump-autoload -o --no-interaction`
4. **Desacoplamiento:** Aunque el plugin es centralizado, se debe mantener la separación de responsabilidades mediante inyección de dependencias y el uso de los módulos de clases definidos.

---
*Nota: Este directorio contiene exclusivamente el plugin `poeticsoft-heart`. Cualquier adición futura de plugins independientes debe seguir este estándar de documentación.*
