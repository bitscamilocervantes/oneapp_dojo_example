<?php

namespace Drupal\oneapp_dojo_example\Plugin\rest\resource\v2_0;

use Drupal\rest\ResourceResponse;
use Drupal\oneapp_rest\Plugin\ResourceBase;
use Drupal\rest\ModifiedResourceResponse;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   api_response_version = "v2_0",
 *   block_id = "oneapp_dojo_example_v2_0_users_block",
 *   id = "oneapp_dojo_example_v2_0_users_rest_resource",
 *   label = @Translation("ONEAPP Dojo Example - Users rest resource"),
 *   uri_paths   = {
 *     "canonical" = "api/v2.0/training/users/{page}/list"
 *   }
 * )
 */
class UsersRestResource extends ResourceBase {

    /**
    * {@inheritdoc}
    */
    public function get($page) {
        $this->init();
        \Drupal::service('page_cache_kill_switch')->trigger();
        $serviceRestLogic = \Drupal::service('oneapp_dojo_example.v2_0.users_rest_logic');
        $serviceRestLogic->setConfig($this->configBlock);

        try {
            $data = $serviceRestLogic->get($page);
            $this->apiResponse->getConfig()->setAll($data['config']);
            $this->apiResponse->getData()->setAll($data['data']);

            // Build response with data.
            $response = new ResourceResponse($this->apiResponse);
            $response->addCacheableDependency($this->cacheMetadata);
        } catch (\Throwable $th) {
            $data = $serviceRestLogic->getErrorData();
            $this->apiResponse->getConfig()->setAll($data['config']);
            $this->apiResponse->getData()->setAll($data['data']);
            $response = new ResourceResponse($this->apiResponse, 503);
            $response->addCacheableDependency($this->cacheMetadata);
        }

        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

}

