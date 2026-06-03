<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown when an external provisioning call (GLPI, Mailcow, …) fails.
 * Catching this at the controller level lets the employee creation succeed
 * even when the downstream service is temporarily unavailable.
 */
class ProvisioningException extends RuntimeException {}
