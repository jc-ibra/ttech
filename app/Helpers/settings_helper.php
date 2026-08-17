<?php

// Helper sin namespace: CodeIgniter lo carga desde Config\Autoload::$helpers y
// las funciones deben quedar en el espacio global para usarse en las vistas.

use App\Models\SettingModel;
use App\Models\ExternalSystemModel;

if (!function_exists('intranet_settings')) {
    /**
     * Ajustes de la intranet (se consultan una sola vez por petición).
     *
     * @return array<string,string>
     */
    function intranet_settings(): array
    {
        static $settings = null;

        if ($settings === null) {
            $settings = (new SettingModel())->getSettings();
        }

        return $settings;
    }
}

if (!function_exists('module_enabled')) {
    /**
     * ¿Está visible un módulo? Ej.: module_enabled('organization_enabled').
     */
    function module_enabled(string $key): bool
    {
        $settings = intranet_settings();

        return ($settings[$key] ?? '1') === '1';
    }
}

if (!function_exists('external_systems')) {
    /**
     * Enlaces activos del bloque "Sistemas externos".
     * Devuelve un arreglo vacío si el bloque está apagado.
     */
    function external_systems(): array
    {
        static $systems = null;

        if (!module_enabled('external_systems_enabled')) {
            return [];
        }

        if ($systems === null) {
            $systems = (new ExternalSystemModel())->getActiveSystems();
        }

        return $systems;
    }
}
