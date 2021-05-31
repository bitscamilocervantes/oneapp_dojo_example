<?php


namespace Drupal\oneapp_dojo_example\Plugin\Endpoint\v2_0;

use Drupal\oneapp_endpoints\EndpointBase;

/**
 * Provides a 'UsersEndpoint' entity.
 *
 * @Endpoint(
 * id = "oneapp_dojo_example_v2_0_users_endpoint",
 * admin_label = @Translation("Dojo Users Example v2.0"),
 *  defaults = {
 *    "endpoint" = "https://reqres.in/api/users",
 *    "method" = "GET",
 *    "timeout" = 60,  
 *  },
 * )
 */
class UsersEndpoint extends EndpointBase {

}
