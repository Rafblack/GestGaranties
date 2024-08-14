<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
?>
<section class="content-header">
    <h1>Liste - Utilisateurs</h1>
</section>
<div class="users index content">
    <?php $i=1; ?>
    <div class="box">
    <div class="table-responsive">

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th><?= $this->Paginator->sort('nom_prenom',['label' => 'Nom & Prénom']) ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('fonction') ?></th>
                    <th><?= $this->Paginator->sort('matricule') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $i; ?></td>
                    <td><?= h($user->nom_prenom) ?></td>
                    <td><?= h($user->email) ?></td>
                    <td><?= h($user->fonction) ?></td>
                    <td><?= h($user->matricule) ?></td>
                    <td class="actions">
                        <?=$this->Html->image("afficher-btn.png", [
                            'height' => '15', 'width' => '27',
                            "alt" => "Modifier",'title' => 'Afficher',
                            'url' => ['controller' => 'Users', 'action' => 'view',$user->id]
                        ]);?>

                        <?=$this->Html->image("modifier-btn.png", [
                            'height' => '15', 'width' => '27',
                            "alt" => "Modifier",'title' => 'Modifier',
                            'url' => ['controller' => 'Users', 'action' => 'edit',$user->id]
                        ]);?>

                        <?= $this->Form->postLink(
                            $this->Html->image(
                                "supprimer-btn.png",
                                ["alt" => "Supprimer", 'class' => 'action-link','title' => 'Supprimer','height' => '15', 'width' => '27']
                            ),
                            ['controller' => 'Users', 'action' => 'delete', $user->id],
                            ['escape' => false, 'confirm' => __('Voulez-vous supprimer le compte: << '.$user->email.' >>?', $user->id)]
                        );
                        ?>
                    </td>
                </tr>
                <?php $i +=1 ; endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('Premier')) ?>
            <?= $this->Paginator->prev('< ' . __('Précédent')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('Suivant') . ' >') ?>
            <?= $this->Paginator->last(__('Dernier') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} sur {{pages}}, Affichage {{current}} enregistrement(s) sur {{count}} au total')) ?></p>
    </div>
</div>
