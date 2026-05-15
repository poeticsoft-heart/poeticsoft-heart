# Poeticsoft Heart Theme (Design System)

Tema basado en bloques (FSE) enfocado en un diseño editorial, minimalista y de alto rendimiento.

## Tokens de Diseño (theme.json)

### 1. Tipografía Fluida (Fluid Typography)
- **Escala:** Major Third (1.250).
- **Rango Viewport:** 320px a 1280px.
- **Implementación:** Basada en `clamp()` precalculado matemáticamente para evitar dependencias del motor nativo de WP.
- **Pasos:** `step--2` (XS) hasta `step-6` (5XL).
- **Familias:**
    - **Headings:** Playfair Display (Italic), pesos 400 y 700.
    - **Body:** Source Sans Pro, pesos 400 y 700.

### 2. Paleta Monocromática
- `base`: `#ffffff` (Fondo)
- `contrast`: `#111111` (Texto principal/Headings)
- `contrast-muted`: `#555555` (Secundarios/Captions)
- `surface`: `#f7f7f7` (Secciones sutiles)
- `border`: `#e0e0e0`

### 3. Layout y Espaciado
- **Content Size:** 800px.
- **Wide Size:** 1200px.
- **Spacing Scale:** Pasos `xs` a `3xl` basados en `rem`.

## Herramientas de Edición (UI)
El tema habilita todos los controles avanzados en el editor de bloques:
- **Dimensiones:** Padding, Margin y BlockGap.
- **Bordes:** Color, Radius, Style, Width.
- **Sombras:** Presets personalizados (`Natural`, `Deep`, `Sharp`).

## Assets Locales
Las fuentes se sirven localmente desde `assets/fonts/` (archivos `.woff2` validados de Fontsource) para cumplir con GDPR y optimizar la carga.

---
*Para modificar la escala tipográfica, utilice el script de cálculo Node.js referenciado en el historial de desarrollo.*
