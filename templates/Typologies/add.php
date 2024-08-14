<div?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Typology $typology
 */
?>

<section class="content-header">
    <h1>Nouvelle - Typlogie</h1>
</section>
<section class="content">
        <div class="box box-primary">
            <div class="box-body">
                <?= $this->Form->create($typology) ?>
                <fieldset>
                    <div style ="width:50%">
                    <?php
                        echo $this->Form->control('label',['label' => 'Intitulé','class' => 'form-control with-border']);
                    ?>
                    </div>
                </fieldset>
                <?= $this->Form->button(__('Créer')) ?>
                <?= $this->Form->end() ?>
            </div>
    </div>
</section>
