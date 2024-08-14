<?php 

// separate ajax controller that deals with the adding of clients and Garants through the Garantie page 



namespace App\Controller;
use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenTime;
use Cake\I18n\Time;
use Laminas\Diactoros\Response\JsonResponse;

 




class AjaxAddController extends AppController{
    public function fetchGarantie(){
        // $this->request->allowMethod(['ajax']);
        $this->autoRender = false;
        $garantiecode = $this->request->getQuery('garantie');
        $this->loadModel('Garanties');
        $this->loadModel('Clients');
        $this->loadModel('Status');
        $this->loadModel('Garants');
        $this->loadModel('Typologies');




       
        $garantie = $this->Garanties->find()->
        where(['numero'=> $garantiecode,
               'del' => 0,
    ]);
        
    $garantie = $garantie->first();
    


    
    if ($garantie) {
       $ref = $garantie->reference;
       $fin = $garantie->date_fin;
       $deb = $garantie->date_debut;
       $mon = $garantie->montant;

       
    $status = $this->Status->find()
    ->where(['id'=> $garantie->status_id]);

    $status = $status->first();


    $typologie = $this->Typologies->find()
    ->where(['id'=> $garantie->typologie_id]);

    $typologie = $typologie->first();



    $client = $this->Clients->find()
    ->where([   'id'=> $garantie->client_id]);

    $client = $client->first(); 

    $garant = $this->Garants->find()
    ->where(['id' => $garantie->garant_id])
    ->first();

    if($garant){
    $garant_nom = $garant->intitule_garant;
    $garant_code = $garant->code_garant;



    }
    else{
        $garant_nom = 'Sans Garant';
        $garant_code = 'Sans Garant';

    }

       if($ref == null){
        $ref = '0';
       }
        
        $response = [
            'success' => true,
                'label' => $garantie->libelle_garantie,
                'id' => $garantie->id,
                'ref'=> $ref,
                'fin'=> $fin,
                'deb'=>$deb,
                'statut'=> $status->label,
                'garant_nom'=> $garant_nom,
                'garant_code'=> $garant_code,
                'client_nom'=>$client->label,
                'client_code'=>$client->code,
                'typ'=>  $typologie->label,
                'montant'=> $mon,
                // Add other fields if necessary
        ];
    } else {
        $response = ['success' => false];
    }
    
    $this->response = $this->response->withType('application/json')
    ->withStringBody(json_encode($response));    //  debug($response);
    // Return the JSON response
    return $this->response;
    // }return;
    
    
    
    }

    public function fetchGarant(){
        // $this->request->allowMethod(['ajax']);
        $this->autoRender = false;
        $garantcode = $this->request->getQuery('garant');
        $this->loadModel('Garants');
       
        $garant = $this->Garants->find()->
        where(['code_garant'=> $garantcode,
               'del' => 0,
    ]);
        
    $garant = $garant->first();
    
    
    if ($garant) {
        
        $response = [
            'success' => true,
                'code' => $garant->code_rct,
                'label' => $garant->intitule_garant,
                'id' => $garant->id,
                // Add other fields if necessary
        ];
    } else {
        $response = ['success' => false];
    }
    
    $this->response = $this->response->withType('application/json')
    ->withStringBody(json_encode($response));    //  debug($response);
    // Return the JSON response
    return $this->response;
    // }return;
    
    
    
    }

public function fetchClient(){
    // $this->request->allowMethod(['ajax']);
    $this->autoRender = false;
    $clientcode = $this->request->getQuery('client');
    $this->loadModel('Clients');
   
    $client = $this->Clients->find()->
    where(['code'=> $clientcode,
           'del' => 0,
]);
    
$client = $client->first();


if ($client) {
    if($client->segment == null){
        $client->segment = '';
    }
    $response = [
        'success' => true,
            'code' => $client->code_rct,
            'label' => $client->label,
            'id' => $client->id,
            'agence'=> $client->agence_id,
            'segment'=> $client->segment,
            // Add other fields if necessary
    ];
} else {
    $response = ['success' => false];
}

$this->response = $this->response->withType('application/json')
->withStringBody(json_encode($response));    //  debug($response);
// Return the JSON response
return $this->response;
// }return;



}




}

