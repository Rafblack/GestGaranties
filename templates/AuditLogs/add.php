<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Client> $auditLogs
 */
?>

<section class="content-header">
<h1 style = "color:green">Journal d'Audit(Saisi)</h1>
</section>

<div class="audit-logs index content">
    <div class="box">
    <div class="table-responsive">

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>NO.</th>
                    <th><?= $this->Paginator->sort('ID') ?></th>
                    <th><?= $this->Paginator->sort('Utilisateur') ?></th>
                    <th><?= $this->Paginator->sort('Action') ?></th>
                    <th><?= $this->Paginator->sort('Modèle') ?></th>
                    <th><?= $this->Paginator->sort('ID du Modèle') ?></th>
                    <th><?= $this->Paginator->sort('Champ') ?></th>
                    <th><?= $this->Paginator->sort('Ancienne Valeur') ?></th>
                    <th><?= $this->Paginator->sort('Nouvelle Valeur') ?></th>
                    <th><?= $this->Paginator->sort('Date') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($auditLogs as $log): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= h($log->id) ?></td>
                        <td><?= $this->Html->link((($USERS->get($log->user_id))->nom_prenom),["controller"=>"users", "action"=>"view",$log->user_id]) ?></td>
                        <td><?= h($log->action) ?></td>
                        <td><?= $this->Html->link(($log->model),["controller"=>$log->model, "action"=> "index"]) ?></td>
                        <?php if($log->action == 'delete'): ?>
                        <td><?= h($log->model_id) ?></td>
                        <?php else:  ?>
                        <td><?= $this->Html->link(($log->model_id),["controller"=>$log->model, "action"=>"view",$log->model_id]) ?></td>
                        <?php endif;?>                     
                        <td><?= h($log->field) ?></td>
                        <td><?= h($log->old_value) ?></td>
                        <td><?= h($log->new_value) ?></td>
                        <td><?= h($log->created) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} sur {{pages}}, Affichage {{current}} enregistrements sur {{count}} au total')) ?></p>
    </div>
</div>
