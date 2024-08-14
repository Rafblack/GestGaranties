<div?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Evaluateur $evaluateur
 */
?>
<section class="content-header">
    <h1>Nouvel(le) - Evaluateur(trice)</h1>
</section>
<div class="content">
    <div class="row">
            <div class="box box-primary">
                <div class="box-body">
                    <?= $this->Form->create($evaluateur) ?>
                    <fieldset>
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 50%;">
                                    <?php
                                    echo $this->Form->control('nom_prenom', ['label' => 'Nom & Prénom', 'class' => 'form-control']);
                                    echo $this->Form->control('tel', ['label' => 'Téléphone', 'class' => 'form-control']);

                                    ?>
                                </td>
                                <td style="width: 50%;">
                                    <?php
                                    echo $this->Form->control('email', ['class' => 'form-control']);
                                    echo $this->Form->control('fonction_evaluateur', ['label' => 'Fonction', 'class' => 'form-control']);

                                    ?>

                                </td>

                                <td style="width: 50%;">
                                    <?php
                                     ?>
                                </td>

                            </tr>
                        </table>
                    </fieldset>
                    <div  style="width:50% ">

                    <?php    
                    
                 echo $this->Form->control('nom_structure', ['label' => 'Structure', 'class' => 'form-control']);
                 echo $this->Form->button(__('Créer'), ['class' => 'btn btn-primary']) ?>
                    <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
    </div>
</div>
