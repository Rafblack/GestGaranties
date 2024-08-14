<?php 

namespace App\Controller;

use App\Controller\AppController;

class AuditLogsController extends AppController
{
    public function index()
    {
          // Set pagination options
          $this->paginate = [
            'limit' => 20, // Number of records per page
            'order' => [
                'created' => 'desc' // Afin ue ca montre les changements les plus recents
            ]
        ];

        // Fetch paginated audit logs
        $auditLogs = $this->paginate($this->AuditLogs->find('all')->where(["action" => "update"]));

        // Set the audit logs to be used in the view
        $this->loadModel('Users');    // to get the users nom_prenom

        $USERS = $this->Users;

        $this->set(compact('USERS'));

        $this->set(compact('auditLogs'));
    }

    public function delete()
    {
          // Set pagination options
          $this->paginate = [
            'limit' => 20, // Number of records per page
            'order' => [
                'created' => 'desc' // Afin ue ca montre les changements les plus recents
            ]
        ];

        // Fetch paginated audit logs
        $auditLogs = $this->paginate(
            $this->AuditLogs->find('all')->where([
                'OR' => [
                    ["action" => "delete"],
                    ["action" => "restore"]
                ]
            ])
        );
        // Set the audit logs to be used in the view
        $this->loadModel('Users');    // to get the users nom_prenom

        $USERS = $this->Users;

        $this->set(compact('USERS'));

        $this->set(compact('auditLogs'));
    }
    public function add()
    {
          // Set pagination options
          $this->paginate = [
            'limit' => 20, // Number of records per page
            'order' => [
                'created' => 'desc' // Afin ue ca montre les changements les plus recents
            ]
        ];

        // Fetch paginated audit logs
        $auditLogs = $this->paginate($this->AuditLogs->find('all')->where(["action" => "create"]));

        // Set the audit logs to be used in the view
        $this->loadModel('Users');    // to get the users nom_prenom

        $USERS = $this->Users;

        $this->set(compact('USERS'));

        $this->set(compact('auditLogs'));
    }

    
    

}
