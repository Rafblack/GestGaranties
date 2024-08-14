<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 */
?>
<section class="content-header">
    <h1>Informations - Client(e)</h1>
</section>
<div class="content">
    <div class="box">
        <div class="box box-primary">
            <aside class="column align-right" style="text-align:right">
                <div class="side-nav">
                    <?= $this->Html->link(__(''), ['action' => 'index'], ['class' => 'fa fa-close','title' => 'Fermer et retouner Liste Clients']) ?>
                </div>
            </aside>
            <table class="table table-bordered table-hover">
                <tr>
                    <th><?= __('Code Client') ?></th>
                    <td><?= h($client->code) ?></td>
                </tr>
                <tr>
                    <th><?= __('Initulé') ?></th>
                    <td><?= h($client->label) ?></td>
                </tr>
                <tr>
                    <th><?= __('Code Rct') ?></th>
                    <td><?= h($client->code_rct) ?></td>
                </tr>
                <tr>
                    <th><?= __('Segment') ?></th>
                    <td><?= h($client->segment) ?></td>
                </tr>
                <tr>
                    <th><?= __('Agence') ?></th>
                    <td><?= $client->has('agence') ? $this->Html->link($client->agence->label, ['controller' => 'Agences', 'action' => 'view', $client->agence->id]) : '' ?></td>
                </tr>
                <?php if($client->modified_by != null): ?>
                    <tr>
                    <th><?= __('Date modifiée') ?></th>
                    <td><?= h($client->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modifiée par') ?></th>
                    <td><?= $this->Html->link(($USERS->get($client->modified_by))->nom_prenom,["controller"=> "users", "action"=> "view",$client->modified_by]) ?></td>
                    </tr>


                    <?php endif; ?>
                    <tr>
                    <th><?= __('Date crée') ?></th>
                    <td><?= h($client->created) ?></td>
                </tr>
                <tr>
                <th><?= __('crée par') ?></th>
                <td><?= $this->Html->link(($USERS->get($client->created_by))->nom_prenom,["controller"=> "users", "action"=> "view",$client->created_by]) ?></td>
                </tr>
                <?php if($client->restored_by != null): ?>
                    <tr>
                    <th><?= __('Date restorée') ?></th>
                    <td><?= h($client->date_r) ?></td>
                </tr>
                <tr>
                    <th><?= __('restorée par') ?></th>
                    <td><?= $this->Html->link(($USERS->get($client->restored_by))->nom_prenom,["controller"=> "users", "action"=> "view",$client->restored_by]) ?></td>
                    </tr>


                    <?php endif; ?>

            </table>
            <div class="box bow-primary">
                <h4><?= __('Garanties Rattachées') ?></h4>
                <?php if (!empty($client->garanties)) : ?>
                <div class="box">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th><?= __('Libelle Garantie') ?></th>
                            <th><?= __('Montant') ?></th>
                            <th><?= __('Statut') ?></th>

                            <th><?= __('Date Debut') ?></th>
                            <th><?= __('Date Fin') ?></th>
                            <th><?= __('Numero') ?></th>
                            <th><?= __('Reference') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php  foreach ($client->garanties as $garanties) : ?>
                        <tr>
                            <td><?= h($garanties->libelle_garantie) ?></td>
                            <td><?= h($garanties->montant) ?></td>
                            <td><?= h($STAT[$garanties->numero]) ?></td>

                            <td><?= h($garanties->date_debut) ?></td>
                            <td><?= h($garanties->date_fin) ?></td>
                            <td><?= h($garanties->numero) ?></td>
                            <td><?= h($garanties->reference) ?></td>
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
