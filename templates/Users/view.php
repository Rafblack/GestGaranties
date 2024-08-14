<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<section class="content-header">
    <h1>Informations - Compte</h1>
</section>
<div class="content">
    <div class="box0">
        <div class="box box-primary">
            <aside class="column align-right" style="text-align:right">
                <div class="side-nav">
                    <?= $this->Html->link(__(''), ['action' => 'index'], ['class' => 'fa fa-close','title' => 'Fermer et retouner Liste utilisateurs']) ?>
                </div>
            </aside>
            <table class="table table-bordered table-hover">
                <tr>
                    <th><?= __('Nom Prenom') ?></th>
                    <td><?= h($user->nom_prenom) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($user->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fonction') ?></th>
                    <td><?= h($user->fonction) ?></td>
                </tr>
                <tr>
                    <th><?= __('Matricule') ?></th>
                    <td><?= h($user->matricule) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created', ['label' => 'Date Création']) ?></th>
                    <td><?= h($user->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified', ['label' => 'Date Dernière modification']) ?></th>
                    <td><?= h($user->modified) ?></td>
                </tr>
                <?php if($user->modified_by != null): ?>
                    <tr>
                    <th><?= __('Date modifiée') ?></th>
                    <td><?= h($user->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modifiée par') ?></th>
                    <td><?= $this->Html->link(($USERS->get($user->modified_by))->nom_prenom,["controller"=> "users", "action"=> "view",$user->modified_by]) ?></td>
                    </tr>


                    <?php endif; ?>
                    <tr>
                    <th><?= __('Date crée') ?></th>
                    <td><?= h($user->created) ?></td>
                </tr>
                <tr>
                <th><?= __('crée par') ?></th>
                <td><?= $this->Html->link(($USERS->get($user->created_by))->nom_prenom,["controller"=> "users", "action"=> "view",$user->created_by]) ?></td>
                </tr>

                <?php if($user->restored_by != null): ?>
                    <tr>
                    <th><?= __('Date restorée') ?></th>
                    <td><?= h($user->date_r) ?></td>
                </tr>
                <tr>
                    <th><?= __('restorée par') ?></th>
                    <td><?= $this->Html->link(($USERS->get($user->restored_by))->nom_prenom,["controller"=> "users", "action"=> "view",$user->restored_by]) ?></td>
                    </tr>


                    <?php endif; ?>
                

            </table>
        </div>
    </div>
</div>
