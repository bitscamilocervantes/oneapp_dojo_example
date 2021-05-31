<?php

namespace Drupal\oneapp_dojo_example\Services\v2_0;


use Drupal\oneapp\Exception\HttpException;


/**
 * Class PaperlessInfoRestLogic.
 */
class UsersRestLogic {

    /**
     * Block configuration.
     *
     * @var mixed
     */
    protected $configBlock;
  
    /**
     * Default configuration.
     *
     * @var mixed
     */
    protected $utils;
  
    /**
     * {@inheritdoc}
     */
    public function __construct($utils) {
      $this->utils = $utils;
    }
  
    /**
     * Responds to setConfig.
     *
     * @param mixed $configBlock
     *   Config card or default.
     */
    public function setConfig($configBlock) {
      $this->configBlock = $configBlock;
    }
  
    /**
     * Responds to GET requests.
     *
     * @param string $billingAccountId
     *   Msisdn.
     *
     * @return array
     *   The associative array.
     */
    public function get($page) {
      $rows = [];
      $config = [];
      $actions = [];
      $configLabels = $this->configBlock['data_labels'];
      $configParams = $this->configBlock['config_params'];
      $configButtons = $this->configBlock['buttons'];
      $users = $this->getUsers($page);
      
      foreach($users->data as $key=>$u){
        $rows['users'][$key]['email'] = [
          'label' => $configLabels['email'] ? $configLabels['email'] : '',
          'show' => $configLabels['email'] ? true : false,
          'value' => $u->email,
          'formattedValue' => $u->email 
        ];
  
        $rows['users'][$key]['first_name'] = [
          'label' => $configLabels['first_name'] ? $configLabels['first_name'] : '',
          'show' => $configLabels['first_name'] ? true : false,
          'value' => $u->first_name,
          'formattedValue' => $u->first_name 
        ];
  
        $rows['users'][$key]['last_name'] = [
          'label' => $configLabels['last_name'] ? $configLabels['last_name'] : '',
          'show' => $configLabels['last_name'] ? true : false,
          'value' => $u->last_name,
          'formattedValue' => $u->last_name 
        ];
  
        $rows['users'][$key]['avatar'] = [
          'label' => $configLabels['avatar'] ? $configLabels['avatar'] : '',
          'show' => $configLabels['avatar'] ? true : false,
          'value' => $u->avatar,
          'formattedValue' => $u->avatar 
        ];
      }

      $config['title'] = [
        'show' => $configParams['comunicationSuccessTitle']['value'] ? true : false,
        'value' => $configParams['comunicationSuccessTitle']['value'] ? $configParams['comunicationSuccessTitle']['value'] : '',
      ];

      $config['description'] = [
        'show' => $configParams['comunicationSuccessDescription']['value'] ? true : false,
        'value' => $configParams['comunicationSuccessDescription']['value'] ? $configParams['comunicationSuccessDescription']['value'] : '',
      ];

      foreach($configButtons as $key => $button){
        switch($key){
          case 'showStatsButton':
            $actions[$key] = [
              'label' => $button['label'],
              'show' => true,
              'url' => $button['url'],
              'type' => 'link'
            ];
            break;
        }
      }
      $config['actions'] = $actions;
        

      return [
        'data' => $rows,
        'config' => $config
      ];
    }

    
    

    /*
    * Get GET error data
    *
    */
    public function getErrorData(){
      $configParams = $this->configBlock['config_params'];
      $configButtons = $this->configBlock['buttons'];
      $rows = [];
      $config = [];


      $config['title'] = [
        'show' => $configParams['comunicationErrorTitle']['value'] ? true : false,
        'value' => $configParams['comunicationErrorTitle']['value'] ? $configParams['comunicationErrorTitle']['value'] : '',
    ];
      $config['message'] = [
        'show' => $configParams['comunicationErrorDescription']['value'] ? true : false,
        'value' => $configParams['comunicationErrorDescription']['value'] ? $configParams['comunicationErrorDescription']['value'] : '',
      ];

      return [
        'data' => $rows,
        'config' => $config
      ];
    }

    public function getUsers($page) {
      return \Drupal::service('oneapp_endpoint.manager')
        ->load('oneapp_dojo_example_v2_0_users_endpoint')
        ->setHeaders([])
        ->setQuery(['page' => $page])
        ->setParams([])
        ->sendRequest();
    }

  
  }
  