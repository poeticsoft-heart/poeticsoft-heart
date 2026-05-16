# Arquitectura de Plugins: Poeticsoft Heart

Este documento describe la responsabilidad de cada módulo y el sistema de integración unificado.

## Módulos del Sistema

### 1. `poeticsoft-heart-core` (Dependency Manager)
- **Misión:** Proveer librerías de terceros a todo el ecosistema.
- **Librerías actuales:** `league/commonmark`.
- **Uso:** Otros plugins cargan este autoloader mediante:
  `require_once dirname(__DIR__) . '/poeticsoft-heart-core/vendor/autoload.php';`

### 2. `poeticsoft-heart-api` (REST API Unificada)
- **Namespace:** `psh/v1`.
- **Arquitectura:** Basada en controladores que heredan de `Endpoint_Base`.
- **Endpoints Clave:**
  - `POST /content/convert`: Convierte Markdown a bloques (JSON).
  - `POST /content/create`: Genera una página real en WP a partir de Markdown.

### 3. `poeticsoft-heart-contentarchitect` (Data Engine)
- **Misión:** Transformar datos en estructuras de WordPress.
- **Componentes:** `Block_Converter` (Motor de conversión Markdown -> Bloques Gutenberg).
- **Integración:** Utiliza `DOMDocument` para mapear etiquetas HTML a comentarios de bloques core.

### 4. `poeticsoft-heart-credentials` (Admin & Config)
- **Misión:** Centralizar API Keys y ajustes SMTP.
- **Componentes:** `SMTP_Configurator` (Integración con PHPMailer).

### 5. `poeticsoft-heart-blocksbase` (Gutenberg Registry)
- **Misión:** Escaneo dinámico y registro de bloques compilados.
- **Categoría:** Registra la categoría global `poeticsoft-heartbase`.

### 6. `poeticsoft-heart-telemetry` & `poeticsoft-heart-aicore`
- **Misión:** Logging avanzado e integración con proveedores de IA (OpenAI, Anthropic, Gemini).

## Flujo de Desarrollo Backend
1. **Añadir Clase:** Crear archivo en `classes/` del plugin correspondiente.
2. **Namespace:** Asegurar que coincida con la ruta PSR-4 del `composer.json`.
3. **Autoload:** Ejecutar `composer dump-autoload -o --no-interaction`.
4. **Sincronización:** Si es un plugin nuevo, crear el enlace simbólico en WordPress:
   `ln -s /home/heart/prod/plugins/[nombre-plugin] /var/www/poeticsoft/wp-content/plugins/`
5. **API:** Si la lógica debe ser expuesta, registrar el endpoint en `heart-api/classes/endpoints/`.

---
*Nota: La comunicación entre plugins debe ser desacoplada siempre que sea posible, utilizando el Core como puente de dependencias.*
