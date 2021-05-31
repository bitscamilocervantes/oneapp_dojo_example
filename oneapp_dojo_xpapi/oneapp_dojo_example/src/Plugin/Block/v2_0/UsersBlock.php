<?php

namespace Drupal\oneapp_dojo_example\Plugin\Block\v2_0;

use Drupal\adf_block_config\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'UsersBlock' block.
 *
 * @Block(
 *  id = "oneapp_dojo_example_v2_0_users_block",
 *  admin_label = @Translation("Oneapp Dojo Example - (Users List Block 2.0)")
 * )
 */
class UsersBlock extends BlockBase {

  /**
   * Content Fields.
   *
   * @var mixed
   */
  protected $contentFields;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {    
      $this->contentFields = [
        'data_labels' => [
           'email' => 'Email',
           'first_name' => 'Primer nombre',
           'last_name' => 'Segundo nombre',
           'avatar' => ''
        ],
        'buttons' => [
            'showStatsButton' => [
              'title' => $this->t('Boton Mostrar Estadisticas'),
              'label' => $this->t('Mostrar estadísticas'),
              'show' => FALSE,
              'url' => '/',
              'type' => 'button',
            ],
            'retryButton' => [
              'title' => $this->t('Boton Reintentar'),
              'label' => $this->t('Reintentar'),
              'show' => FALSE,
              'url' => '/',
              'type' => 'button',
            ]
        ],
        'config_params' => [
            'comunicationErrorTitle' => [
              'title' => $this->t('Título Error'),
              'value' => $this->t('Lo sentimos. No pudimos actualizar los datos de facturación.'),
              'show' => FALSE,
            ],
            'comunicationErrorDescription' => [
              'title' => $this->t('Subtítulo Error'),
              'value' => $this->t('Por favor inténtalo nuevamente.'),
              'show' => FALSE,
            ],
            'comunicationSuccessTitle' => [
              'title' => $this->t('Título Éxito'),
              'value' => $this->t('Lista de usuarios'),
              'show' => FALSE,
            ],
            'comunicationSuccessDescription' => [
              'title' => $this->t('Subtitulo Éxito'),
              'value' => $this->t('Estos son los usuarios del sistema'),
              'show' => FALSE,
            ],
          ]
      ];
      if (!empty($this->adfDefaultConfiguration())) {
        return $this->adfDefaultConfiguration();
      }
      else {
        return $this->contentFields;
      }
  }

   /**
   * labels configuration table
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function addFieldsDataLabelsTable(array &$form) {
    $data = isset($this->configuration['data_labels']) ? $this->configuration['data_labels'] : $this->contentFields['data_labels'];


    $form['data_labels'] = [
      '#type' => 'details',
      '#title' => $this->t('Data Labels'),
      '#open' => TRUE,
      '#prefix' => '<div id="inner-container-wrapper-data-labels">',
      '#suffix' => '</div>',
    ];    


    $form['data_labels']['table-row'] = [
      '#type' => 'table',
      '#header' => [
        $this->t('Email'),
        $this->t('Primer nombre'),
        $this->t('Segundo nombre'),
        $this->t('Avatar'),
      ],
      '#empty' => $this->t('Sorry, There are no items!'),
      '#prefix' => '<div id="names-fieldset-wrapper-data-labels">',
      '#suffix' => '</div>',
    ];

    foreach ($data as $id => $entity) {
      $form['data_labels']['table-row'][0][$id] = [
        '#type' => 'textfield',
        '#title' => '',
        '#default_value' => $entity,
        '#size' => 20,
      ];
    }
  }

  /**
   * Buttons configurations table.
   *
   * @param array $form
   *   Form to add configuration.
   */
  public function addButtonsTable(array &$form) {
    $data = isset($this->configuration['buttons']) ? $this->configuration['buttons'] : $this->contentFields['buttons'];

    $form['summary']['buttons'] = [
      '#type' => 'details',
      '#title' => $this->t('Botones'),
      '#open' => FALSE,
    ];

    $form['summary']['buttons']['properties'] = [
      '#type' => 'table',
      '#header' => [
        $this->t('Field'),
        $this->t('label'),
        $this->t('Url')
      ],
      '#empty' => $this->t('There are no items yet. Add an item.'),
    ];
    
    foreach ($data as $id => $entity) {
      $form['summary']['buttons']['properties'][$id]['title'] = [
        '#type' => 'hidden',
        '#default_value' => $entity['title'],
        '#suffix' => $entity['title'],
      ];

      $form['summary']['buttons']['properties'][$id]['label'] = [
        '#type' => 'textfield',
        '#default_value' => $entity['label'],
        '#size' => 20
      ];

      $form['summary']['buttons']['properties'][$id]['url'] = [
        '#type' => 'textfield',
        '#default_value' => $entity['url'],
        '#suffix' => '',
        '#size' => 20
      ];
    }
  }
  

   /**
   * Update messages configurations.
   *
   * @param array $form
   *   Form to add configuration.
   */
  public function addConfigParamsTable(array &$form) {
    $data = isset($this->configuration['config_params']) ? $this->configuration['config_params'] : $this->contentFields['config_params'];

    $form['summary']['params'] = [
      '#type' => 'details',
      '#title' => $this->t('Parámetros de configuración'),
      '#open' => FALSE,
    ];

    $form['summary']['params']['properties'] = [
      '#type' => 'table',
      '#header' => [
        $this->t('Field'),
        $this->t('Message'),
      ],
      '#empty' => $this->t('There are no items yet. Add an item.'),
    ];

    foreach ($data as $id => $entity) {
      $form['summary']['params']['properties'][$id]['title'] = [
        '#type' => 'hidden',
        '#default_value' => $entity['title'],
        '#suffix' => $entity['title'],
      ];

      $form['summary']['params']['properties'][$id]['value'] = [
        '#type' => 'textfield',
        '#default_value' => $entity['value'],
        '#size' => 30
      ];

    }
  }

  /**
   * {@inheritdoc}
   */
  public function adfBlockForm($form, FormStateInterface $form_state) {  
    $this->addFieldsDataLabelsTable($form);  
    $this->addButtonsTable($form); 
    $this->addConfigParamsTable($form);
    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function adfBlockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['data_labels'] = $form_state->getValue(['data_labels', 'table-row'])[0];
    $this->configuration['buttons'] = $form_state->getValue(['summary', 'buttons', 'properties']);
    $this->configuration['config_params'] = $form_state->getValue(['summary', 'params', 'properties']);
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [];
  }

}
