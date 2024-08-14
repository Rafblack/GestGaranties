<div?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<section class="content-header">
    <h1>Nouveau - Compte</h1>
</section>
<div class="content">
    <div class="row">
            <div class="box box-primary">
                <div class="box-body">
                    <aside class="column align-right" style="text-align:right">
                        <div class="side-nav">
                            <?= $this->Html->link(__(''), ['action' => 'index'], ['class' => 'fa fa-close','title' => 'Fermer et retourner à la liste des utilisateurs']) ?>
                        </div>
                    </aside>
                    <?= $this->Form->create($user) ?>
                    <fieldset>
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 50%;">
                                    <?php
                                    echo $this->Form->control('nom_prenom', ['label' => 'Nom & Prénom', 'class' => 'form-control']);
                                    echo $this->Form->control('email', ['class' => 'form-control']);
                                    ?>
                                </td>
                                <td style="width: 50%;">
                                    <?php
                                    $options = [

                                        'ADMIN'=> 'ADMIN',
                                        'USER'=> 'USER',
                                    ];
                                    echo $this->Form->control('fonction', ['class' => 'form-control','options'=> $options]);
                                    echo $this->Form->control('matricule', ['class' => 'form-control']);
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <div style = "width: 50%">
                    <?= $this->Form->control('password', ['class' => 'form-control']);?>

                    </div>
                    <?= $this->Form->button(__('Créer'), ['class' => 'btn btn-primary']) ?>
                    <?= $this->Form->end() ?>
                </div>
            </div>
    </div>
</div>
