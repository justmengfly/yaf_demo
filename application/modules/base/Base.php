<?php
namespace App\Base;

use Helpers\ApiResponse;
use Yaf\Controller_Abstract;

abstract class Base extends Controller_Abstract
{
    use ApiResponse;
}
