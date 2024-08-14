<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Agence $agence
 */
?>
<section class="content-header">
    <h1>Informations - Agence</h1>
</section>
<div class="content">
    <div class="box">
        <div class="box box-primary">
            <table class="table table-bordered table-hover">
                <tr>
                    <th><?= __('Code Agence') ?></th>
                    <td><?= h($agence->code_agence) ?></td>
                </tr>
                <tr>
                    <th><?= __('Nom') ?></th>
                    <td><?= h($agence->label) ?></td>
                </tr>
                <?php if($agence->modified_by != null): ?>
                    <tr>
                    <th><?= __('Date modifiée') ?></th>
                    <td><?= h($agence->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modifiée par') ?></th>
                    <td><?= $this->Html->link(($USERS->get($agence->modified_by))->nom_prenom,["controller"=> "users", "action"=> "view",$agence->modified_by]) ?></td>
                    </tr>


                    <?php endif; ?>
                    <tr>
                    <th><?= __('Date crée') ?></th>
                    <td><?= h($agence->created) ?></td>
                </tr>
                <tr>
                <th><?= __('crée par') ?></th>
                <td><?= $this->Html->link(($USERS->get($agence->created_by))->nom_prenom,["controller"=> "users", "action"=> "view",$agence->created_by]) ?></td>
                </tr>





            </table>
            <div class="box bow-primary">
                <h4><?= __('Clients Rattachés') ?></h4>
                <?php if (!empty($agence->clients)) : ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th><?= __('Code Client') ?></th>
                            <th><?= __('Initulé') ?></th>
                            <th><?= __('Code Rct') ?></th>
                            <th><?= __('Segment') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($agence->clients as $clients) : ?>
                        <tr>
                            <td><?= h($clients->code) ?></td>
                            <td><?= h($clients->label) ?></td>
                            <td><?= h($clients->code_rct) ?></td>
                            <td><?= h($clients->segment) ?></td>
                            <td class="actions">
                                <?=$this->Html->image("afficher-btn.png", [
                                    'height' => '15', 'width' => '27',
                                    "alt" => "Modifier",'title' => 'Afficher',
                                    'url' => ['controller' => 'Clients', 'action' => 'view',$clients->id]
                                ]);?>

                                <?=$this->Html->image("modifier-btn.png", [
                                    'height' => '15', 'width' => '27',
                                    "alt" => "Modifier",'title' => 'Modifier',
                                    'url' => ['controller' => 'Clients', 'action' => 'edit',$clients->id]
                                ]);?>

                                <?= $this->Form->postLink(
                                    $this->Html->image(
                                        "supprimer-btn.png",
                                        ["alt" => "Supprimer", 'class' => 'action-link','title' => 'Supprimer','height' => '15', 'width' => '27']
                                    ),
                                    ['controller' => 'Clients', 'action' => 'delete', $clients->id],
                                    ['escape' => false, 'confirm' => __('Voulez-vous supprimer le client: << '.$clients->label.' >>?', $clients->id)]
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
