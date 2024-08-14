<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Garant> $garants
 */
?>
<section class="content-header">
<h1 style="color: red;">Liste - Garant(s) Supprimés</h1>
</section>

<div class="garants index content">
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
                    <th><?= $this->Paginator->sort('code_garant') ?></th>
                    <th><?= $this->Paginator->sort('code_rct') ?></th>
                    <th><?= $this->Paginator->sort('intitule_garant') ?></th>
                    <th><?= $this->Paginator->sort('Date de Suppresion') ?></th>

                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($garants as $garant): ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= h($garant->code_garant) ?></td>
                    <td><?= h($garant->code_rct) ?></td>
                    <td><?= h($garant->intitule_garant) ?></td>
                    <td><?= h($garant->del_at) ?></td>

                    <td class="actions">
                    <?=$this->Html->image("restorer1.png", [
                            'height' => '15', 'width' => '27',
                            "alt" => "Restorer",'title' => 'Restorer',
                            'url' => ['controller' => 'Garants', 'action' => 'restore',$garant->id]
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
        <p><?= $this->Paginator->counter(__('Page {{page}} sur {{pages}}, Affichage {{current}} enregistrement(s) sur {{count}} au total')) ?></p>
    </div>
</div>
