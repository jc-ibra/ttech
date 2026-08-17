<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Configuración general de la intranet (almacén clave-valor).
 * Los valores por defecto (defaults()) reproducen el comportamiento previo a
 * que la configuración fuera administrable, de modo que si la tabla está vacía
 * —o la migración aún no corre— todo sigue visible como siempre.
 */
class SettingModel extends Model
{
    protected $table         = 'settings';
    protected $primaryKey    = 'id';
    protected $returnType    = 'object';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $allowedFields = ['setting_key', 'setting_value'];

    /**
     * Interruptores disponibles. '1' = visible, '0' = oculto.
     */
    public static function defaults(): array
    {
        return [
            // Sección de organigramas (/organization).
            'organization_enabled'     => '1',
            // Bloque "Sistemas externos" (perfil y atajos del feed).
            'external_systems_enabled' => '1',
        ];
    }

    /**
     * Devuelve todos los ajustes combinando lo guardado sobre los defaults.
     */
    public function getSettings(): array
    {
        $settings = self::defaults();

        try {
            foreach ($this->findAll() as $row) {
                if ($row->setting_value !== null) {
                    $settings[$row->setting_key] = $row->setting_value;
                }
            }
        } catch (\Throwable $e) {
            // La tabla aún no existe (migración pendiente): usamos los defaults.
        }

        return $settings;
    }

    /**
     * ¿Está activo un interruptor?
     */
    public function isEnabled(string $key): bool
    {
        $settings = $this->getSettings();

        return ($settings[$key] ?? '1') === '1';
    }

    /**
     * Guarda (upsert) un conjunto de pares clave => valor.
     */
    public function saveSettings(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            $existing = $this->where('setting_key', $key)->first();

            if ($existing) {
                $this->update($existing->id, ['setting_value' => $value]);
            } else {
                $this->insert([
                    'setting_key'   => $key,
                    'setting_value' => $value,
                ]);
            }
        }
    }
}
