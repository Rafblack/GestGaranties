<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Client> $clients
 */
?>
<section class="content-header">
<h1 style="color: red;">Liste - Clients Supprimés</h1>
</section>
<div class="clients index content">
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
        <?php $i=1; ?>
        <div class="table-responsive">

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>NO.</th>
                    <th><?= $this->Paginator->sort('code',['label' => 'Code Client']) ?></th>
                    <th><?= $this->Paginator->sort('Date de Suppression') ?></th>

                    <th><?= $this->Paginator->sort('label', ['label' => 'Intitulé Client']) ?></th>
                    <th><?= $this->Paginator->sort('code_rct') ?></th>
                    <th><?= $this->Paginator->sort('segment') ?></th>
                    <th><?= $this->Paginator->sort('agence_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= h($client->code) ?></td>
                    <td><?= h($client->del_at) ?></td>

                    <td><?= h($client->label) ?></td>
                    <td><?= h($client->code_rct) ?></td>
                    <td><?= h($client->segment) ?></td>
                    <td><?= $client->has('agence') ? $this->Html->link($client->agence->label, ['controller' => 'Agences', 'action' => 'view', $client->agence->id]) : '' ?></td>
                    <td class="actions">
                
                    <?=$this->Html->image("restorer1.png", [
                            'height' => '15', 'width' => '27',
                            "alt" => "Restorer",'title' => 'Restorer',
                            'url' => ['controller' => 'Clients', 'action' => 'restore',$client->id]
                        ]);?>

                        
                    </td>
                </tr>
                <?php ++$i; endforeach; ?>
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
