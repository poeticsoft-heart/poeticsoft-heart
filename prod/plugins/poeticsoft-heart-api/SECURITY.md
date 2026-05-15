# Seguridad y Control de Acceso (API REST)

Este documento detalla la arquitectura de seguridad implementada en `poeticsoft-heart-api` para proteger los recursos del ecosistema, con especial énfasis en los endpoints de Inteligencia Artificial.

## 1. Filosofía de Seguridad
Nuestra API maneja dos perfiles de acceso con reglas diferenciadas:
- **Usuarios Autenticados (Editores/Admins):** Acceso basado en capacidades nativas de WordPress (`current_user_can`). Sin restricciones de frecuencia para facilitar el desarrollo y la gestión.
- **Usuarios Anónimos (Visitantes):** Acceso restringido mediante validación de origen y límites de frecuencia (Rate Limiting) para prevenir abusos y costes excesivos de API externas.

---

## 2. Capas de Protección

### Capa A: Validación de Origen (CORS/Referer)
Situada en `Endpoint_Base::is_public_request_safe`.
- **Mecánica:** Verifica las cabeceras HTTP `Referer` y `Origin`.
- **Regla:** La petición DEBE originarse desde el mismo dominio que `site_url()`. 
- **Objetivo:** Evitar ataques de tipo Cross-Site e impedir que terceros consuman tu API desde sus propios dominios o scripts externos (cURL, Postman sin headers).

### Capa B: Rate Limiting (Control de Frecuencia)
Gestionada por la clase `Poeticsoft\Heart\API\Security\Rate_Limiter`.
- **Identificación:** Se basa en la dirección IP real del cliente (gestionando proxies mediante `HTTP_X_FORWARDED_FOR`).
- **Almacenamiento:** Utiliza la API de Transients de WordPress (efímero y cacheable).
- **Métrica:** Contador por acción y ventana de tiempo.
- **Error:** Devuelve un código HTTP `429 (Too Many Requests)` cuando se excede el límite.

---

## 3. Implementación Técnica

### Clase `Rate_Limiter`
Se encuentra en `classes/security/class-rate-limiter.php`.
```php
Rate_Limiter::check_limit( $action, $max_requests, $window_seconds );
```
Genera claves únicas en caché con el formato: `psh_rl_{action}_{md5(IP)}`.

### Integración en Endpoints
Para proteger un nuevo endpoint, se debe usar el `permission_callback` de la siguiente forma:

```php
public function check_permissions( $request ) {
    // 1. Siempre permitir a editores
    if ( current_user_can( 'edit_posts' ) ) {
        return true;
    }

    // 2. Aplicar seguridad pública (Ej: 5 peticiones por minuto)
    return $this->is_public_request_safe( $request, 'mi_accion', 5, 60 );
}
```

---

## 4. Configuración Actual (Voz)
El endpoint `/voice/stream` tiene las siguientes reglas por defecto:
- **Acción:** `voice_stream`
- **Límite:** 5 peticiones.
- **Ventana:** 60 segundos.
- **Comportamiento:** Si un usuario anónimo habla más de 5 veces en un minuto, el servidor dejará de procesar su voz hasta que pase el tiempo de enfriamiento.

---

## 5. Mantenimiento y Escalabilidad
- **Purga de límites:** Al usar Transients, los registros de bloqueo se limpian automáticamente cuando expiran.
- **Proxies:** Si el sitio se mueve detrás de un balanceador de carga complejo (como Cloudflare), la lógica en `Rate_Limiter::get_client_ip()` asegura que se siga limitando por la IP del usuario real y no por la del servidor proxy.
