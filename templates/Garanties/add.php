<div?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Garanty $garanty
 * @var \App\Model\Entity\Clients $client

 * @var \Cake\Collection\CollectionInterface|string[] $typologies
 

 * @var \Cake\Collection\CollectionInterface|string[] $garants
 */

?>
<section class="content-header">
    <h1>Nouvelle - Garantie</h1>
</section>
<div class="content">
    <div class="row">
        <div class="box box-primary">
            <div class="box-body">
                <?= $this->Form->create($garanty,['type'=>'file']) ?>

                <fieldset>
                    <table style="width: 100%;">
                        <tr>
                            <td style="vertical-align: top; width: 50%;">
                                <?php
                                echo $this->Form->control('libelle_garantie', ['class' => 'form-control']);

                                echo $this->Form->control('portee', ['class' => 'form-control']);
                                echo $this->Form->control('classement', ['class' => 'form-control','required' => false,'maxLength'=> 45]);

                             // echo $this->Form->control('garant_id', ['options' => $garants, 'empty' => true, 'class' => 'form-control']);

                                echo $this->Form->control('agence_id', [ 'id'=> 'agence','options' => $agences, 'empty' => true,'required'=> true, 'class' => 'form-control']);
                                
                               


                              

                                ?>
                                  <div id = 'message'></div>

                                </td>
                             <td style="vertical-align: top; width: 50%;">

                                <?php
                                     echo $this->Form->control('typologie_id', ['options' => $typologies,'required'=> true, 'empty' => true, 'class' => 'form-control']);

                                $currencies = [
                                    'GNF'=>'GNF',

                                    'USD' => 'USD',
                                    'EUR' => 'EUR',
                                    'XOF' => 'XOF',
                                ];
                                
                              
                                echo $this->Form->control('montant', ['class' => 'form-control','type' => 'number', ]);
                            
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
                                echo $this->Form->control('date_debut', ['class' => 'form-control']);

                                echo $this->Form->control('date_fin', ['class' => 'form-control']);
                                echo $this->Form->control('reference', ['label'=> 'document de reference','class' => 'form-control']);
                                echo $this->Form->control('numero', ['class' => 'form-control','empty'=> true,'required' => false]);
                                echo $this->Form->control('descrip', ["value"=> "placeholder","type"=>"hidden",'class' => 'form-control','empty'=> true,'required' => false]);
                                echo $this->Form->control('raison', ["value"=> "placeholder","type"=>"hidden",'class' => 'form-control','empty'=> true,'required' => false]);


                                 


                                ?>
                            </td>
                            </tr>


                            <tr>
                            <td style="vertical-align: top; width: 50%;">
                                <?php
                                echo $this->Form->control('code', ['label' => 'Code Client','data-field' => 'code-client', 'class' => 'form-control','id' => 'code-client','required' => true  ,'maxlength' => 9

                            ]);
                                //  echo $this->Form->control('label', ['label' => 'Intitulé', 'class' => 'form-control', 'data-field' => 'label', 'id' => 'label']) 
                                ?>
                                 <div id = 'label'>    
                                 </div> 
                            </td>
                            <td style="vertical-align: top; width: 50%;">
                                   <div id = 'code'>
                                    <?php
                                   echo $this->Form->control('client_id', [   'type' => 'hidden',  'class' => 'form-control','readonly' => true,'required' => false, 
                                   'formnovalidate' => true , 'value'=> 'wrong' ]);
                                   ?>
                                           </div>

                                           
                                
                                
                            </td>
                        </tr>

                        <tr>
                            <td style="vertical-align: top; width: 50%;">
                                <?php
                                echo $this->Form->control('code_garant', ['label' => 'Code Garant','data-field' => 'code-garant', 'class' => 'form-control',    'maxlength' => 9
,'id' => 'code-garant','required' => false  ,
                            ]);
                            echo $this->Form->control('cree', [ 'class' => 'form-control','type'=>'hidden', "value"=> $_SESSION['user']]);
                            echo $this->Form->control('cree_id', [ 'class' => 'form-control','type'=>'hidden', "value"=> $_SESSION['user_id']]);

                                //  echo $this->Form->control('label', ['label' => 'Intitulé', 'class' => 'form-control', 'data-field' => 'label', 'id' => 'label']) 
                                ?>
                                 <div id = 'garantlabel'>    
                                 </div> 
                            </td>
                            <td style="vertical-align: top; width: 50%;">
                                   <div id = 'garantcode'>
                                    <?php
                                   echo $this->Form->control('garant_id', [   'type' => 'hidden',  'class' => 'form-control','readonly' => true,'required' => false, 
                                   'formnovalidate' => true , 'value'=> 'wrong' ]);
                                   ?>
                                           </div>

                                           <div id = 'garantmessage'>




                                           </div>

                                           
                                
                                
                            </td>
                        </tr>
                        
                        

                       
                    </table>
                </fieldset>
                <div class="text-center">
                    <?php
                echo $this->Form->file('document[]', ['label' => 'Joindre Fichiers', 'multiple' => true, 'class' => 'form-control-file']);

                          
                         echo$this->Form->button(__('Créer'), ['class' => 'btn btn-primary']) ?>
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


  


$(document).ready(function() {
    window.onload= function() { 
        val = $('#code-client').val();
        client(val)
        val1 = $('#code-garant').val();
        garant(val1);
  
};
    document.querySelectorAll('input, select, textarea').forEach(function (element) {
            // Check if element has a value
            if (element.value) {
                // Set the initial value of the form field to the current input value
                element.setAttribute('value', element.value);
            }
        });

    // $('#code-client').val(-0)
    
  



    $('#code-client, #agence').on('input change',function() {
        val = $('#code-client').val();
    client(val); // Call the function when the change event occurs
});

$('#code-garant').on('input change',function() {
        val = $('#code-garant').val();
    garant(val); // Call the function when the change event occurs
});


function garant(val) {


var codeGarant = val
console.log(codeGarant);
if (codeGarant) {
    $.ajax({
        url: '/AjaxAdd/fetchGarant', 

        method: 'GET',
    data: {
        garant: codeGarant,
    },
        success: function(response) {
            $('#garantlabel').empty();
            $('#garantmessage').empty();

            if(response.success == true){
     
           
         
                $('#garantmessage').append(`
                 <div class="message success">
Garant retrouves!
</div>

`
                )

             $('#garantlabel').append(`
<?php
echo $this->Form->control('intitule_garant', [
    'label' => 'Intitulé',
    'class' => 'form-control',
    'data-field' => 'intitule_garant',
    'id' => 'intitule_garant',
    'value'=> '${response.label}',
    'readonly' => true, // Make sure they cant edit
    'formnovalidate' => true  ,
   'required' => false 

]);

?>
`);
$('#garantcode').empty();

$('#garantcode').append(

`  
 <?php   echo $this->Form->control('garant_code_rct', ['class' => 'form-control','data-field' => 'code_rct','id' => 'code_rct_garant', 'value' => '${response.code}',  'readonly' => true,'required' => true, 
'formnovalidate' => true    // Disable form validation

]);
echo $this->Form->control('garant_id', [   'type' => 'hidden',  'class' => 'form-control','readonly' => true,'required' => false, 
'formnovalidate' => true , 'value'=>'${response.id}' ]);


?>

`
)

            

           

}
else{  // no garant is found
    // console.log("frhvfehwe h")

$('#garantmessage').append(`
                 <div class="message warning">
Aucun Garant avec ce code veuillez creez un nouveaux
</div>
`)

$('#garantlabel').empty();
$('#garantcode').empty();

$('#garantlabel').append(`
<?php
echo $this->Form->control('intitule_garant', [
'label' => 'Intitulé',
    'class' => 'form-control',
    'data-field' => 'intitule_garant',
    'id' => 'intitule_garant',
    'required'=> true,
    'maxlength' => 45,

  

]);

?>
`);
$('#garantcode').empty();

$('#garantcode').append(

`  
 <?php   echo $this->Form->control('garant_code_rct', ['class' => 'form-control','required' => true,'data-field' => 'code_rct','id' => 'code_rct_garant',    'maxlength' => 9

 
]);

echo $this->Form->control('garant_id', [   'type' => 'hidden',  'class' => 'form-control','readonly' => true,'required' => false, 
'formnovalidate' => true , 'value'=> null ]);

?>
`
)

}
        },
        error: function() {
            alert('An error occurred while fetching client data.');
        }
    });
} else {
    $('[data-field="label"]').val('');
}
};




    function client(val) {


        var codeClient = val
        console.log(codeClient);
        if (codeClient) {
            $.ajax({
                url: '/AjaxAdd/fetchClient', 

                method: 'GET',
            data: {
                client: codeClient,
            },
                success: function(response) {
                    $('#label').empty();
                    $('#message').empty();

                    if(response.success == true){
                        agence = document.getElementById('agence')
                    console.log(agence.value)
                   console.log(response.agence)
                   
                    if(agence.value != response.agence){
                        $('#message').append(`
                         <div class="message error">
  Le client existe mais l'agence de correspond pas!
</div>

`
                        )
                        $('#code').empty();

                        $('#code').append(

                        
    `  
         <?php   echo $this->Form->control('code_rct', ['type'=>'hidden','class' => 'form-control','data-field' => 'code','id' => 'code_rct', 'value' => null,  'readonly' => true,'required' => false, 
    'formnovalidate' => true    // Disable form validation

]);
  echo $this->Form->control('client_id', [   'type' => 'hidden',  'class' => 'form-control','readonly' => true,'required' => false, 
    'formnovalidate' => true , 'value'=> 'wrong' ]);

    echo $this->Form->control('segment', [   'type' => 'hidden',  'class' => 'form-control','readonly' => true,'required' => false, 
    'formnovalidate' => true , 'value'=> null ]);

?>

  `
);


                    }
                    else{
                        $('#message').append(`
                         <div class="message success">
 Client retrouves!
</div>

`
                        )

                     $('#label').append(`
    <?php
        echo $this->Form->control('label', [
            'label' => 'Intitulé',
            'class' => 'form-control',
            'data-field' => 'label',
            'id' => 'label',
            'value'=> '${response.label}',
            'readonly' => true, // Make sure they cant edit
            'formnovalidate' => true  ,
           'required' => false ,
           'maxlength' => 45


        ]);

    ?>
`);
$('#code').empty();

$('#code').append(

    `  
         <?php   echo $this->Form->control('code_rct', ['class' => 'form-control','data-field' => 'code','id' => 'code_rct',    'maxlength' => 9
, 'value' => '${response.code}',  'readonly' => true,'required' => true, 
    'formnovalidate' => true    // Disable form validation

]);
  echo $this->Form->control('client_id', [   'type' => 'hidden',  'class' => 'form-control','readonly' => true,'required' => false, 
    'formnovalidate' => true , 'value'=>'${response.id}' ]);

    echo $this->Form->control('segment', [     'maxlength' => 45,
    'type' => 'text',  'class' => 'form-control','readonly' => true,'required' => false, 
    'formnovalidate' => true , 'value'=>'${response.segment}' ]);

?>

  `
)

                    }

                   

    }
    else{  // no client is found

        $('#message').append(`
                         <div class="message warning">
 Aucun Client avec ce code veuillez creez un nouveaux
</div>
`)

        $('#label').empty();
        $('#code').empty();
   
        $('#label').append(`
    <?php
        echo $this->Form->control('label', [
            'label' => 'Intitulé',
            'class' => 'form-control',
            'data-field' => 'label',
            'id' => 'label',
            'required'=> true,
          

        ]);

    ?>
`);
$('#code').empty();

$('#code').append(

    `  
         <?php   echo $this->Form->control('code_rct', ['class' => 'form-control','required' => true,'data-field' => 'code','id' => 'code_rct'
         
]);

echo $this->Form->control('client_id', [   'type' => 'hidden',  'class' => 'form-control','readonly' => true,'required' => false, 
'formnovalidate' => true , 'value'=> null ]);

echo $this->Form->control('segment', [
    'label' => 'Segment',
    'class' => 'form-control',
    'data-field' => 'segment',
    'id' => 'segment',
    'required'=> false,
  

]);


 
?>

  `
)

    }
                },
                error: function() {
                    alert('An error occurred while fetching client data.');
                }
            });
        } else {
            $('[data-field="label"]').val('');
        }
    };











});
</script>

