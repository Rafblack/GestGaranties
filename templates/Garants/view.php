<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Garant $garant
 */
?>
<section class="content-header">
    <h1>Informations - Garant(e)</h1>
</section>
<div class="content">
    <div class="box">
        <div class="box box-primary">
            <table class="table table-bordered table-hover">
                <tr>
                    <th><?= __('Code Garant') ?></th>
                    <td><?= h($garant->code_garant) ?></td>
                </tr>
                <tr>
                    <th><?= __('Code Rct') ?></th>
                    <td><?= h($garant->code_rct) ?></td>
                </tr>
                <tr>
                    <th><?= __('Intitule Garant') ?></th>
                    <td><?= h($garant->intitule_garant) ?></td>
                </tr>
                <?php if($garant->modified_by != null): ?>
                    <tr>
                    <th><?= __('Date modifiée') ?></th>
                    <td><?= h($garant->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modifiée par') ?></th>
                    <td><?= $this->Html->link(($USERS->get($garant->modified_by))->nom_prenom,["controller"=> "users", "action"=> "view",$garant->modified_by]) ?></td>
                    </tr>


                    <?php endif; ?>
                    <tr>
                    <th><?= __('Date crée') ?></th>
                    <td><?= h($garant->created) ?></td>
                </tr>
                <tr>
                <th><?= __('crée par') ?></th>
                <td><?= $this->Html->link(($USERS->get($garant->created_by))->nom_prenom,["controller"=> "users", "action"=> "view",$garant->created_by]) ?></td>
                </tr>
                <?php if($garant->restored_by != null): ?>
                    <tr>
                    <th><?= __('Date restorée') ?></th>
                    <td><?= h($garant->date_r) ?></td>
                </tr>
                <tr>
                    <th><?= __('restorée par') ?></th>
                    <td><?= $this->Html->link(($USERS->get($garant->restored_by))->nom_prenom,["controller"=> "users", "action"=> "view",$garant->restored_by]) ?></td>
                    </tr>


                    <?php endif; ?>

            </table>
            <div class="related">
                <h4><?= __('Garanties Rattachées') ?></h4>
                <?php if (!empty($garant->garanties)) : ?>
                <div class="box">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th><?= __('Libelle Garantie') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Montant') ?></th>
                            <th><?= __('Date Debut') ?></th>
                            <th><?= __('Date Fin') ?></th>
                            <th><?= __('Portee') ?></th>
                            <th><?= __('Numero') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($garant->garanties as $garanties) : ?>
                        <tr>
                            <td><?= h($garanties->libelle_garantie) ?></td>
                            <td><?= h($garanties->description) ?></td>
                            <td><?= h($garanties->montant) ?></td>
                            <td><?= h($garanties->date_debut) ?></td>
                            <td><?= h($garanties->date_fin) ?></td>
                            <td><?= h($garanties->portee) ?></td>
                            <td><?= h($garanties->numero) ?></td>
                            <td class="actions">
                                <?=$this->Html->image("afficher-btn.png", [
                                    'height' => '15', 'width' => '27',
                                    "alt" => "Modifier",'title' => 'Afficher',
                                    'url' => ['controller' => 'Garanties', 'action' => 'view',$garanties->id]
                                ]);?>

                                <?=$this->Html->image("modifier-btn.png", [
                                    'height' => '15', 'width' => '27',
                                    "alt" => "Modifier",'title' => 'Modifier',
                                    'url' => ['controller' => 'Garanties', 'action' => 'edit',$garanties->id]
                                ]);?>

                                <?=   $this->Html->image(
                                "supprimer-btn.png",
                                ["alt" => "Supprimer", 'class' => 'action-link','title' => 'Supprimer','height' => '15', 'width' => '27',
                                'url' => ['controller' => 'Garanties', 'action' => 'delete',$garanties->id]

                           ]);
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
