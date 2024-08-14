<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    public function valueExists($tableName, $field, $value)
    {
        // Get the table instance
        $table = TableRegistry::getTableLocator()->get($tableName);

        // Check if the value exists
        $exists = $table->find()
            ->where([$field => $value])
            ->count() > 0;

        return $exists;
    }

    public function increment($input) {  // function to increment a unique value that we are trying to restore
        $lastChar = substr($input, -1);
        $prefix = substr($input, 0, -1);
        
        if (is_numeric($lastChar)) {
            $incrementedChar = (int)$lastChar + 1;
        } elseif (ctype_alpha($lastChar)) {
            if ($lastChar === 'z') {
                $incrementedChar = 'a';
            } elseif ($lastChar === 'Z') {
                $incrementedChar = 'A';
            } else {
                $incrementedChar = chr(ord($lastChar) + 1);
            }
        } else {
            return $input; // If the last character is neither a digit nor a letter, return the input unchanged
        }
    
        return $prefix . $incrementedChar;
    }



    public function beforeFilter(\Cake\Event\EventInterface $event)   
    {
     
        $date = date('Y-m-d H:i:s');
          $this->loadModel('Garanties');
          $this->loadModel('Clients');

        $garanties =   $this->Garanties->find('all')->where([ 'date_fin <'=> $date,
            'status_id !='=> '2'
        ])->all();
        // $garanties = $this->paginate($query);
    // debug($garanties);
        foreach ($garanties as $garantie) {
          
          $garantie->status_id = 3; 
          $this->Garanties->save($garantie);

        

    }


    parent::beforeFilter($event);

    $controller = $this->request->getParam('controller');
    $action = $this->request->getParam('action');

    // List of controllers where only existence check is needed
    $controllersWithExistenceCheck = ['Agences', 'Typologies','Modifications'];
   if(in_array($action,['index','view','deleted'])){   // we will delete the referer everytime we index or view
   
    $this->request->getSession()->delete('referercopy');



   }
    // Check if current controller requires only existence check
    if (in_array($controller, $controllersWithExistenceCheck) && in_array($action,['view','edit'])) {
        // Check existence without deletion status
        $id = $this->request->getParam('pass.0');
        $table = TableRegistry::getTableLocator()->get($controller); // Get table object dynamically
        $entity = $table->findById($id)->first();
        if (!$entity) {
            $this->redirect('/error/notFound'); // Redirect to error page or handle accordingly
        }
        // Make the entity available to the action
        $this->set('entity', $entity);
    } else {
        // All other controllers (require existence and deletion status check)
        if (in_array($action, ['view','edit'])) {
            $id = $this->request->getParam('pass.0');
            $table = TableRegistry::getTableLocator()->get($controller); // Get table object dynamically
            $entity = $table->findByIdAndDel($id, 0)->first(); 
                 if( !$entity ) {

                $this->redirect('/error/notFound'); // Redirect to error page or handle accordingly
            }
            else{


            }
            // Make the entity available to the action
            $this->set('entity', $entity);
        }
        else if($action == 'delete' && $_SESSION["role"] != 'ADMIN'){  // lock deletion for non Admin users


            $this->Flash->error(__('Seul le ADMIN a accès à cette fonctionnalité'));

            return $this->redirect($this->referer());
            // Redirect to error page or handle accordingly



        }
    }
}



    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        // $this->App->addBehavior('Timestamp'); // To handle created and modified timestamps

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
		$this->viewBuilder()->setTheme('AdminLTE');
        $this->loadComponent('Authentication.Authentication');

        $this->loadComponent('Paginator');
        $this->setDefaultPaginatorConfig();


        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }
    protected function setDefaultPaginatorConfig()
    {
        $defaultOrder = ['created' => 'DESC']; // Default order by created timestamp descending

        // Modify default order conditionally
        if ($this->request->getParam('action') === 'deleted') {
            $defaultOrder = ['del_at' => 'DESC']; // Order by del_at descending if action is 'deleted'
        }

        // Set Paginator configuration
        $this->Paginator->setConfig([
            'order' => $defaultOrder,
        ]);
    }
    // In src/Controller/GuaranteesController.php
   
}


