<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Agence $agence
 */
?>

<section class="content-header">
    <h1>Nouvelle - Agence</h1>
</section>
<div class="content">
    <div class="row">
            <div class="box box-primary">
                <div class="box-body">
                    <?= $this->Form->create($agence) ?>
                    <fieldset>
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 50%;">
                                    <?php
                                    echo $this->Form->control('code_agence', ['class' => 'form-control']);
                                    ?>
                                </td>
                                <td style="width: 50%;">
                                    <?php
                                    echo $this->Form->control('label', ['label' => 'Nom', 'class' => 'form-control']);
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
