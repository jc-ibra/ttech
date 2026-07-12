<?php

namespace App\Controllers;

use App\Models\LandingContentModel;
use App\Controllers\HelperUtility;

/**
 * Administración del CONTENIDO de la landing "Trantor Technologies".
 * Sólo se edita el contenido (textos e imágenes); la estructura, estilos y los
 * enlaces internos de las tarjetas permanecen fijos en la vista pública.
 */
class Landing extends BaseController
{
    protected $landingModel;

    // Campos de texto editables.
    private const TEXT_KEYS = [
        'hero_title', 'hero_highlight', 'hero_subtitle',
        'mission_title', 'mission_text',
        'vision_title', 'vision_text',
        'values_title', 'values_list',
        'counter1_value', 'counter1_label',
        'counter2_value', 'counter2_label',
        'counter3_value', 'counter3_label',
        'counter4_value', 'counter4_label',
        'sections_title',
        'card1_title', 'card1_text', 'card1_button',
        'card2_title', 'card2_text', 'card2_button',
        'card3_title', 'card3_text', 'card3_button',
        'card4_title', 'card4_text', 'card4_button',
    ];

    // Campos de imagen editables.
    private const IMAGE_KEYS = [
        'hero_image', 'card1_image', 'card2_image', 'card3_image', 'card4_image',
    ];

    public function __construct()
    {
        $this->landingModel = new LandingContentModel();
    }

    public function index(): string
    {
        return   view('shared/header',              ['title' => 'Contenido Trantor Tech.'])
                .view('shared/sidebar')
                .view('shared/navbar')
                .view('pages/admin/landing/index',  [
                                                        'c'        => $this->landingModel->getContent(),
                                                        'csrfName' => csrf_token(),
                                                        'csrfHash' => csrf_hash(),
                                                    ])
                .view('shared/footer');
    }

    public function update()
    {
        // Contenido actual: nos sirve para conocer las imágenes anteriores.
        $current = $this->landingModel->getContent();
        $pairs   = [];

        // Textos
        foreach (self::TEXT_KEYS as $key) {
            $pairs[$key] = (string) $this->request->getPost($key);
        }

        // Imágenes (opcionales): sólo se actualiza la que se sube.
        foreach (self::IMAGE_KEYS as $key) {
            $file = $this->request->getFile($key);

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $path = $this->handleImageUpload($file);

                if ($path === false) {
                    return HelperUtility::redirectWithMessage('landing', lang('Errors.gral_upload_file_error'));
                }

                // Borrar la imagen anterior sólo si vivía en uploads/ (no los assets base).
                $old = $current[$key] ?? '';
                if (strpos($old, 'uploads/') === 0 && file_exists(ROOTPATH . 'public/' . $old)) {
                    unlink(ROOTPATH . 'public/' . $old);
                }

                $pairs[$key] = $path;
            }
        }

        $this->landingModel->saveContent($pairs);

        return HelperUtility::redirectWithMessage('landing', 'Contenido actualizado correctamente.', 'success');
    }

    /**
     * Sube una imagen de la landing y retorna la ruta relativa lista para guardar.
     * Retorna false si el archivo es inválido o el MIME no está permitido.
     */
    private function handleImageUpload($photo): string|false
    {
        if (!$photo || !$photo->isValid() || $photo->hasMoved()) {
            return false;
        }

        if (!in_array($photo->getClientMimeType(), ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])) {
            return false;
        }

        $uploadPath = ROOTPATH . 'public/uploads/images/landing';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newName = $photo->getRandomName();
        $photo->move($uploadPath, $newName);

        return 'uploads/images/landing/' . $newName;
    }
}
