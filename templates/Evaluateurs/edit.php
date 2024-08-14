<div?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Evaluateur $evaluateur
 */
?>

<section class="content-header">
    <h1>Modification - Evaluateur(trice)</h1>
</section>
<div class="content">
    <div class="row">
            <div class="box box-primary">
                <div class="box-body">
                    <aside class="column align-right" style="text-align:right">
                        <div class="side-nav">
                            <?= $this->Html->link(__(''), ['action' => 'index'], ['class' => 'fa fa-close','title' => 'Fermer et retourner à la liste des évaluateurs']) ?>
                        </div>
                    </aside>
                    <?= $this->Form->create($evaluateur) ?>
                    <fieldset>
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 50%;">
                                    <?php
                                    echo $this->Form->control('nom_prenom', ['class' => 'form-control']);
                                    echo $this->Form->control('tel', ['label' => 'Téléphone', 'class' => 'form-control']);
                                    ?>
                                </td>
                                <td style="width: 50%;">
                                    <?php
                                    echo $this->Form->control('email', ['class' => 'form-control']);
                                    echo $this->Form->control('fonction_evaluateur', ['class' => 'form-control']);
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <div style = "width: 50%">
                    <?php  echo $this->Form->control('nom_structure', ['class' => 'form-control']);
                          echo $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-primary']) ?>
                    <?= $this->Form->end() ?>

                    </div>
                </div>
            </div>
    </div>
</div>
