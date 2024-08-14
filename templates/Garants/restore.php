<div style="text-align: center;">
    <h3>Confirmation</h3>

    <p style="font-size: 1.2em;">Ancien code_garant : <?= $oldcode ?></p>
    <p style="font-size: 1.2em;">Nouveau code_garant : <?= $code ?></p>

    <?= $this->Form->create($garant, ['url' => ['action' => 'confirm_restore', $id]]) ?>
    <?= $this->Form->hidden('code', ['value' => $code]) ?> <!-- Include hidden field to send numero -->
    <?= $this->Form->submit('Confirmer la restauration', ['class' => 'btn btn-primary', 'style' => 'font-size: 1.2em; margin-top: 20px;']) ?>
    <?= $this->Form->end() ?>

    <p style="font-size: 0.9em; margin-top: 20px;">Si l'ancien code est différent, c'est parce qu'il a été attribué à un/une autre garant pendant la période où celle/celui-ci était désactivée.</p>
</div>
