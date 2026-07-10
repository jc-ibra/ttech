<div class="container-fluid">
    <div class="card sg-inbox">
        <div class="row g-0 sg-inbox__row">

            <!-- Lista -->
            <div class="col-md-4 sg-inbox__list-col" id="suggestion__list_main">
                <div class="sg-inbox__list-head">
                    <div class="sg-inbox__list-title">
                        <i class="ti ti-message-2-star"></i>
                        <span>Quejas y sugerencias</span>
                    </div>
                    <span class="sg-inbox__count" id="s__count">0</span>
                </div>
                <div class="suggestion__container">
                    <ul class="s__list" id="s__list__wrapper"></ul>
                </div>
            </div>

            <!-- Detalle -->
            <div class="col sg-inbox__detail-col" id="suggestion__wrapper_main">

                <!-- Estado vacío -->
                <div class="sg-empty" id="suggestion__empty">
                    <div class="sg-empty__icon"><i class="ti ti-mail-opened"></i></div>
                    <h5>Selecciona un mensaje</h5>
                    <p>Elige una queja o sugerencia de la lista para ver su contenido.</p>
                </div>

                <!-- Contenido del mensaje -->
                <div id="suggestion__wrapper" style="display: none;">
                    <button class="btn sg-back" id="s__back" style="display:none;">
                        <i class="ti ti-arrow-left"></i> Volver
                    </button>

                    <div class="sg-detail__head">
                        <div class="sg-detail__subject">
                            <span class="sg-detail__eyebrow">Asunto</span>
                            <h4 id="s__title"></h4>
                        </div>
                        <span class="sg-status" id="s__status"></span>
                    </div>

                    <div class="sg-detail__card">
                        <div class="sg-detail__sender">
                            <img src="" alt="" class="sg-detail__photo" id="s__photo">
                            <div class="sg-detail__sender-info">
                                <b id="s__name"></b>
                                <span id="s__date"></span>
                            </div>
                        </div>

                        <div class="sg-detail__body" id="s__message"></div>

                        <div class="sg-detail__actions">
                            <a class="sg-action" id="s__markUnread" suggestionId="">
                                <i class="ti ti-mail-off"></i> Marcar no leído
                            </a>
                            <a class="sg-action sg-action--danger" id="s__delete" suggestionId="">
                                <i class="ti ti-trash"></i> Eliminar
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
  var csrfName = '<?= $csrfName ?>';
  var csrfHash = '<?= $csrfHash ?>';
</script>
