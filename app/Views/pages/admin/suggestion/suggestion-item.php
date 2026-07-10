<?php
    $name       = trim($suggestion->name) !== '' ? trim($suggestion->name) : 'Anónimo';
    $initial    = strtoupper(mb_substr($name, 0, 1));
    $createdTs  = strtotime($suggestion->created_at);
?>
<li class="s__list_item <?= $suggestion->status ?>" suggestionId="<?= $suggestion->id ?>" id="suggestion_<?= $suggestion->id ?>">
    <div class="s__avatar"><?= esc($initial) ?></div>
    <div class="s__item-main">
        <div class="s__header">
            <span class="s__name"><?= esc($name) ?></span>
            <span class="s__time">
                <?php if (date('Y-m-d', $createdTs) == date('Y-m-d')): ?>
                    <?= date('H:i', $createdTs) ?>
                <?php elseif (date('Y-m-d', $createdTs) == date('Y-m-d', strtotime('-1 day'))): ?>
                    Ayer
                <?php else: ?>
                    <?= date('d/m/Y', $createdTs) ?>
                <?php endif; ?>
            </span>
        </div>
        <div class="s__body">
            <span class="s__subject"><?= esc($suggestion->title) ?></span>
            <span class="s__preview"><?= esc($suggestion->message) ?></span>
        </div>
    </div>
    <span class="s__dot"></span>
</li>
