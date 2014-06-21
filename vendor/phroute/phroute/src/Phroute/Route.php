<?php

namespace Phroute;

interface Route {
    
    const BEFORE = 'before';
    const AFTER = 'after';
    
    const ANY = 'ANY';
    const GET = 'GET';
    const HEAD = 'HEAD';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    const OPTIONS = 'OPTIONS';
}

