<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\SettingModel;

/**
 * Bloquea el acceso a los módulos que el administrador apagó desde
 * /configuracion. El rol admin nunca se bloquea: así puede seguir revisando y
 * administrando la sección aunque esté oculta para el resto de la intranet.
 *
 * Se aplica por patrón de URI desde Config\Filters::$filters.
 */
class ModuleFilter implements FilterInterface
{
    /**
     * Prefijo de URI => clave del interruptor en la tabla `settings`.
     */
    private const MODULES = [
        'organization' => 'organization_enabled',
    ];

    public function before(RequestInterface $request, $arguments = null)
    {
        $rol = session('user')->rol ?? null;

        // El administrador conserva el acceso para poder gestionar la sección.
        if ($rol === 'admin') {
            return;
        }

        $uri = uri_string();
        $key = null;

        foreach (self::MODULES as $prefix => $settingKey) {
            if (strpos($uri, $prefix) === 0) {
                $key = $settingKey;
                break;
            }
        }

        if ($key === null) {
            return;
        }

        if (!(new SettingModel())->isEnabled($key)) {
            return redirect()->to('/404');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Sin lógica posterior.
    }
}
