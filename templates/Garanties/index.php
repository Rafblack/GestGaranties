<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Garanty> $garanties
 */
?>
<section class="content-header">
    <h1>Liste - Garanties</h1>
</section>
<div class="content">
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
<div class="box">
    
        <?php $i =1; ?>
        <div class="table-responsive">

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>NO.</th>
                    <th><?= $this->Paginator->sort('libelle_garantie') ?></th>
                    <th><?= $this->Paginator->sort('montant') ?></th>
                    <th><?= $this->Paginator->sort('date_debut') ?></th>
                    <th><?= $this->Paginator->sort('date_fin') ?></th>
                    <th><?= $this->Paginator->sort('numero') ?></th>
                    <th><?= $this->Paginator->sort('Reference') ?></th>
                    <th><?= $this->Paginator->sort('statut') ?></th>
                    <th><?= $this->Paginator->sort('classement') ?></th>

                    <th><?= $this->Paginator->sort('typologie_id',['label' => 'Typologie']) ?></th>
                    <th><?= $this->Paginator->sort('client_id',['label' => 'Client']) ?></th>
                    <th><?= $this->Paginator->sort('garant_id', ['label' =>'Garant']) ?></th>

                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($garanties as $garanty): ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= h($garanty->libelle_garantie) ?>
                    <td><?= h((string)$garanty->montant . $garanty->currency) ?></td>
                    <td><?= h($garanty->date_debut) ?></td>
                    <td><?= h($garanty->date_fin) ?></td>
                    <td><?= h($garanty->numero) ?></td>
                    <td><?= h($garanty->reference) ?></td>
                    <td><?= h($STAT[$garanty->numero]) ?></td>
                    <td><?= h($garanty->classement) ?></td>

                    <td><?= $garanty->has('typology') ? $this->Html->link($garanty->typology->label, ['controller' => 'Typologies', 'action' => 'view', $garanty->typology->id]) : '' ?></td>
                    <td><?= $garanty->has('client') ? $this->Html->link($garanty->client->label.'('.$garanty->client->code.')', ['controller' => 'Clients', 'action' => 'view', $garanty->client->id]) : '' ?></td>
                    <td><?= $garanty->has('garant') ? $this->Html->link($garanty->garant->intitule_garant.'('.$garanty->garant->code_garant.')', ['controller' => 'Garants', 'action' => 'view', $garanty->garant->id]) : '' ?></td>
                    <td class="actions">
                        <?=$this->Html->image("afficher-btn.png", [
                            'height' => '15', 'width' => '27',
                            "alt" => "Modifier",'title' => 'Afficher',
                            'url' => ['controller' => 'Garanties', 'action' => 'view',$garanty->id]
                        ]);?>

                        <?=$this->Html->image("modifier-btn.png", [
                            'height' => '15', 'width' => '27',
                            "alt" => "Modifier",'title' => 'Modifier',
                            'url' => ['controller' => 'Garanties', 'action' => 'edit',$garanty->id]
                        ]);?>

                        <?= 
                            $this->Html->image(
                                "supprimer-btn.png",
                                ["alt" => "Supprimer", 'class' => 'action-link','title' => 'Supprimer','height' => '15', 'width' => '27',
                                'url' => ['controller' => 'Garanties', 'action' => 'delete',$garanty->id]

                           ]);
                        
                        ?>
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
        <p><?= $this->Paginator->counter(__('Page {{page}} sur {{pages}}, Affichage {{current}} enregsitrement(s) sur {{count}} au total')) ?></p>
    </div>
</div>
