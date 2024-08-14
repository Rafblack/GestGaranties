<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Garanty $garanty
 */
?>

<section class="content-header">
    <h1>Raisonnement de suppression - Garantie</h1>
</section>
    <div class="content">
                    <?= $this->Form->create($garantie) ?>
                    <fieldset>
                    <div style ="width:50%">
                    <?php
                        echo $this->Form->control('raison',["value"=> "",'label' => 'raisonnement ?']);
                    ?>
                    </div>
                    </fieldset>
                    <?= $this->Form->button(__('Enregistrer')) ?>
                    <?= $this->Form->end() ?>

    </div>
