<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Evaluation> $evaluations
 */
?>
<section class="content-header">
    <h1>Liste - Evaluations</h1>
</section>
<div class="evaluations index content">
    <?php $i =1; ?>
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
    <div class="box box-primary">
    <div class="table-responsive">

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>NO.</th>
                    <th><?= $this->Paginator->sort('frequence') ?></th>
                    <th><?= $this->Paginator->sort('valeur_garantie') ?></th>
                    <th><?= $this->Paginator->sort('date_debut') ?></th>

                    <th><?= $this->Paginator->sort('date_fin') ?></th>
                    <th><?= $this->Paginator->sort('evaluateur_id',['label' => 'Evaluateur(rice)']) ?></th>
                    <th><?= $this->Paginator->sort('garantie_id',['label' => 'Numero Garantie']) ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($evaluations as $evaluation): ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= h($evaluation->frequence) ?></td>
                    <td><?= h((string)$evaluation->valeur_garantie . $evaluation->currency) ?></td>
                    <td><?= h($evaluation->date_debut) ?></td>

                    <td><?= h($evaluation->date_fin) ?></td>
                    <td><?= $evaluation->has('evaluateur') ? $this->Html->link($evaluation->evaluateur->nom_prenom, ['controller' => 'Evaluateurs', 'action' => 'view', $evaluation->evaluateur->id]) : '' ?></td>
                    <td><?= $evaluation->has('garanty') ? $this->Html->link($evaluation->garanty->numero, ['controller' => 'Garanties', 'action' => 'view', $evaluation->garanty->id]) : '' ?></td>
                    <td class="actions">
                        <?=$this->Html->image("afficher-btn.png", [
                            'height' => '15', 'width' => '27',
                            "alt" => "Modifier",'title' => 'Afficher',
                            'url' => ['controller' => 'Evaluations', 'action' => 'view',$evaluation->id]
                        ]);?>

                        <?=$this->Html->image("modifier-btn.png", [
                            'height' => '15', 'width' => '27',
                            "alt" => "Modifier",'title' => 'Modifier',
                            'url' => ['controller' => 'Evaluations', 'action' => 'edit',$evaluation->id]
                        ]);?>

                        <?= $this->Form->postLink(
                            $this->Html->image(
                                "supprimer-btn.png",
                                ["alt" => "Supprimer", 'class' => 'action-link','title' => 'Supprimer','height' => '15', 'width' => '27']
                            ),
                            ['controller' => 'Evaluations', 'action' => 'delete', $evaluation->id],
                            ['escape' => false, 'confirm' => __('Voulez-vous supprimer cette évaluation?', $evaluation->id)]
                        );
                        ?>

                    </td>
                </tr>
                <?php endforeach; ?>
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
