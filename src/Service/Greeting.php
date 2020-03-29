<?php
/**
 * Created by PhpStorm.
 * User: vladimir
 * Date: 18.07.19
 * Time: 11:58
 */

namespace App\Service;

use Psr\Log\LoggerInterface;

class Greeting
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string $message
     */
    private $message;

    public function __construct(LoggerInterface $logger, string $message)
//    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->message = $message;
    }

    public function greet(string $name): string
    {
        $this->logger->info("Greeted $name");

        return sprintf("%s Mr. %s", $this->message, $name);
    }
}
