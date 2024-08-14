<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Typology $typology
 */
?>

<section class="content-header">
    <h1>Modification - Typologie</h1>
</section>
    <div class="content">
                    <?= $this->Form->create($typology) ?>
                    <fieldset>
                    <div style ="width:50%">
                    <?php
                        echo $this->Form->control('label',['label' => 'Intitulé']);
                    ?>
                    </div>
                    </fieldset>
                    <?= $this->Form->button(__('Enregistrer')) ?>
                    <?= $this->Form->end() ?>

    </div>
