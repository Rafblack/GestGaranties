<div style="text-align: center;">
    <h3>Confirmation</h3>

    <p style="font-size: 1.2em;">Ancien numéro : <?= $oldnumero ?></p>
    <p style="font-size: 1.2em;">Nouveau numéro : <?= $numero ?></p>

    <?= $this->Form->create($garantie, ['url' => ['action' => 'confirm_restore', $id]]) ?>
    <?= $this->Form->hidden('numero', ['value' => $numero]) ?> <!-- Include hidden field to send numero -->
    <?= $this->Form->submit('Confirmer la restauration', ['class' => 'btn btn-primary', 'style' => 'font-size: 1.2em; margin-top: 20px;']) ?>
    <?= $this->Form->end() ?>

    <p style="font-size: 0.9em; margin-top: 20px;">Si l'ancien numéro est différent, c'est parce qu'il a été attribué à une autre garantie pendant la période où cette garantie était désactivée.</p>
</div>
