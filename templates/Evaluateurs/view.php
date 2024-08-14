<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Evaluateur $evaluateur
 */
?>
<section class="content-header">
    <h1>Informations - Evaluateur(trice)</h1>
</section>
<div class="content">
    <div class="box">
        <div class="box box-primary">
            <aside class="column align-right" style="text-align:right">
                <div class="side-nav">
                    <?= $this->Html->link(__(''), ['action' => 'index'], ['class' => 'fa fa-close','title' => 'Fermer et retouner Liste Evaluateurs']) ?>
                </div>
            </aside>
            <table class="table table-bordered table-hover">
                <tr>
                    <th><?= __('Nom & Prenom') ?></th>
                    <td><?= h($evaluateur->nom_prenom) ?></td>
                </tr>
                <tr>
                    <th><?= __('Téléphone') ?></th>
                    <td><?= h($evaluateur->tel) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($evaluateur->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fonction ') ?></th>
                    <td><?= h($evaluateur->fonction_evaluateur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Structure') ?></th>
                    <td><?= h($evaluateur->nom_structure) ?></td>
                </tr>
                <?php if($evaluateur->modified_by != null): ?>
                    <tr>
                    <th><?= __('Date modifiée') ?></th>
                    <td><?= h($evaluateur->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modifiée par') ?></th>
                    <td><?= $this->Html->link(($USERS->get($evaluateur->modified_by))->nom_prenom,["controller"=> "users", "action"=> "view",$evaluateur->modified_by]) ?></td>
                    </tr>


                    <?php endif; ?>
                    <tr>
                    <th><?= __('Date crée') ?></th>
                    <td><?= h($evaluateur->created) ?></td>
                </tr>
                <tr>
                <th><?= __('crée par') ?></th>
                <td><?= $this->Html->link(($USERS->get($evaluateur->created_by))->nom_prenom,["controller"=> "users", "action"=> "view",$evaluateur->created_by]) ?></td>
                </tr>
                <?php if($evaluateur->restored_by != null): ?>
                    <tr>
                    <th><?= __('Date restorée') ?></th>
                    <td><?= h($evaluateur->date_r) ?></td>
                </tr>
                <tr>
                    <th><?= __('restorée par') ?></th>
                    <td><?= $this->Html->link(($USERS->get($evaluateur->restored_by))->nom_prenom,["controller"=> "users", "action"=> "view",$evaluateur->restored_by]) ?></td>
                    </tr>


                    <?php endif; ?>

            </table>
            <div class="related">
                <h4><?= __('Evaluations Rattachées') ?></h4>
                <?php if (!empty($evaluateur->evaluations)) : ?>
                <div class="box">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th><?= __('Frequence') ?></th>
                            <th><?= __('Valeur Garantie') ?></th>
                            <th><?= __('Date Fin') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($evaluateur->evaluations as $evaluations) : ?>
                        <tr>
                            <td><?= h($evaluations->frequence) ?></td>
                            <td><?= h($evaluations->valeur_garantie) ?></td>
                            <td><?= h($evaluations->date_fin) ?></td>
                            <td class="actions">
                                <?=$this->Html->image("afficher-btn.png", [
                                    'height' => '15', 'width' => '27',
                                    "alt" => "Modifier",'title' => 'Afficher',
                                    'url' => ['controller' => 'Evaluations', 'action' => 'view',$evaluations->id]
                                ]);?>

                                <?=$this->Html->image("modifier-btn.png", [
                                    'height' => '15', 'width' => '27',
                                    "alt" => "Modifier",'title' => 'Modifier',
                                    'url' => ['controller' => 'Evaluations', 'action' => 'edit',$evaluations->id]
                                ]);?>

                                <?= $this->Form->postLink(
                                    $this->Html->image(
                                        "supprimer-btn.png",
                                        ["alt" => "Supprimer", 'class' => 'action-link','title' => 'Supprimer','height' => '15', 'width' => '27']
                                    ),
                                    ['controller' => 'Evaluations', 'action' => 'delete', $evaluations->id],
                                    ['escape' => false, 'confirm' => __('Voulez-vous supprimer cette évaluation?', $evaluations->id)]
                                );
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
