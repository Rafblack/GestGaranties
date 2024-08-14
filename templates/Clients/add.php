<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 * @var \Cake\Collection\CollectionInterface|string[] $agences
 */
?>


<section class="content-header">
    <h1>Nouveau(le) - Client(e)</h1>
</section>
<div class="content">
    <div class="row">
        <div class="box box-primary">
            <div class="box-body">
                <?= $this->Form->create($client) ?>
                <fieldset>
                    <table style="width: 100%;">
                        <tr>
                            <td style="vertical-align: top; width: 50%;">
                                <?php
                                echo $this->Form->control('code', ['label' => 'Code Client', 'class' => 'form-control']);
                                echo $this->Form->control('label', ['label' => 'Intitulé', 'class' => 'form-control']);
                                ?>
                            </td>
                            <td style="vertical-align: top; width: 50%;">
                                <?php
                                echo $this->Form->control('code_rct', ['class' => 'form-control']);
                                ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <?= $this->Form->button(__('Créer'), ['class' => 'btn btn-primary']) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
