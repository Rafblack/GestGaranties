<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Garanty $garanty
 * @var string[]|\Cake\Collection\CollectionInterface $typologies
 * @var string[]|\Cake\Collection\CollectionInterface $clients
 * @var string[]|\Cake\Collection\CollectionInterface $garants
 */
?>
<section class="content-header">
    <h1>Modification - Garantie</h1>
</section>
<div class="content">
    <div class="row">
        <div class="box">
            <div class="box-body">
                <aside class="column align-right" style="text-align:right">
                    <div class="side-nav">
                        <?= $this->Html->link(__(''), ['action' => 'index'], ['class' => 'fa fa-close','title' => 'Fermer et retouner Liste Garanties']) ?>
                    </div>
                </aside>
                <?= $this->Form->create($garanty,['type'=>'file']) ?>
                <fieldset>
                <table style="width: 100%;">
                        <tr>
                            <td style="vertical-align: top; width: 50%;">
                                <?php
                                echo $this->Form->control('libelle_garantie', ['class' => 'form-control']);
                                $currencies = [
                                    'GNF'=>'GNF',

                                    'USD' => 'USD',
                                    'EUR' => 'EUR',
                                    'XOF' => 'XOF',
                                ];
                                
                              
                                echo $this->Form->control('montant', ['class' => 'form-control','type' => 'number',"required"=> false]);
                            
                                echo $this->Form->control('currency', [
                                    'type' => 'select',
                                    'options' => $currencies,
                                    'empty' => 'Selectionnez la devise',
                                    'class' => 'form-control',
                                    'label'=> false,
                                    'required'=> true,
                                ]);
                                echo   $this->Form->control('description', ['class' => 'form-control']);



                                ?>
                                </td>
                             <td style="vertical-align: top; width: 50%;">

                                <?php

                                echo $this->Form->control('typologie_id', ['options' => $typologies, 'empty' => true, 'class' => 'form-control']);
                                echo $this->Form->control('portee', ['class' => 'form-control']);
                                $statuts_garantie  = array('1' => 'Encours', '2' => 'Levée', '3'=>'Echue');
                                echo $this->Form->control('status_id', ['options' => $statuts_garantie, 'empty' => true]);
                                echo $this->Form->control('descrip', [
                                    'label'=> "Raison/Description de Modification",
                                    'empty' => true,
                                    'maxlength' => 255,
                                ]);                              echo $this->Form->control('user', ['type'=> "hidden",  'value' => $_SESSION['user']]);
                                echo $this->Form->control('user_id', ['type'=> "hidden",  'value' => $_SESSION['user_id']]);

                                echo $this->Form->control('garantie_id', ['type'=> "hidden",  'value' => $given]);


 

                               




                                ?>
                            </td>
                            <td style="vertical-align: top; width: 50%;">
                                <?php
                                echo $this->Form->control('date_debut', ['class' => 'form-control']);

                                echo $this->Form->control('date_fin', ['class' => 'form-control']);
                                echo $this->Form->control('reference', ['class' => 'form-control']);
                                echo $this->Form->control('numero', ['class' => 'form-control','empty'=> true,'required' => true]);

                             
                                ?>
                            </td>

                            <td style="vertical-align: top; width: 50%;">
                                <?php

                            ?>
                     
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top; width: 50%;">
                                <?php
                                ?>
                                 <div id = 'label'>    
                                 </div> 
                            </td>
                        
                        </tr>
                    </table>
                   
                </fieldset>
                <div class="text-center">
                    
                    <?php
                      echo $this->Form->file('document[]', ['label' => 'Joindre Fichiers', 'multiple' => true, 'class' => 'form-control-file']);

                    echo $this->Form->button(__('Enregistrer')) ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>




