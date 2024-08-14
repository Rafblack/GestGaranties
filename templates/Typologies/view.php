<div?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Typology $typology
 */
?>
<section class="content-header">
    <h1>Informations - Typologie</h1>
</section>
<div class="content">
    <div class="box">
        <div class="box box-primary">
            <table class="table table-bordered table-hover">
                <tr>
                    <th><?= __('Intitulé') ?></th>
                    <td><?= h($typology->label) ?></td>
                </tr>
                <?php if($typology->modified_by != null): ?>
                    <tr>
                    <th><?= __('Date modifiée') ?></th>
                    <td><?= h($typology->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modifiée par') ?></th>
                    <td><?= $this->Html->link(($USERS->get($typology->modified_by))->nom_prenom,["controller"=> "users", "action"=> "view",$typology->modified_by]) ?></td>
                    </tr>


                    <?php endif; ?>
                    <tr>
                    <th><?= __('Date crée') ?></th>
                    <td><?= h($typology->created) ?></td>
                </tr>
                <tr>
                <th><?= __('crée par') ?></th>
                <td><?= $this->Html->link(($USERS->get($typology->created_by))->nom_prenom,["controller"=> "users", "action"=> "view",$typology->created_by]) ?></td>
                </tr>

              
            </table>
       
        <?php if ($garanties->count() > 0) : ?>
            <h4><?= __('Garanties Rattachés') ?></h4>

        <?php $i =1; ?>
        <div class="box">
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
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($garanties as $garanty): ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= h($garanty->libelle_garantie) ?>
                    <td><?= h($garanty->montant) ?></td>
                    <td><?= h($garanty->date_debut) ?></td>
                    <td><?= h($garanty->date_fin) ?></td>
                    <td><?= h($garanty->numero) ?></td>
                    <td><?= h($garanty->reference) ?></td>
                    <td><?= h($STAT[$garanty->numero]) ?></td>
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

                        <?= $this->Form->postLink(
                            $this->Html->image(
                                "supprimer-btn.png",
                                ["alt" => "Supprimer", 'class' => 'action-link','title' => 'Supprimer','height' => '15', 'width' => '27']
                            ),
                            ['controller' => 'Garanties', 'action' => 'delete', $garanty->id],
                            ['escape' => false, 'confirm' => __('Voulez-vous supprimer la garantie: << '.$garanty->numero.' >>?', $garanty->id)]
                        );
                        ?>
                    </td>
                </tr>
                <?php ++$i;endforeach; ?>
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
        <p><?= $this->Paginator->counter(__('Page {{page}} sur {{pages}}, Affichage {{current}} enregsitrement(s) sur {{count}} au total')) ?></p>
    </div>

  
    <?php else: ?>
        <h4><?= __('Aucune garantie n\'a été ajoutée') ?></h4>


    </div>

    <?php endif; ?>

</div>
</div>
</div>
