<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Typology> $typologies
 */
?>
<div class="typologies index content">
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
    <h3><?= __('TYPOLOGIES') ?></h3>
    <div class="box">
    <div class="table-responsive">

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>NO.</th>
                    <th><?= $this->Paginator->sort('label', ['label' =>'INTITULE']) ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1;?>
                <?php foreach ($typologies as $typology): ?>
                <tr>
                    <td><?=  h($i);++$i;?></td>
                    <td><?= h($typology->label) ?></td>
                    <td class="actions">
                        <?=$this->Html->image("afficher-btn.png", [
                         'height' => '15', 'width' => '27',
                        "alt" => "Modifier",'title' => 'Afficher',
                        'url' => ['controller' => 'Typologies', 'action' => 'view',$typology->id]
                        ]);?>

                        <?=$this->Html->image("modifier-btn.png", [
                            'height' => '15', 'width' => '27',
                            "alt" => "Modifier",'title' => 'Modifier',
                            'url' => ['controller' => 'Typologies', 'action' => 'edit',$typology->id]
                        ]);?>

                        <?= $this->Form->postLink(
                                $this->Html->image(
                                "supprimer-btn.png",
                                ["alt" => "Supprimer", 'class' => 'action-link','title' => 'Supprimer','height' => '15', 'width' => '27']
                                ),
                                ['controller' => 'Typologies', 'action' => 'delete', $typology->id],
                                   ['escape' => false, 'confirm' => __('Voulez-vous supprimer la typologie: << '.$typology->label.' >>?'.
                                   "(LES TYPOLOGIES NE SONT PAS RESTORABLE!)")]
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
            <?= $this->Paginator->prev('< ' . __('Precedent')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('Suivant') . ' >') ?>
            <?= $this->Paginator->last(__('Dernier') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} sur {{pages}}, Affichage {{current}} enregistrement(s) sur {{count}} au total')) ?></p>
    </div>
</div>
