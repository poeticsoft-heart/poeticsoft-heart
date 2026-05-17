<?php

namespace Poeticsoft\Heart;

use Poeticsoft\Heart\AI\Main as AI;
use Poeticsoft\Heart\API\Main as API;
use Poeticsoft\Heart\Blocks\Main as Blocks;
use Poeticsoft\Heart\Credentials\Main as Credentials;
use Poeticsoft\Heart\Prompts\Main as Prompts;
use Poeticsoft\Heart\Telemetry\Main as Telemetry;

class Main {
    public function __construct() {
        new AI();
        new API();
        new Blocks();
        new Credentials();
        new Prompts();
        new Telemetry();
    }
}
