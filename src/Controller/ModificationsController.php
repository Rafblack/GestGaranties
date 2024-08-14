<?php
declare(strict_types=1);

namespace App\Controller;
use Authentication\PasswordHasher\DefaultPasswordHasher;

/**
 * Users Controller
 *
 * @property \App\Model\Table\Modfications $Modifications
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ModificationsController extends AppController
{

    public function index($id = null)
    {
      
        $query = $this->Modifications->find('all')->where([ 
            'garantie_id'=> $id,
        ]);

      $modifications=  $this->paginate($query);
        $this->set(compact('modifications'));

    }


    

    public function view($id = null)
    {
      
        $modification = $this->Modifications->get($id, [
            'contain' => [],
        ]);
        

      
        $this->set(compact('modification'));

    }



}