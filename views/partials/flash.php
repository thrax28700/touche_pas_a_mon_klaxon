<?php
/** @var array<int, array{type: string, message: string}> $flashes */
$alertClasses = ['success' => 'alert-success', 'error' => 'alert-danger', 'info' => 'alert-secondary'];
?>
<?php foreach ($flashes as $flash): ?>
    <div class="alert <?= $alertClasses[$flash['type']] ?? 'alert-secondary' ?>" role="alert">
        <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endforeach; ?>
