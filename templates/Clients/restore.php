<div style="text-align: center;">
    <h3>Confirmation</h3>

    <p style="font-size: 1.2em;">Ancien numéro : <?= $oldcode ?></p>
    <p style="font-size: 1.2em;">Nouveau numéro : <?= $code ?></p>

    <?= $this->Form->create($client, ['url' => ['action' => 'confirm_restore', $id]]) ?>
    <?= $this->Form->hidden('code', ['value' => $code]) ?> 
    <?= $this->Form->submit('Confirmer la restauration', ['class' => 'btn btn-primary', 'style' => 'font-size: 1.2em; margin-top: 20px;']) ?>
    <?= $this->Form->end() ?>

    <p style="font-size: 0.9em; margin-top: 20px;">Si l'ancien code est différent, c'est parce qu'il a été attribué à une/un autre clienr(e) pendant la période où ce/cette client(e) était désactivée.</p>
</div>
