<?php
/** @var array<string, mixed> $trajet */
?>
<div class="modal fade" id="modalDetails<?= (int) $trajet['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du trajet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Auteur : <strong><?= htmlspecialchars($trajet['auteur_prenom'] . ' ' . $trajet['auteur_nom'], ENT_QUOTES, 'UTF-8') ?></strong></p>
                <p>Téléphone : <strong><?= htmlspecialchars($trajet['auteur_telephone'], ENT_QUOTES, 'UTF-8') ?></strong></p>
                <p>Email : <strong><?= htmlspecialchars($trajet['auteur_email'], ENT_QUOTES, 'UTF-8') ?></strong></p>
                <p class="mb-0">Nombre total de places : <strong><?= (int) $trajet['nb_places_total'] ?></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
