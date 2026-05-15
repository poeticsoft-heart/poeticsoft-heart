# Poeticsoft Heart Project

Ecosistema de WordPress compuesto por múltiples aplicaciones, bloques de Gutenberg y una arquitectura modular de plugins de nivel empresarial.

## Estructura de Directorios

```text
/home/heart
├── dev/          <-- [Fuentes Frontend & Compilación] Ver dev/GEMINI.md
└── prod/
    ├── plugins/  <-- [Arquitectura Modular & API] Ver prod/plugins/GEMINI.md
    └── themes/   <-- [Diseño Editorial & FSE] Ver prod/themes/poeticsoft-heart-theme/GEMINI.md
```

## Estándares Globales del Ecosistema

### 1. Gestión de Dependencias (Composer)
- **Centralización:** Las librerías de terceros (ej. CommonMark, Guzzle) se gestionan ÚNICAMENTE en el plugin `poeticsoft-heart-core`.
- **Autoloading:**
    - Cada plugin tiene su propio `composer.json` para sus clases internas (`Poeticsoft\Heart\[Modulo]`).
    - Es obligatorio ejecutar `composer dump-autoload -o --no-interaction` tras modificar clases.
    - El entorno está configurado para permitir `COMPOSER_ALLOW_SUPERUSER=1`.

### 2. Estándares de Código PHP
- **Estilo:** WordPress PHP Coding Standards (sintaxis corta de arrays `[]` obligatoria).
- **Namespaces:** `Poeticsoft\Heart\[NombreModulo]`.
- **Modularidad:** La lógica de negocio debe residir en `prod/plugins/`, el tema es estrictamente presentacional.

### 3. Naming y Organización
- Prefijo PHP: `psh_` o `PSH_`.
- Slugs Bloques: `poeticsoft-heart/[nombrebloque]` (IMPORTANTE: El nombre del bloque **no debe contener guiones** para evitar problemas en las rutas de compilación. Ej: `blockbase`, `voiceinterface`).
- Categoría Bloques: `poeticsoft-heart-base` ("Poeticsoft Heart Base").

### 4. Infraestructura
- **Ruta WordPress:** `/var/www/poeticsoft`.
- **Sincronización:** Los directorios en `prod/` están enlazados simbólicamente en `wp-content/`.
- **Requisitos:** El sistema cuenta con `php-mbstring`, `php-curl`, `unzip`, `zip` y `git` instalados para el correcto funcionamiento de Composer.

### 5. Reglas de Interacción (AI Agent)
- **Control de Bucles y Tokens:** Si una tarea requiere más de dos intentos fallidos para ser resuelta (bucles de compilación, errores de configuración, etc.), el agente debe detenerse inmediatamente, explicar el problema y esperar instrucciones del usuario. Esto es estrictamente para evitar el gasto innecesario de tokens y prevenir bucles incontrolados.
- **Compilación Segura (Modo Watch):** Antes de ejecutar cualquier comando de compilación (`webpack`), el agente DEBE verificar que `dev/webpack.config.js` tenga configurado `watch: 'no'`. Si está en `'si'`, debe cambiarlo a `'no'` antes de proceder para evitar que el proceso se bloquee en la terminal.

## Flujo de Trabajo Maestro
1. **Definir Estructura:** CPTs y lógica de conversión en `heart-content-architect`.
2. **Exponer Funcionalidad:** Crear endpoints REST en `heart-api`.
3. **Construir Interfaz:** Desarrollar bloques en `dev/src/` y compilarlos hacia `heart-blocks-base`.
4. **Presentación:** Ajustar tokens de diseño (colores, tipos, gaps) en el `theme.json` del tema.

---
*Este archivo es el índice global. Para detalles técnicos específicos, consulte los archivos GEMINI.md de cada subdirectorio.*
