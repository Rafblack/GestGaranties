<?php $this->layout = 'AdminLTE.login'; ?>
<div class="users form">

    <h3>Connexion</h3>
    <?= $this->Form->create() ?>
    <fieldset>
        <?= $this->Form->control('email', ['required' => true, 'placeholder' => 'E-mail']) ?>
        <?= $this->Form->control('password', ['required' => true, 'placeholder' => 'Mot de pass']) ?>
    </fieldset>
    <?= $this->Form->submit(__('Se Connecter')); ?>
    <?= $this->Form->end() ?>
    <?= $this->Flash->render() ?>
</div>
