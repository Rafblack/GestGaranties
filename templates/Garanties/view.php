<div?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Garanty $garanty


 */
?>
<section class="content-header">
    <h1>Informations - Garanties</h1>
</section>
<div class="content">
    <div class="box">
        <div class="box box-primary">
            <aside class="column align-right" style="text-align:right">



                <div class="side-nav">
                    <?= $this->Html->link(__(''), ['action' => 'index'], ['class' => 'fa fa-close','title' => 'Fermer et retouner Liste Garanties']) ?>
                </div>

            </aside>
            <table class="table table-bordered table-hover" >
                <tr>
                    <th><?= __('Libelle Garantie') ?></th>
                    <td><?= h($garanty->libelle_garantie) ?></td>
                </tr>
                <tr>

                  <th><?= __('Créé Par') ?></th>
                  <td><?= $this->Html->link($garanty->cree, ['controller'=>'Users','action' => 'view', $garanty->cree_id]) ?></td>
                      </tr>
                      <tr>

                  <th><?= __('Date Créé') ?></th>
                  <td><?= h($garanty->created)?></td>
                      </tr>
                      <?php if($garanty->restored_by != null): ; ?>
                    <tr>
                    <th><?= __('Date restorée') ?></th>
                    <td><?= h($garanty->date_r) ?></td>
                </tr>
                <tr>
                    <th><?= __('restorée par') ?></th>
                    <td><?=  $this->Html->link(($USERS->get($garanty->restored_by))->nom_prenom,["controller"=> "users", "action"=> "view",$garanty->restored_by]) ?></td>
                    </tr>


                    <?php endif; ?>
                <tr>
                    <th><?= __('Portee') ?></th>
                    <td><?= h($garanty->portee) ?></td>
                </tr>
                <tr>
                    <th><?= __('Statut') ?></th>
                    <td><?= h($status) ?></td>
                </tr>
                <?php  if($garanty->status_id == 2  && $garanty->date_levee != null):?>
                <tr>
                    <th><?= __('Date levee') ?></th>
                    <td><?= h($garanty->date_levee) ?></td>
                </tr>
                <?php  endif;   ?>

                <tr>

                    <th><?= __('Numero') ?></th>
                    <td><?= $this->Html->link($garanty->numero, ['action' => 'documents', $garanty->id]) ?></td>
                    </tr>
                
                <tr>
                    <th><?= __('Reference') ?></th>
                    <td><?= h($garanty->reference) ?></td>
                </tr>
                <tr>
                    <th><?= __('Typologie') ?></th>
                    <td><?= $garanty->has('typology') ? $this->Html->link($garanty->typology->label, ['controller' => 'Typologies', 'action' => 'view', $garanty->typology->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Code Client') ?></th>
                    <td><?= $garanty->has('client') ? $this->Html->link($garanty->client->code, ['controller' => 'Clients', 'action' => 'view', $garanty->client->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Code Garant') ?></th>
                    <td><?= $garanty->has('garant') ? $this->Html->link($garanty->garant->code_garant, ['controller' => 'Garants', 'action' => 'view', $garanty->garant->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Montant') ?></th>
                    <td><?= h((string)$garanty->montant . $garanty->currency) ?></td>
                </tr>
                <tr>
                    <th><?= __('Date Debut') ?></th>
                    <td><?= h($garanty->date_debut) ?></td>
                </tr>
                <tr>
                    <th><?= __('Date Fin') ?></th>
                    <td><?= h($garanty->date_fin) ?></td>
                </tr>
                <tr>
                <th><?= __('Modifications') ?></th>

                    <td>
                    <?=$this->Html->image("mod.png", [
                            'height' => '20', 'width' => '30',
                            "alt" => "modification",'title' => 'modification',
                            'url' => ['controller' => 'Modifications', 'action' => 'index',$garanty->id]
                        ]);?>
                </td>
                </tr>
                <tr>
                <th><?= __('Fichiers') ?></th>

                    <td>   <?=$this->Html->image("fichier.jpeg", [
                            'height' => '20', 'width' => '30',
                            "alt" => "fichiers",'title' => 'fichiers',
                            'url' => ['controller' => 'Garanties', 'action' => 'documents',$garanty->id]
                        ]);?>
</td>
                
                
                </tr>

                </tr>
            </table>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($garanty->description)); ?>
                </blockquote>
            </div>
           
    </div>

     <h4>Evaluations</h4>
    <?php if (!empty($garanty->evaluations)) : ?>
                <div class="box">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th><?= __('Frequence') ?></th>
                            <th><?= __('Valeur Garantie') ?></th>
                            <th><?= __('Date Debut') ?></th>

                            <th><?= __('Date Fin') ?></th>
                            <th><?= __('Evaluateur') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($garanty->evaluations as $evaluations) : ?>
                        <tr>
                            <td><?= h($evaluations->frequence) ?></td>
                            <td><?= h($evaluations->valeur_garantie) ?></td>
                            <td><?= h($evaluations->date_debut) ?></td>

                            <td><?= h($evaluations->date_fin) ?></td>
                            <td><?= $this->Html->link($h[$evaluations->evaluateur_id],['controller'=>'Evaluateurs','action'=> 'view',$evaluations->evaluateur_id])   ?>

                                    <td>
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
