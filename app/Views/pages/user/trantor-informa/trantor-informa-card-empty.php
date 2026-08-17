<div class="tinf__card tinf__empty">
    <!-- Ilustración decorativa: megáfono con "publicaciones" flotando alrededor -->
    <div class="tinf__empty__art">
        <svg viewBox="0 0 260 150" role="img" aria-label="Ilustración de un megáfono anunciando comunicados" focusable="false">
            <defs>
                <linearGradient id="tinfEmptyCircle" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#D5E8F7" />
                    <stop offset="100%" stop-color="#EEF5FC" />
                </linearGradient>
            </defs>

            <!-- Anillo punteado y disco de fondo -->
            <circle cx="130" cy="70" r="62" fill="none" stroke="#9CCAEE" stroke-width="1.5" stroke-dasharray="3 8" stroke-linecap="round" opacity=".65" />
            <circle cx="130" cy="70" r="46" fill="url(#tinfEmptyCircle)" />
            <ellipse cx="130" cy="128" rx="54" ry="7" fill="#D5E8F7" opacity=".5" />

            <!-- Tarjeta flotante izquierda -->
            <g transform="rotate(-9 51 56)">
                <rect x="28" y="40" width="46" height="32" rx="7" fill="#FFFFFF" stroke="#E3E4E5" />
                <circle cx="37" cy="49" r="4" fill="#9CCAEE" />
                <rect x="45" y="47" width="22" height="4" rx="2" fill="#E3E4E5" />
                <rect x="35" y="58" width="32" height="4" rx="2" fill="#F1F1F2" />
                <rect x="35" y="65" width="20" height="4" rx="2" fill="#F1F1F2" />
            </g>

            <!-- Tarjeta flotante derecha -->
            <g transform="rotate(8 209 84)">
                <rect x="186" y="68" width="46" height="32" rx="7" fill="#FFFFFF" stroke="#E3E4E5" />
                <circle cx="195" cy="77" r="4" fill="#57A5E0" />
                <rect x="203" y="75" width="22" height="4" rx="2" fill="#E3E4E5" />
                <rect x="193" y="86" width="32" height="4" rx="2" fill="#F1F1F2" />
                <rect x="193" y="93" width="20" height="4" rx="2" fill="#F1F1F2" />
            </g>

            <!-- Megáfono -->
            <g transform="rotate(-8 130 70) translate(94 34) scale(3)"
               fill="none" stroke="#115EA3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8a3 3 0 0 1 0 6" />
                <path d="M10 8v11a1 1 0 0 1 -1 1h-1a1 1 0 0 1 -1 -1v-5" />
                <path d="M12 8h0l4.524 -3.77a.9 .9 0 0 1 1.476 .692v12.156a.9 .9 0 0 1 -1.476 .692l-4.524 -3.77h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1h8" />
            </g>

            <!-- Destellos -->
            <circle cx="72" cy="104" r="3.5" fill="#9CCAEE" opacity=".8" />
            <circle cx="196" cy="34" r="4.5" fill="#57A5E0" opacity=".55" />
            <circle cx="168" cy="126" r="2.5" fill="#9CCAEE" opacity=".7" />
        </svg>
    </div>

    <div class="tinf__empty__body">
        <span class="tinf__empty__badge"><i class="ti ti-clock-hour-3"></i> Próximamente</span>
        <h5>Aún no hay comunicados publicados</h5>
        <p>
            Pronto podrás visualizar aquí los comunicados, noticias y anuncios
            importantes de <strong>Trantor Technologies</strong>. En cuanto se
            publique el primero, aparecerá en este espacio.
        </p>

        <ul class="tinf__empty__hints">
            <li><i class="ti ti-speakerphone"></i> Comunicados</li>
            <li><i class="ti ti-news"></i> Noticias</li>
            <li><i class="ti ti-calendar-event"></i> Eventos</li>
        </ul>

        <?php if (session('user')->rol == 'admin'): ?>
            <!-- Sólo admin: el modal #createFeed únicamente se incluye para ese rol -->
            <button type="button" class="tinf__empty__cta" data-bs-toggle="modal" data-bs-target="#createFeed">
                <i class="ti ti-plus"></i> Crear la primera publicación
            </button>
        <?php endif; ?>
    </div>
</div>
