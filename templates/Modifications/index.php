<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Modification> $modifications
 */
?>
<section class="content-header">
    <h1>Liste - Modifications</h1>
</section>
<div class="users index content">
<?php if (count($modifications->toArray()) > 0 ) :?>

    <?php $i=1; ?>
    <div class="box">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th><?= $this->Paginator->sort('user',['label' => 'Nom & Prénom']) ?></th>
                    <th><?= $this->Paginator->sort('Date') ?></th>
             
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($modifications as $modification): ?>
                <tr>
                    <td><?= $i; ?></td>
                    <td><?= $this->Html->link($modification->user,['controller'=>'Users', 'action'=> 'view', $modification->user_id]) ?></td>
                    <td><?= h($modification->modification_date) ?></td>
                
                    <td class="actions">
                        <?=$this->Html->image("afficher-btn.png", [
                            'height' => '15', 'width' => '27',
                            "alt" => "Afficher",'title' => 'Afficher',
                            'url' => ['controller' => 'Modifications', 'action' => 'view',$modification->id]
                        ]);?>

                   
                        
                    </td>
                </tr>
                <?php   $i +=1;endforeach;  ?>
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
    <?php else: ?>
        <h4>Pas de modifications</h4>

    <?php endif ?>
</div>
