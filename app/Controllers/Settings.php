<?php

namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\ExternalSystemModel;
use App\Controllers\HelperUtility;

/**
 * Configuración de la intranet.
 * Permite al administrador mostrar u ocultar secciones (organigramas, bloque de
 * "Sistemas externos") y dar de alta/editar los enlaces de dicho bloque.
 */
class Settings extends BaseController
{
    protected $lang;
    protected $settingModel;
    protected $externalSystemModel;

    // Interruptores administrables (checkbox en el formulario).
    private const TOGGLE_KEYS = [
        'organization_enabled',
        'external_systems_enabled',
    ];

    public function __construct()
    {
        $this->lang                = \Config\Services::language();
        $this->lang                ->setLocale('es');
        $this->settingModel        = new SettingModel();
        $this->externalSystemModel = new ExternalSystemModel();
    }

    public function index(): string
    {
        return   view('shared/header',                  ['title' => 'Configuración'])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/admin/settings/index',     [
                                                            'settings' => $this->settingModel->getSettings(),
                                                            'systems'  => $this->externalSystemModel->getSystems(),
                                                            'csrfName' => csrf_token(),
                                                            'csrfHash' => csrf_hash(),
                                                        ])
                .view('shared/footer');
    }

    /**
     * Guarda los interruptores de visibilidad. Un checkbox no marcado no viaja
     * en el POST, por eso cada clave se evalúa explícitamente.
     */
    public function update()
    {
        $pairs = [];

        foreach (self::TOGGLE_KEYS as $key) {
            $pairs[$key] = $this->request->getPost($key) ? '1' : '0';
        }

        $this->settingModel->saveSettings($pairs);

        return HelperUtility::redirectWithMessage('configuracion', 'Configuración actualizada correctamente.', 'success');
    }

    /**
     * Alta o edición de un sistema externo (AJAX).
     */
    public function saveSystem()
    {
        $id          = $this->request->getPost('id');
        $title       = trim((string) $this->request->getPost('title'));
        $description = trim((string) $this->request->getPost('description'));
        $icon        = trim((string) $this->request->getPost('icon'));
        $url         = trim((string) $this->request->getPost('url'));
        $sortOrder   = (int) $this->request->getPost('sort_order');
        $active      = $this->request->getPost('active') ? 1 : 0;

        if (!$this->checkEmptyField([$title, $url])) {
            return $this->respondWithCsrf([
                'ok'    => false,
                'error' => 'El nombre y la URL son obligatorios.',
            ]);
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return $this->respondWithCsrf([
                'ok'    => false,
                'error' => 'La URL no es válida. Incluye http:// o https://',
            ]);
        }

        $data = [
            'title'       => $title,
            'description' => $description,
            'icon'        => $icon !== '' ? $icon : 'ti ti-external-link',
            'url'         => $url,
            'sort_order'  => $sortOrder,
            'active'      => $active,
        ];

        if ($id) {
            if (!$this->externalSystemModel->find($id)) {
                return $this->respondWithCsrf([
                    'ok'    => false,
                    'error' => lang('Errors.gral_not_found'),
                ]);
            }

            $saved = $this->externalSystemModel->update($id, $data);
        } else {
            $saved = $this->externalSystemModel->insert($data);
        }

        if (!$saved) {
            return $this->respondWithCsrf([
                'ok'    => false,
                'error' => lang('Errors.error_try_again_later'),
            ]);
        }

        return $this->respondWithCsrf(['ok' => true]);
    }

    /**
     * Elimina un sistema externo (AJAX, borrado lógico).
     */
    public function deleteSystem()
    {
        $id = $this->request->getPost('id');

        if (!$this->externalSystemModel->find($id)) {
            return $this->respondWithCsrf([
                'ok'    => false,
                'error' => lang('Errors.gral_not_found'),
            ]);
        }

        if ($this->externalSystemModel->delete($id)) {
            return $this->respondWithCsrf(['ok' => true]);
        }

        return $this->respondWithCsrf([
            'ok'    => false,
            'error' => lang('Errors.error_try_again_later'),
        ]);
    }
}
