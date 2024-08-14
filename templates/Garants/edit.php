<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Garant $garant
 */
?>

<section class="content-header">
    <h1>Modification - Garant(e)</h1>
</section>
<div class="content">
    <div class="row">
        <div class="box">
            <div class="box-body">
                <aside class="column align-right" style="text-align:right">
                    <div class="side-nav">
                        <?= $this->Html->link(__(''), ['action' => 'index'], ['class' => 'fa fa-close', 'title' => 'Fermer et retourner à la liste des garants']) ?>
                    </div>
                </aside>
                <fieldset>
                    <?= $this->Form->create($garant) ?>
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
                    
                    <?php echo $this->Form->control('intitule_garant', ['label' => 'Intitulé Garant', 'class' => 'form-control']);

                    
                     echo  $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-primary']) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
