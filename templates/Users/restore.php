

<div style="text-align: center;">
    <h3>Confirmation</h3>



    <div?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Evaluateur $evaluateur
 */
?>

<section class="content-header">
    <h1>Restoration - Utilisateur</h1>
</section>
<div class="content">
    <div class="row">
            <div class="box box-primary">
                <div class="box-body">
                    <aside class="column align-right" style="text-align:right">
                        <div class="side-nav">
                            <?= $this->Html->link(__(''), ['action' => 'deleted'], ['class' => 'fa fa-close','title' => 'Fermer et retourner à la liste des évaluateurs supprimer']) ?>
                        </div>
                    </aside>
                    <?= $this->Form->create($user) ?>
                    <fieldset>
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 50%;">
                                <div id = "email">
                       <?php if($email == null):?>
                          <?= $this->Form->control('email', ['class' => 'form-control',"required"=>true,"empty"=>"true", "label"=> "email plus disponible entrez un nouveau", "value"=> ""]);?>




            <?php else :?>

                <?= $this->Form->control('email', ['class' => 'form-control', "readonly"=> true, "value"=> $email]);?>


                <?php endif ;?>
        </div>
                                </td>
                                <td style="width: 50%;">
                                <div id = "tel">
                    <?php if($mat == null):?>

                     <?= $this->Form->control('matricule', [ 'class' => 'form-control',"label"=> "telephone plus disponible entrez un nouveau", "required"=> true, "value"=>""])?>



              <?php else :?>

                     <?= $this->Form->control('matricule', ['label' => 'Téléphone', 'class' => 'form-control',"readonly"=> true, "value"=> $mat])?>


    <?php endif ;?>


   </div>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <div style = "width: 50% text: center" >
                          <?= $this->Form->button(__('Restorer'), ['class' => 'btn btn-primary']) ?>
                    <?= $this->Form->end() ?>


                    <p>Si la case est modifiable, c'est parce que la valeur a été attribuée à un(e) autre utilisateur.</p>
                    </div>
                </div>
            </div>
    </div>
</div>
    