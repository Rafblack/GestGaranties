<?php



// controller for ajax request 

namespace App\Controller;
use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenTime;
use Cake\I18n\Time;
use Laminas\Diactoros\Response\JsonResponse;

 




class AjaxController extends AppController{

   public function getRealConversionRate($from_currency, $to_currency, $api_key) {
     // Check if conversion rates are already stored in session and not expired
     if (isset($_SESSION['conversion_rates'][$from_currency][$to_currency]) && $_SESSION['conversion_rates'][$from_currency][$to_currency]['expiry'] > time()) {
        return $_SESSION['conversion_rates'][$from_currency][$to_currency]['rate'];
    }

    // Fetch rate from API
    $url = "https://v6.exchangerate-api.com/v6/{$api_key}/pair/{$from_currency}/{$to_currency}";
    $response = file_get_contents($url);

    if (!$response) {
        return 1; // la plus par est en GNF donc sui sa marche plus cest mieux on converti jsut pas au lieu de crash le dashboard
    }

    $data = json_decode($response, true);

    if ($data['result'] !== 'success') {
        // echo 'API Error: ' . $data['error'];
        return 1;
    }

    // Extract conversion rate
    $conversion_rate = $data['conversion_rate'];

    $_SESSION['conversion_rates'][$from_currency][$to_currency] = [
        'rate' => $conversion_rate,
        'expiry' => time() + (7 * 24 * 3600), 
    ];
    

    return $conversion_rate;

    }
public function getConversionRate($currency){
    
  $ans =  $this->getRealConversionRate($currency,'GNF','c76dc39540571ee8a9452199');

return $ans;
}


    public function fetchbarchartData()
    {
    
            $year = $this->request->getQuery('year');
            // $endDate = $this->request->getQuery('endDate');
    
            //   debug( $selectedTimePeriod);
            // Get client data based on the selected time period
            $data = $this->getbarchartDataByTimePeriod($year);
            
            $this->response = $this->response->withType('application/json')
            ->withStringBody(json_encode($data));    //  debug($response);
            // Return the JSON response
            return $this->response;
        // }
    }
    public function getbarchartDataByTimePeriod($year){
    
    $this->loadModel("Garanties");
    $this->loadModel("Clients");
    $this->loadModel("Garants");
    
    $Garanties = $this->Garanties;
    $Clients = $this->Clients;
    $Garants = $this->Garants;  
    
    // $garantie = [];
    // $garants = [];
    // $clients =[];
    
    for ($month = 0; $month < 12; $month++) {
        // Define start and end dates for the current month
        $real = $month +1;
        $startDate = date('Y-m-01', strtotime("$year-$real-01"));
        $endDate = date('Y-m-t', strtotime("$year-$real-01"));
       
        $gar = $Garanties->find()
        ->where([
            // 'agence_id' => $agencyId,
            // 'created >=' => $startDate,
            'date_debut <=' => $endDate,
            'date_debut >=' => $startDate,
            'AND' => [
                'OR' => [
                    ['del' => false],
                    ['del_at >' => $startDate]
                ]   
            ]
            
    
        ]);
    
        $cli = $Clients->find()
        ->where([
            // 'agence_id' => $agencyId,
            // 'created >=' => $startDate,
            'created <=' => $endDate,
            'created >=' => $startDate,
            'AND' => [
                'OR' => [
                    ['del' => false],
                    ['del_at >' => $startDate]
                ]   
            ]
            
            
        ]);
        
        $clien = $Clients->find()
        ->where([
            // 'agence_id' => $agencyId,
            // 'created >=' => $startDate,
            'created <' => $endDate,
            'AND' => [
                'OR' => [
                    ['del' => false],
                    ['del_at >' => $startDate]
                ]   
            ]
            
            
        ]);
    
        $GAR = $Garants->find()
        ->where([
            // 'agence_id' => $agencyId,
            // 'created >=' => $startDate,
            'created <' => $endDate,
            'AND' => [
                'OR' => [
                    ['del' => false],
                    ['del_at >' => $startDate]
                ]   
            ]
            
        ]);
        $GARa = $Garants->find()
        ->where([
            // 'agence_id' => $agencyId,
            'created >=' => $startDate,
            'created <' => $endDate,
            'AND' => [
                'OR' => [
                    ['del' => false],
                    ['del_at >' => $startDate]
                ]   
            ]
            
        ]);
    
        $gara = $Garanties->find()
        ->where([
            // 'agence_id' => $agencyId,
            // 'created >=' => $startDate,
            'date_debut <' => $endDate,
            'AND' => [
                'OR' => [
                    ['del' => false],
                    ['del_at >' => $startDate]
                ]   
            ]
            
    
        ]);
    
        $deb = $Garanties->find()
        ->where([   
              // 'created >=' => $startDate,
              'date_debut <' => $endDate,
              'date_debut >' => $startDate,
              'AND' => [
                  'OR' => [
                      ['del' => false],
                      ['del_at >' => $startDate]
                  ]   
              ]
    
        ]);
    
        $fin = $Garanties->find()
        ->where([   
              // 'created >=' => $startDate,
              'date_fin <' => $endDate,
              'date_fin >' => $startDate,
              'AND' => [
                  'OR' => [
                      ['del' => false],
                      ['del_at >=' => $endDate]
                  ]   
              ]
    
        ]);
        $montant = 0;
        foreach( $deb as $d ) {
        $array_montant = explode(' ', $d->montant); 

            $currency = $d->currency;
            $rate = ($currency !== 'GNF') ? $this->getConversionRate($currency) : 1; // Default to 1 if currency is GNF
            $montant += $array_montant[0] * $rate;
  

        }
        $montantf =0;
        foreach( $fin as $f ) {
        $array_montant = explode(' ', $f->montant); 
        $currency = $f->currency;
        $rate = ($currency !== 'GNF') ? $this->getConversionRate($currency) : 1; // Default to 1 if currency is GNF
       $montantf += $array_montant[0] * $rate;
        }
    
        $garantie[$month] =  $gar->count();
        $garants[$month] =  $GAR->count();
        $new[$month] =  $cli->count();
        $clients[$month] = $clien->count();
        $newgarants[$month] = $GARa->count();
        $total[$month] = $gara->count();
        $debut[$month] = $deb->count();
        $fini[$month] = $fin->count();
        $montantarr[$month] =$montant;
        $montantfarr[$month] =$montantf;
    }
    
    return [$garantie,$garants, $clients,$new,$total,$newgarants,$debut,$fini,$montantarr,$montantfarr];  
    }
    public function fetchpiechartData()
    {
        // Check if the request is an AJAX request
        // if ($this->request->is('ajax')) {
            // Retrieve the selected time period from the request data
            $date = $this->request->getQuery('date');
            $endDate = $this->request->getQuery('endDate');
    
            //   debug( $selectedTimePeriod);
            // Get client data based on the selected time period
            $data = $this->getpieDataByTimePeriod($date);
            // debug($data);   
            // Create a JSON response with the fetched data
            $this->response = $this->response->withType('application/json')
            ->withStringBody(json_encode($data));    //  debug($response);
            // Return the JSON response
            return $this->response;
        // }
    }
    public function fetchClientData()
    {
        // Check if the request is an AJAX request
        // if ($this->request->is('ajax')) {
            // Retrieve the selected time period from the request data
            $startDate = $this->request->getQuery('startDate');
            $endDate = $this->request->getQuery('endDate');
    
            //   debug( $selectedTimePeriod);
            // Get client data based on the selected time period
            $data = $this->getClientDataByTimePeriod($startDate,$endDate);
            
            $this->response = $this->response->withType('application/json')
            ->withStringBody(json_encode($data));    //  debug($response);
            // Return the JSON response
            return $this->response;
        // }
    }
    public function fetchGarantieData()
    {
        // if ($this->request->is('ajax')) {
            $year = $this->request->getQuery('year');
    
            //   debug( $selectedTimePeriod);
            // Get client data based on the selected time period
            $data = $this->getGarantieDataByTimePeriod($year);
            
            $this->response = $this->response->withType('application/json')
            ->withStringBody(json_encode($data));    //  debug($response);
            // Return the JSON response
            return $this->response;
        // }
    }
    public function fetchevalData()
    {
        // if ($this->request->is('ajax')) {
            // Retrieve the selected time period from the request data
            $year = $this->request->getQuery('year');
            // debug($year);
    
            // Get client data based on the selected time period
            $data = $this->getevalDataByTimePeriod($year);
            
            $this->response = $this->response->withType('application/json')
            ->withStringBody(json_encode($data));    //  debug($response);
            // Return the JSON response
            return $this->response;
        // }
    }
    public function getevalDataByTimeperiod($year){
    
        $this->loadModel('Evaluations');
        $this->loadModel('Evaluateurs');
    
        $Evaluations = $this->Evaluations;
        // debug($guaranteesTable);    
        $Evaluators  = $this->Evaluateurs;
    
        for ($month = 0; $month < 12; $month++) {
            // Define start and end dates for the current month
            $real = $month +1;
            $startDate = date('Y-m-01', strtotime("$year-$real-01"));
            $endDate = date('Y-m-t', strtotime("$year-$real-01"));
        // debug($agencesTable);
        $current = $Evaluations->find()
        ->where([
            // 'agence_id' => $agencyId,
            // 'created >=' => $startDate,
            'date_debut <=' => $endDate,
            'date_debut >=' => $startDate,
            
        ]);
    
        $open = $Evaluations->find() // this is the date its ending
        ->where([   
         'date_fin <=' => $endDate,
         'date_fin >='   => $startDate,
    
    
        ]);
     
    
        $all = $Evaluators->find()
        ->where([
        'created <' => $endDate,
        
        'AND' => [
            'OR' => [
                ['del' => false],
                ['del_at >' => $startDate]
            ]
        ]
    
    
    
            ]);
       
        $allarr[$month] = $all->count();
        $currcount[$month] = $current->count();
        $openarr[$month] = $open->count();  
        
    
        $currgaranteed = 0;
        $allgaranteed =0;
        foreach($open as $A ){
            $array = explode(' ', $A->valeur_garantie);

            $currency = $A->currency;
            $rate = ($currency !== 'GNF') ? $this->getConversionRate($currency) : 1; // Default to 1 if currency is GNF
            $allgaranteed += (int)$array[0] * $rate;
        }
    
        foreach($current as $N){
            $array = explode(' ', $N->valeur_garantie);
            $currency = $N->currency;
            $rate = ($currency !== 'GNF') ? $this->getConversionRate($currency) : 1; // Default to 1 if currency is GNF
            $currgaranteed += (int)$array[0] * $rate;
        }
       
        $moneyarr[$month] =$currgaranteed;
        $endmoneyarr[$month] = $allgaranteed; 
    
    
    }
    
        return[
            'data' => [$allarr,$currcount,$openarr,$moneyarr,$endmoneyarr],
            // 'lables' => $lables
        ];
    
    
    
        
    }
        public function getGarantieDataByTimeperiod($year){
    
            $this->loadModel('Garanties');
            $this->loadModel('Typologies');
    
            $Garanties = $this->Garanties;
            // debug($guaranteesTable);    
            $Status  = $this->Typologies;
            // debug($agencesTable);
            $status = $Status->find('all')->toArray();
                    // debug($agences);
    
            $lables = [];
            $data = [];
            foreach ($status as $typ) {
                // debug($agency->id);
                $lables[]= $typ->label;
                $agencyData = $this->aggregateGarantieData($Garanties, $typ->id, $year );
                $data[] = $agencyData;
    
            }
            // debug($data);
           
    
    
            return[
                'data' => $data,
                'lables' => $lables
            ];
    
    
    
            
        }
        public function aggregateGarantieData($table, $typid, $year)
        {
            $dataPoints = []; // Initialize an array to hold data points for each month
            $stDate = date('Y-m-01', strtotime("$year-01-01"));
            $enDate = date('Y-m-t', strtotime("$year-12-01"));
            $pief = $table->find()
            ->where([
                'typologie_id' =>$typid,
                'date_fin >' => $stDate,
                'date_fin <' => $enDate,
                'AND' => [
                    'OR' => [
                        ['del' => false],
                        ['del_at >' => $enDate]
                    ]
                ]
    
              
                    ]);
                    $end = 0;
                    foreach ($pief as $row) {
                $array_montant = explode(' ', $row->montant); // Split the string by spaces
                $currency = $row->currency;
                $rate = ($currency !== 'GNF') ? $this->getConversionRate($currency) : 1; // Default to 1 if currency is GNF

                 $end += $array_montant[0] * $rate;
                    }
            $pie= $table->find()
            ->where([
                 'typologie_id' =>$typid,
                 'date_debut <' => $enDate,
                 'date_fin >' => $stDate,
                 'AND' => [
                     'OR' => [
                         ['del' => false],
                         ['del_at >' => $stDate]
                     ]
                 ]
    
               
                     ]);
    
                     $total = $pie->count();
                     $am = 0;
                    //  debug($total);
        
                     // Loop through query results to aggregate data
                     foreach($pie as $gar) {
                        $array_montant = explode(' ', $gar->montant); // Split the string by spaces
                        $currency = $gar->currency;
                        $rate = ($currency !== 'GNF') ? $this->getConversionRate($currency) : 1; // Default to 1 if currency is GNF
                         $am += $array_montant[0] * $rate;
                     }
             
            // Loop through each month of the year
            for ($month = 0; $month < 12; $month++) {
                // Define start and end dates for the current month
                $real = $month +1;
                $startDate = date('Y-m-01', strtotime("$year-$real-01"));
                $endDate = date('Y-m-t', strtotime("$year-$real-01"));
                $pieff = $table->find()
                ->where([
                    'typologie_id' =>$typid,
                    'date_fin >=' => $startDate,
                    'date_fin <=' => $endDate,
                    'AND' => [
                        'OR' => [
                            ['del' => false],
                            ['del_at >' => $endDate]
                        ]
                    ]
        
                  
                        ]);
                        $countfinish[$month ] =$pieff->count();
                        $haa =0;
                        foreach($pieff as $row) {
                            $array_montant = explode(' ', $row->montant); // Split the string by spaces
                            $currency = $row->currency;
                             $rate = ($currency !== 'GNF') ? $this->getConversionRate($currency) : 1; // Default to 1 if currency is GNF
                         $haa += $array_montant[0] * $rate; 
                        }
                        $countfinarr[$month] = $haa;
                // Query to fetch data for the current month
                $query = $table->find()
                    ->where([
                        'typologie_id' => $typid,
                        'date_debut >=' => $startDate,
                        'date_debut <=' => $endDate,
                        'AND' => [
                            'OR' => [
                                ['del' => false],
                                ['del_at >' => $startDate]
                            ]
                        ]
                    ]);
        
                // Initialize variables to hold aggregated data for the current month
                $countable_gar = $query->count();
                $amount = 0;
        
                // Loop through query results to aggregate data
                foreach ($query as $gar) {
                    $array_montant = explode(' ', $gar->montant); // Split the string by spaces
                    $currency = $gar->currency;
                    $rate = ($currency !== 'GNF') ? $this->getConversionRate($currency) : 1; // Default to 1 if currency is GNF
                    $amount += $array_montant[0] * $rate;
                }
        
                // Store aggregated data for the current month
                $dataPoints[$month] = [
                    'count' => $countable_gar,
                    'amount' => $amount
                ];
                $countarr[$month] = $countable_gar;
                $amarr[$month] = $amount;
            }
        
            return [  $countarr, $amarr,$total,$am,$countfinish,$pief->count(),$end,$countfinarr];
        }
        
        public function getClientDataByTimePeriod($startDate,$endDate)
        {
    
        
            $this->loadModel('Garanties');
            $this->loadModel('Agences');
            $this->loadModel('Clients');

    
            $Clientstable = $this->Clients;
            // debug($guaranteesTable);    
            $agencesTable = $this->Agences;
            // debug($agencesTable);
            $agences = $agencesTable->find('all')->toArray();
                    // debug($agences);
    
            $lables = [];
            $data = [];
            foreach ($agences as $agency) {
                // debug($agency->id);
                $lables[]= $agency->label;
                $agencyData = $this->aggregateClientData($Clientstable, $agency->id, $startDate, $endDate);
                $data[] = $agencyData;
    
            }
            // debug($data);
           
            return[
                'data' => $data,
                'lables' => $lables
            ];
        }
        public function getpieDataByTimePeriod($date)
        {
    
            // switch ($selectedTimePeriod) {
            //     case 'last_week':
            //         $startDate = $now->subWeek(1)->startOfWeek();
            //         $endDate = $now->subWeek(1)->endOfWeek();
            //         $interval = 'day';
            //         break;
            //     case 'last_month':
            //         $startDate = $now->subMonth(1)->startOfMonth();
            //         $endDate = $now->subMonth(1)->endOfMonth();
            //         $interval = 'day';
            //         break;
            //     case 'last_year':
            //         $startDate = $now->subYear(1)->startOfYear();
            //         $endDate = $now->subYear(1)->endOfYear();
            //         $interval = 'month';
            //         break;
            //     default:
            //         $startDate = $now->startOfDay();
            //         $endDate = $now;
            //         $interval = 'hour';
            //         break;
            // }
            $this->loadModel('Garanties');
            $this->loadModel('Status');

    
            $Garanties = $this->Garanties;
            // debug($guaranteesTable);    
            $Status = $this->Status;
            // debug($agencesTable);
            $status = $Status->find('all')->toArray();
                    // debug($agences);
    
            $lables = [];
            $data = [];
            foreach ($status as $stat) {
                // debug($agency->id);
                $lables[]= $stat->label;
                $agencyData = $this->aggregatepieData($Garanties, $stat->id, $date);
                $data[] = $agencyData;
    
            }
            // debug($data);
           
            return[
                'data' => $data,
                'lables' => $lables
            ];
        }
    
        private function aggregatepieData($table, $agencyId, $date)
        {
            // debug($endDate);
            // debug($startDate);
    
            $query = $table->find()
            ->where([
                'status_id' => $agencyId,
                'date_debut <=' => $date,
                // 'created <' => $endDate,
                'AND' => [
                    'OR' => [
                        ['del' => false],
                        ['del_at >' => $date]
                    ]
                ]
            ]);
        
        $countable_clients = $query->count();
      
    
    
             $amount = 0;
    
             foreach($query as $gar){
                $array_montant = explode(' ', $gar->montant); // Split the string by spaces
                $currency = $gar->currency;
                $rate = ($currency !== 'GNF') ? $this->getConversionRate($currency) : 1; // Default to 1 if currency is GNF
                
                $amount += $array_montant[0] * $rate;
                
             }
        // $countable_gar = $query1->count();  
            // debug($countable_clients);
            return [$countable_clients,$amount] ;
    
            // return $countable_clients ;   
        }
            // debug($countable_clients);
    
        
    
        private function aggregateClientData($table, $agencyId, $startDate, $endDate)
        {
            // debug($endDate);
            // debug($startDate);
    
            $query = $table->find()
            ->where([
                'agence_id' => $agencyId,
                'created >' => $startDate,
                'created <' => $endDate,
                'AND' => [
                    'OR' => [
                        ['del' => false],
                        ['del_at >' => $endDate]
                    ]
                ]
            ]);
        
        $countable_clients = $query->count();
        $query1 = $this->Garanties->find()
        ->where([
         'agence_id' => $agencyId,
         'date_debut <='=> $endDate,
         'date_debut >='=> $startDate,
    
         'AND' =>[
             'OR'=>[
             ['del' => false],
             ['del_at >' => $endDate]
    
             ]
    
    
    
         ]
    
    
            
    
             ]);
    
             $finished = $this->Garanties->find()
             ->where([  
                'agence_id' => $agencyId,
                'date_fin <'=> $endDate,
                 'date_fin >'=> $startDate,
    
                  'AND' =>[
                   'OR'=>[
                     ['del' => false],
                  ['del_at >' => $endDate]
    
             ]
                  ]
    
             ]);
    
    
             $complete = 0;
             foreach($finished as $test){
                $array_montant = explode(' ', $test->montant); // Split the string by spaces
                $currency = $test->currency;
                $rate = ($currency !== 'GNF') ? $this->getConversionRate($currency) : 1; // Default to 1 if currency is GNF

                $complete += $array_montant[0] * $rate;  
             }
    
    
             $amount = 0;
    
             foreach($query1 as $gar){
                $array_montant = explode(' ', $gar->montant); // Split the string by spaces
                $currency = $gar->currency;
                $rate = ($currency !== 'GNF') ? $this->getConversionRate($currency) : 1; // Default to 1 if currency is GNF

                $amount += $array_montant[0] * $rate;
                
             }
        $countable_gar = $query1->count();  
            // debug($countable_clients);
            return [$countable_clients, $countable_gar,$amount,$finished->count(0),$complete] ;
    
            // return $countable_clients ;   
        }
            // debug($countable_clients);
    
        }
    



