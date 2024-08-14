<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Agence> $agences
 */
?>
<section class="content-header">
    <h1>Liste - Agences</h1>
</section>
<div class="agences index content">
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
    <h3><?= __('Agences') ?></h3>
    <div class="box">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>NO.</th>
                    <th><?= $this->Paginator->sort('code_agence',['label' =>'CODE']) ?></th>
                    <th><?= $this->Paginator->sort('label',['label' => 'NOM AGENCE']) ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <?php $i=1; ?>
            <tbody>
                <?php foreach ($agences as $agence): ?>
                <tr>
                    <td><?= $i?></td>
                    <td><?= h($agence->code_agence) ?></td>
                    <td><?= h($agence->label) ?></td>
                    <td class="actions" style="text-align: right">
                        <?=$this->Html->image("afficher-btn.png", [
                            'height' => '15', 'width' => '27',
                            "alt" => "Modifier",'title' => 'Afficher',
                            'url' => ['controller' => 'Agences', 'action' => 'view',$agence->id]
                        ]);?>

                        <?=$this->Html->image("modifier-btn.png", [
                            'height' => '15', 'width' => '27',
                            "alt" => "Modifier",'title' => 'Modifier',
                            'url' => ['controller' => 'Agences', 'action' => 'edit',$agence->id]
                        ]);?>

                        <?= $this->Form->postLink(
                            $this->Html->image(
                                "supprimer-btn.png",
                                ["alt" => "Supprimer", 'class' => 'action-link','title' => 'Supprimer','height' => '15', 'width' => '27']
                            ),
                            ['controller' => 'Agences', 'action' => 'delete', $agence->id],
                            ['escape' => false, 'confirm' => __('Voulez-vous supprimer Agence: << '.$agence->label.' >>?'
                            ."(LES AGENCES NE SONT PAS RESTORABLE!)")]
                        );
                        ?>
                    </td>
                </tr>
                <?php ++$i;
                      endforeach; ?>
            </tbody>
        </table>
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
