<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Garant $garant
 */
?>

<section class="content-header">
    <h1>Nouveau(le) - Garant(e)</h1>
</section>
<div class="content">
    <div class="row">
        <div class="box primary-box">
            <div class="box-body">
                <?= $this->Form->create($garant) ?>
                <fieldset>
                    <table style="width: 100%;">
                        <tr>
                            <td style="vertical-align: top; width: 50%;">
                                <?php
                                echo $this->Form->control('code_garant', ['class' => 'form-control']);
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
                <div style = "width:50%">
                <?php 
                                                echo $this->Form->control('intitule_garant', ['label' => 'Intitulé Garant', 'class' => 'form-control']);
                                               echo $this->Form->button(__('Créer'), ['class' => 'btn btn-primary']) ?>
                </div>


                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
