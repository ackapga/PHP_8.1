<?php

namespace Ackapga\Habrahabr\Http\Actions;

use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Http\Response;

interface ActionInterface
{
    public function handle(Request $request): Response;
}
