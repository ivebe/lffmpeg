<?php

namespace Ivebe\Lffmpeg\Jobs\Contracts;

if ( interface_exists('Illuminate\Contracts\Bus\SelfHandling') )
{
    interface ISelfHandling extends \Illuminate\Contracts\Bus\SelfHandling
    {
        //Laravel 5.3 doesn't have SelfHandling interface anymore
    }
}
else
{
    interface ISelfHandling
    {
        //Laravel 5.3 doesn't have SelfHandling interface anymore
    }
}