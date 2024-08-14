<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 * @var string[]|\Cake\Collection\CollectionInterface $agences
 */
?>

<section class="content-header">
    <h1>Modification - Client(e)</h1>
</section>
<div class="content">
    <div class="row">
        <div class="box">
            <div class="box-body">
                <aside class="column align-right" style="text-align:right">
                    <div class="side-nav">
                        <?= $this->Html->link(__(''), ['action' => 'index'], ['class' => 'fa fa-close','title' => 'Fermer et retourner à la liste des clients']) ?>
                    </div>
                </aside>
                <?= $this->Form->create($client) ?>
                <fieldset>
                    <table style="width: 100%;">
                        <tr>
                            <td style="vertical-align: top; width: 50%;">
                                <?php
                                echo $this->Form->control('code', ['class' => 'form-control']);
                                echo $this->Form->control('label', ['label' => 'Intitulé', 'class' => 'form-control']);
                                echo $this->Form->control('segment', ['class' => 'form-control']);
                                ?>
                            </td>
                            <td style="vertical-align: top; width: 50%;">
                                <?php
                                echo $this->Form->control('code_rct', ['class' => 'form-control']);
                                echo $this->Form->control('agence_id', ['options' => $agences, 'empty' => true, 'class' => 'form-control']);
                                ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-primary']) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
