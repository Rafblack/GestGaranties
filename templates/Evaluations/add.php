<div?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Evaluation $evaluation
 * @var \Cake\Collection\CollectionInterface|string[] $evaluateurs
 * @var \Cake\Collection\CollectionInterface|string[] $garanties
 */
?>

<section class="content-header">
    <h1>Nouvelle - Evaluation</h1>
</section>
<div class="content">
    <div class="row">
            <div class="box box-primary">
                <div class="box-body">
                    <?= $this->Form->create($evaluation) ?>
                    <fieldset>
                        <table style="width: 100%;">
                            <tr>
                            <td>
                                <?php
                            echo $this->Form->control('Numero', ['label'=> 'Numero Garanty','empty' => true, 'class' => 'form-control', 'id'=>'code_garantie','required'=>true]);
                                ?>

                                <div id = 'code'>

                                </div>

                              

                            
                                </td>
                                <td> 
                                    <div id ="libelle">


                                    </div>
                                    </td>
                            </tr>
                            <tr>
                                


                                    <td style="width: 50%;">

                                    <div id = 'message'>

</div>
                                    <?php
                                    $frequence= [
                                    'Annuel'=> 'Annuel',
                                    'Triennal'=> 'Triennal',




                                    ];
                                    echo $this->Form->control('frequence', ['id'=>'frequence','empty'=> true, 'required'=> true,'options' => $frequence,'class' => 'form-control']);
                                    echo $this->Form->control('valeur_garantie', ['class' => 'form-control','type' => 'number','maxlength' => 15],);
                                    $currencies = [
                                        'GNF'=>'GNF',
    
                                        'USD' => 'USD',
                                        'EUR' => 'EUR',
                                        'XOF' => 'XOF',
                                    ];
                                
                                    echo $this->Form->control('currency', [
                                        'type' => 'select',
                                        'options' => $currencies,
                                        'empty' => 'Selectionnez la devise',
                                        'class' => 'form-control',
                                        'label'=> false,
                                        'required'=> true,
                                    ]);
    
                                    ?>
                                </td>
                                <td style="width: 50%;">
                                    <?php
                                    echo $this->Form->control('evaluateur_id', ['options' => $evaluateurs, 'empty' => true, 'class' => 'form-control']);
                                    echo $this->Form->control('date_debut', ['id'=>'date_debut','class' => 'form-control']);
                                    ?>

                                    <div id = 'date'>
                    
                                    </div>
                                </td>
                                    
                            </tr>
                        </table>
                    </fieldset>
                    <div style = "width:45%">
                    <?php 

                    echo $this->Form->button(__('Créer'), ['class' => 'btn btn-primary']) ?>

                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
    </div>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>  
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js"
    integrity="sha256-c9vxcXyAG4paArQG3xk6DjyW/9aHxai2ef9RpMWO44A=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>

<script>
  $('#libell').empty();

  $('#message').empty();

  


$(document).ready(function() {
  
    document.querySelectorAll('input, select, textarea').forEach(function (element) {
            // Check if element has a value
            if (element.value) {
                // Set the initial value of the form field to the current input value
                element.setAttribute('value', element.value);
            }
        });



        window.onload= function() { 
      
      val1 = $('#code_garantie').val();
      garantie(val1);
      val2 = $('#date_debut').val();
      datefin(val2);  
};
    // $('#code-client').val(-0)
    
  



    $('#code_garantie').on('input change',function() {
        val = $('#code_garantie').val();
    garantie(val); // Call the function when the change event occurs

    })

    $('#date_debut, #frequence').on('input change',function() {
        val = $('#date_debut').val();
    datefin(val); // Call the function when the change event occurs

    })


    function datefin(val){

        $('#date').empty();

      // Parse the input date string into a Date object
       val = new Date(val);
    if($('#frequence').val()){
        if($('#frequence').val() == 'Annuel'){
    val.setFullYear(val.getFullYear() + 1);

        }
        else{
    
    // Add three more years to the updated date
    val.setFullYear(val.getFullYear() + 3);

        }
     dateString = val.toISOString().substring(0, 10);



        $('#date').append(`
<?php
echo $this->Form->control('Date_fin', [
    'label' => 'date_fin',
    'class' => 'form-control',
    'value'=> '${dateString}',
    'readonly' => true, // Make sure they cant edit
    'formnovalidate' => true  ,
   'required' => false ,

])
?>
`
)

    }
    else{

    }



    }

function garantie(val) {


var codeGarantie = val
console.log(codeGarantie);
if (codeGarantie) {
    $.ajax({
        url: '/AjaxAdd/fetchGarantie', 

        method: 'GET',
    data: {
        garantie: codeGarantie,
    },
        success: function(response) {
            $('#libelle').empty();
            $('#message').empty();
            $('#code').empty();


            if(response.success == true){
     
           
         
                $('#message').append(`
                 <div class="message success">
Garantie retrouves!
</div>

`
                )

             $('#libelle').append(`
<?php
echo $this->Form->control('libelle_garantie', [
    'label' => 'Libelle Garantie',
    'class' => 'form-control',
    'data-field' => 'libelle_garantie',
    'id' => 'intitule_garant',
    'value'=> '${response.label}',
    'readonly' => true, // Make sure they cant edit
    'formnovalidate' => true  ,
   'required' => false ,

]);

echo $this->Form->control('reference', [
    'label' => 'Document de reference',
    'class' => 'form-control',
    'value'=> '${response.ref}',
    'readonly' => true, // Make sure they cant edit
    'formnovalidate' => true  ,
   'required' => false ,

]);
echo $this->Form->control('date_fin', [
    'label' => 'Date_fin',
    'class' => 'form-control',
    'value'=> '${response.fin}',
    'readonly' => true, // Make sure they cant edit
    'formnovalidate' => true  ,
   'required' => false ,

]);

echo $this->Form->control('code-client', [
    'label' => 'Code Client',
    'class' => 'form-control',
    'value'=> '${response.client_code}',
    'readonly' => true, // Make sure they cant edit
    'formnovalidate' => true  ,
   'required' => false ,

]);

echo $this->Form->control('Code Garant', [
    'class' => 'form-control',
    'value'=> '${response.garant_code}',
    'readonly' => true, // Make sure they cant edit
    'formnovalidate' => true  ,
   'required' => false ,

]);
echo $this->Form->control('Montant', [
    'class' => 'form-control',
    'value'=> '${response.montant}',
    'readonly' => true, // Make sure they cant edit
    'formnovalidate' => true  ,
   'required' => false ,

]);

?>
`);
$('#code').append(`
<?php
echo $this->Form->control('Statut', [
    'class' => 'form-control',
    'value'=> '${response.statut}',
    'readonly' => true, // Make sure they cant edit
    'formnovalidate' => true  ,
   'required' => false ,

]);



echo $this->Form->control('date_debut', [
    'label' => 'Date_debut',
    'class' => 'form-control',
    'value'=> '${response.deb}',
    'readonly' => true, // Make sure they cant edit
    'formnovalidate' => true  ,
   'required' => false ,

]);



echo $this->Form->control('client', [
    'label' => 'Client',
    'class' => 'form-control',
    'value'=> '${response.client_nom}',
    'readonly' => true, // Make sure they cant edit
    'formnovalidate' => true  ,
   'required' => false ,

]);

echo $this->Form->control('Garant', [
    'class' => 'form-control',
    'value'=> '${response.garant_nom}',
    'readonly' => true, // Make sure they cant edit
    'formnovalidate' => true  ,
   'required' => false ,

]);

echo $this->Form->control('Typologie', [
    'class' => 'form-control',
    'value'=> '${response.typ}',
    'readonly' => true, // Make sure they cant edit
    'formnovalidate' => true  ,
   'required' => false ,

]);







?>
`)

$('#libelle').append(

`  
 <?php  
echo $this->Form->control('garantie_id', [   'type' => 'hidden',  'class' => 'form-control','readonly' => true,'required' => false, 
'formnovalidate' => true , 'value'=>'${response.id}' ]);


?>


`
)

            

           

}
else{  // no garant is found
    // console.log("frhvfehwe h")

$('#message').append(`
                 <div class="message warning">
Aucune Garantie avec ce code 
</div>
`)

$('#libelle').empty();

$('#libelle').append(`
<?php
echo $this->Form->control('garantie_id', [   'type' => 'hidden',  'class' => 'form-control','readonly' => true,'required' => false, 
'formnovalidate' => true , 'value'=>'no' ]);

  


?>



`
)

}
        },
        error: function() {
            alert('An error occurred while fetching client data.');
        }
    });
}



else {
}
};
});

</script>