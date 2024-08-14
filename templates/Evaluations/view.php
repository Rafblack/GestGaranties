<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Evaluation $evaluation
 */
?>
<section class="content-header">
    <h1>Informations - Evaluation</h1>
</section>
<div class="content">
    <div class="box">
        <div class="box box-primary">
            <aside class="column align-right" style="text-align:right">
                <div class="side-nav">
                    <?= $this->Html->link(__(''), ['action' => 'index'], ['class' => 'fa fa-close','title' => 'Fermer et retouner Liste Evaluations']) ?>
                </div>
            </aside>
            <table class="table table-bordered table-hover">
                <tr>
                    <th><?= __('Frequence') ?></th>
                    <td><?= h($evaluation->frequence) ?></td>
                </tr>
                <tr>
                    <th><?= __('Evaluateur') ?></th>
                    <td><?= $evaluation->has('evaluateur') ? $this->Html->link($evaluation->evaluateur->nom_prenom, ['controller' => 'Evaluateurs', 'action' => 'view', $evaluation->evaluateur->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Numero Garantie') ?></th>
                    <td><?= $evaluation->has('garanty') ? $this->Html->link($evaluation->garanty->numero, ['controller' => 'Garanties', 'action' => 'view', $evaluation->garanty->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Valeur Garantie') ?></th>
                    <td><?= h((string)$evaluation->valeur_garantie . $evaluation->currency) ?></td>
                </tr>
                <tr>
                    <th><?= __('Date Debut') ?></th>
                    <td><?= h($evaluation->date_debut) ?></td>
                </tr>

                <tr>
                    <th><?= __('Date Fin') ?></th>
                    <td><?= h($evaluation->date_fin) ?></td>
                </tr>
                <?php if($evaluation->modified_by != null): ?>
                    <tr>
                    <th><?= __('Date modifiée') ?></th>
                    <td><?= h($evaluation->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modifiée par') ?></th>
                    <td><?= $this->Html->link(($USERS->get($evaluation->modified_by))->nom_prenom,["controller"=> "users", "action"=> "view",$evaluation->modified_by]) ?></td>
                </tr>


                    <?php endif; ?>
                    <tr>
                    <th><?= __('Date crée') ?></th>
                    <td><?= h($evaluation->created) ?></td>
                </tr>
                <tr>
                <th><?= __('crée par') ?></th>
                    <td><?= $this->Html->link(($USERS->get($evaluation->created_by))->nom_prenom,["controller"=> "users", "action"=> "view",$evaluation->created_by]) ?></td>
                </tr>
                <?php if($evaluation->restored_by != null): ?>
                    <tr>
                    <th><?= __('Date restorée') ?></th>
                    <td><?= h($evaluation->date_r) ?></td>
                </tr>
                <tr>
                    <th><?= __('restorée par') ?></th>
                    <td><?= $this->Html->link(($USERS->get($evaluation->restored_by))->nom_prenom,["controller"=> "users", "action"=> "view",$evaluation->restored_by]) ?></td>
                    </tr>


                    <?php endif; ?>

            </table>
        </div>
    </div>
</div>
