<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Evaluateur> $evaluateurs
 */
?>
<section class="content-header">
<h1 style="color: red;">Liste - Evaluateurs Supprimés</h1>
</section>
<div class="evaluateurs index content">
<div class="search-form">
    <?= $this->Form->create(null, ['type' => 'get', 'url' => ['action' => 'index'], 'class' => 'form-inline']) ?>
    <div class="input-group">
        <?= $this->Form->text('search', ['class' => 'form-control', 'placeholder' => 'Rechercher...']) ?>
        <span class="input-group-btn">
            <?= $this->Form->button('Rechercher', ['type' => 'submit', 'class' => 'btn btn-default btn-sm']) ?>
        </span>
    </div>
    <?= $this->Form->end() ?>
</div>
    <?php $i=1; ?>
    <div class="box">
    <div class="table-responsive">

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>NO.</th>
                    <th><?= $this->Paginator->sort('nom_prenom', ['label' => 'Nom & Prenom']) ?></th>
                    <th><?= $this->Paginator->sort('Date de Suppresion') ?></th>
                    <th><?= $this->Paginator->sort('tel',['label' => 'Téléphone']) ?></th>
                    <th><?= $this->Paginator->sort('email',['label' => 'E-mail']) ?></th>
                    <th><?= $this->Paginator->sort('fonction_evaluateur',['label' => 'Fonction']) ?></th>
                    <th><?= $this->Paginator->sort('nom_structure', ['label' => 'Structure']) ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($evaluateurs as $evaluateur): ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= h($evaluateur->nom_prenom) ?></td>
                    <td><?= h($evaluateur->del_at) ?></td>

                    <td><?= h($evaluateur->tel) ?></td>
                    <td><?= h($evaluateur->email) ?></td>
                    <td><?= h($evaluateur->fonction_evaluateur) ?></td>
                    <td><?= h($evaluateur->nom_structure) ?></td>
                    <td class="actions">
                           

                    <?=$this->Html->image("restorer1.png", [
                            'height' => '15', 'width' => '27',
                            "alt" => "Restorer",'title' => 'Restorer',
                            'url' => ['controller' => 'Evaluateurs', 'action' => 'restore',$evaluateur->id]
                        ]);?>
                        
                    </td>
                </tr>
                <?php ++$i;endforeach; ?>
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
        <p><?= $this->Paginator->counter(__('Page {{page}} sur {{pages}}, Affichage {{current}} enregistremeny(s) sur {{count}} au total')) ?></p>
    </div>
</div>
