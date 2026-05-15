# Entorno de Desarrollo (Frontend & Bloques)

Guía técnica para el desarrollo de componentes React y compilación de assets para el ecosistema Poeticsoft Heart.

## Toolchain
- **Bundler:** Vite 5.
- **Transpilación:** React (JSX), SCSS.
- **Configuración:** `dev/vite.config.js`.

## Desarrollo de Bloques
Los bloques se desarrollan en `dev/src/blocks/[nombre-del-bloque]/`.

### Estructura de Archivos
- `index.jsx`: Registro del bloque en el cliente.
- `edit.jsx`: Interfaz de usuario en el editor (React).
- `view.js`: Lógica interactiva en el frontend (opcional).
- `edit.scss`: Estilos del editor.
- `view.scss`: Estilos del frontend.

### Scripts de Compilación
Se utiliza una variable de entorno para definir el objetivo de la compilación:
- `npm run build --target=block-base`: Compila el bloque base.
- `npm run start --target=block-base`: Modo watch/dev.

## Destino de Producción
Vite está configurado para emitir los archivos compilados directamente en la subcarpeta `build/` del bloque correspondiente dentro del plugin de bloques:
`prod/plugins/poeticsoft-heart-blocksbase/blocks/[nombre-bloque]/build/`

## Convenciones de UI
- Utilizar los **Design Tokens** definidos en el `theme.json` mediante variables CSS de WordPress (`var(--wp--preset--*)`).
- Asegurar compatibilidad con el sistema de tipografía fluida.
