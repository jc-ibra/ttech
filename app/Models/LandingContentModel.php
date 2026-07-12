<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Contenido administrable de la landing "Trantor Technologies".
 * Almacén clave-valor. Los valores por defecto (defaults()) reproducen el
 * contenido original que estaba hardcodeado en la vista, de modo que la página
 * se ve idéntica aunque todavía no se haya guardado nada desde el administrador.
 */
class LandingContentModel extends Model
{
    protected $table         = 'landing_content';
    protected $primaryKey    = 'id';
    protected $returnType    = 'object';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $allowedFields = ['content_key', 'content_value'];

    /**
     * Contenido base (el que estaba hardcodeado en la vista original).
     * Las rutas de imagen son relativas a public/.
     */
    public static function defaults(): array
    {
        return [
            // Hero / Parallax
            'hero_image'     => 'assets/ttech_lp/img/cableado.jpg',
            'hero_title'     => 'Trantor Technologies',
            'hero_highlight' => 'Intranet',
            'hero_subtitle'  => 'Conoce lo que tenemos para ti',

            // Misión / Visión / Valores
            'mission_title' => 'Nuestra Misión',
            'mission_text'  => 'Ser una empresa en constante innovación que busca el máximo beneficio de nuestros clientes a través de la calidad de nuestras soluciones de tecnologías de información, promoviendo siempre las mejores condiciones de trabajo para nuestros colaboradores y una alta rentabilidad para nuestros accionistas.',
            'vision_title'  => 'Nuestra Visión',
            'vision_text'   => 'Ser un aliado estratégico para nuestros clientes, diseñando e implementando las mejores soluciones en tecnologías de información, para cumplir plenamente sus requerimientos y expectativas, con base en el talento de nuestro equipo y nuestro compromiso.',
            'values_title'  => 'Núcleo de Valores',
            // Un valor por línea.
            'values_list'   => "Integridad\nExcelencia\nResponsabilidad\nEspíritu de Equipo\nRespeto Mutuo",

            // Contadores
            'counter1_value' => '67000',
            'counter1_label' => 'Usuarios felices',
            'counter2_value' => '25',
            'counter2_label' => 'Años de experiencia',
            'counter3_value' => '58',
            'counter3_label' => 'Ciudades cubiertas',
            'counter4_value' => '11000',
            'counter4_label' => 'Tickets de atención / Mes',

            // Secciones de la intranet (tarjetas)
            'sections_title' => 'Intranet secciones',

            'card1_image'  => 'assets/ttech_lp/img/secciones/comunidad.jpg',
            'card1_title'  => 'Trantor Informa',
            'card1_text'   => 'Mantente al día con los comunicados importantes. Tu participación es fundamental para el éxito de nuestra comunidad.',
            'card1_button' => 'Ir ahora',

            'card2_image'  => 'assets/ttech_lp/img/secciones/documentos.jpg',
            'card2_title'  => 'Documentos',
            'card2_text'   => '¿Buscas algún documento en particular? En esta sección compartimos contigo los documentos más relevantes para tus labores diarias.',
            'card2_button' => 'Ir ahora',

            'card3_image'  => 'assets/ttech_lp/img/secciones/buzon.png',
            'card3_title'  => 'Quejas y Sugerencias',
            'card3_text'   => 'Tu opinión es importante para nosotros. En esta sección puedes dejar tus quejas y sugerencias para ayudarnos a mejorar.',
            'card3_button' => 'Ir ahora',

            'card4_image'  => 'assets/ttech_lp/img/secciones/directorio.jpg',
            'card4_title'  => 'Directorio',
            'card4_text'   => 'Explora nuestro nuevo directorio, localiza rapidamente el contácto de quien lo necesites.',
            'card4_button' => 'Ir ahora',
        ];
    }

    /**
     * Devuelve el contenido combinando los valores guardados sobre los defaults.
     * Así nunca falta una clave y la página siempre tiene contenido.
     */
    public function getContent(): array
    {
        $content = self::defaults();

        try {
            foreach ($this->findAll() as $row) {
                // Un valor guardado como cadena vacía se respeta; sólo null cae al default.
                if ($row->content_value !== null) {
                    $content[$row->content_key] = $row->content_value;
                }
            }
        } catch (\Throwable $e) {
            // La tabla aún no existe (migración pendiente): usamos los defaults.
        }

        return $content;
    }

    /**
     * Guarda (upsert) un conjunto de pares clave => valor.
     */
    public function saveContent(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            $existing = $this->where('content_key', $key)->first();

            if ($existing) {
                $this->update($existing->id, ['content_value' => $value]);
            } else {
                $this->insert([
                    'content_key'   => $key,
                    'content_value' => $value,
                ]);
            }
        }
    }
}
