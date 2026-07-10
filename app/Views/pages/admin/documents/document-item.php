<?php

    $files_formats = array(
        'pdf' => 'assets/images/icons/pdf.png',
        'doc' => 'assets/images/icons/docx.png',
        'xls' => 'assets/images/icons/xls.webp',
        'ppt' => 'assets/images/icons/ppt.png',
        'zip' => 'assets/images/icons/zip.png',
        'rar' => 'assets/images/icons/rar.png',
        'unk' => 'assets/images/icons/unk.png'
    );

    $file_type  = base_url($files_formats['unk']);
    $file_label = 'Archivo';
    $file_pdf   = false;

    if (strpos($file->type, 'zip') !== false) {
        $file_type = base_url($files_formats['zip']);         $file_label = 'ZIP';
    } else if (strpos($file->type, 'rar') !== false) {
        $file_type = base_url($files_formats['rar']);         $file_label = 'RAR';
    } else if (strpos($file->type, 'pdf') !== false) {
        $file_type = base_url($files_formats['pdf']);         $file_label = 'PDF';   $file_pdf = true;
    } else if (strpos($file->type, 'word') !== false) {
        $file_type = base_url($files_formats['doc']);         $file_label = 'Word';
    } else if (strpos($file->type, 'sheet') !== false) {
        $file_type = base_url($files_formats['xls']);         $file_label = 'Excel';
    } else if (strpos($file->type, 'presentation') !== false) {
        $file_type = base_url($files_formats['ppt']);         $file_label = 'PowerPoint';
    }

    $rol      = session('user')->rol;
    $size_mb  = $file->size / (1024 * 1024);
    $size_txt = $size_mb < 0.01
                    ? number_format($file->size / 1024, 0) . ' KB'
                    : number_format($size_mb, 2) . ' MB';
?>

<li class="doc-file">
    <a class="doc-file__main" href="<?= base_url($file->path) ?>" target="_blank" title="<?= esc($file->name) ?>">
        <span class="doc-file__icon">
            <img src="<?= $file_type ?>" alt="<?= esc($file_label) ?>" width="30" height="30">
        </span>
        <span class="doc-file__info">
            <span class="doc-file__name"><?= esc($file->name) ?></span>
            <span class="doc-file__meta"><?= esc($file_label) ?> &middot; <?= $size_txt ?></span>
        </span>
    </a>
    <div class="doc-file__actions dropdown">
        <button class="doc-file__btn" type="button" id="feed__card__actions_<?= $file->id ?>"
                data-bs-toggle="dropdown" aria-expanded="false" title="Opciones">
            <i class="ti ti-dots-vertical"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="feed__card__actions_<?= $file->id ?>">
            <?php if ($file_pdf): ?>
                <li><a class="dropdown-item" href="<?= base_url($file->path) ?>" target="_blank"><i class="ti ti-eye me-2"></i>Ver</a></li>
            <?php endif; ?>
            <li><a class="dropdown-item" href="<?= base_url($file->path) ?>" download><i class="ti ti-download me-2"></i>Descargar</a></li>
            <?php if ($rol === 'admin'): ?>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger delete__file" fileId="<?= $file->id ?>"><i class="ti ti-trash me-2"></i>Eliminar</a></li>
            <?php endif; ?>
        </ul>
    </div>
</li>
