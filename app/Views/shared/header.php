<!doctype html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport" content="width=device-width" />
  
  <title>Trantor Technologies | <?= esc($title) ?></title>
  
  <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/images/logos/logo-2.png') ?>" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/styles.min.css') ?>" />

<?php
  // Rutas que usan tablas del administrador (DataTables + extensión Buttons).
  $adminTableRoutes = ['directorio', 'ocupation', 'department', 'area', 'custom-organigram', 'empleados', 'usuarios'];
  $isAdminTable = false;
  foreach ($adminTableRoutes as $r) {
    if (strpos(uri_string(), $r) !== false) { $isAdminTable = true; break; }
  }
?>
<?php if ($isAdminTable): ?>
  <!-- DataTables (núcleo + Buttons) - CSS -->
  <link rel="stylesheet" href="//cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css">
  <link rel="stylesheet" href="//cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.min.css">
<?php endif; ?>

  <!-- jQuery -->
  <script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js') ?>"></script>


<?php if ( strpos(uri_string(), 'documentos' ) !== false || strpos(uri_string(), 'documents' ) !== false ): ?>
  <!-- JsTree -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jstree/dist/themes/default/style.min.css">  
  <script src="https://cdn.jsdelivr.net/npm/jstree/dist/jstree.min.js"></script>
<?php endif; ?>  

<?php if (strpos(uri_string(), 'documents') !== false || strpos(uri_string(), 'trantor-informa') !== false): ?>
  <!-- FilePond -->
  <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
  <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
  <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
  <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
  <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
  <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<?php endif; ?>  

<?php if (strpos(uri_string(), 'trantor-technologies') !== false): ?>
  <!-- Technologies -->
  <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,900">
  <link rel="stylesheet" href="<?= base_url('assets/ttech_lp/css/bootstrap.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/ttech_lp/css/fonts.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/ttech_lp/css/style.css') ?>" />
  <style>.ie-panel{display: none;background: #212121;padding: 10px 0;box-shadow: 3px 3px 5px 0 rgba(0,0,0,.3);clear: both;text-align:center;position: relative;z-index: 1;} html.ie-10 .ie-panel, html.lt-ie-10 .ie-panel {display: block;}</style>
<?php endif; ?>  

<?php if (strpos(uri_string(), 'organization') !== false || strpos(uri_string(), 'custom-organigram') !== false): ?>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/orgchart/2.1.9/css/jquery.orgchart.min.css">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/orgchart/2.1.9/js/jquery.orgchart.min.js"></script>
<?php endif; ?>  

  <link rel="stylesheet" href="<?= base_url('assets/css/index.css') ?>" />

</head>

<!--  Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
<!-- Loader -->
<div id="loader" class="loader">
  <div class="spinner"></div>
</div>