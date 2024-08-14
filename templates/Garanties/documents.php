
<section class="content-header">
    <h1>Fichiers</h1>
</section>
<div class="content">
    <div class="box">
<table  class="table-responsive">
    <tr>
        <th>Fichiers</th>
        <th>Actions</th>
    </tr>
            <?php if (!empty($garantie->documents)): ?>
                <?php 
                    $documents = json_decode($garantie->documents, true);
                    foreach ($documents as $document): 
                ?>
                <tr>
                   
                <td> <?= $this->Html->link($document, ['action' => 'viewDocument', $garantie->id, $document]) ?>   </td> 
                   <td><?php echo $this->Form->postLink(
                            $this->Html->image(
                                "supprimer-btn.png",
                                ["alt" => "Supprimer", 'class' => 'action-link','title' => 'Supprimer','height' => '15', 'width' => '27']
                            ),
                            ['controller' => 'Garanties', 'action' => 'deleteDocument', $garantie->id,$document],
                            ['escape' => false, 'confirm' => __('Voulez-vous supprimer le fichier: << '.$document.' >>?')]
                        );
                        
                        
                        ?></td>

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <h4>Aucun fichier téléchargé</h4>
            <?php endif; ?>

</table>

</div>
</div>


