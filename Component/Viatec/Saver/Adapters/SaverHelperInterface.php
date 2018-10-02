<?php

namespace Onixcat\Component\Viatec\Saver\Adapters;


interface SaverHelperInterface
{
    public function save(string $path, string $fileExtension) : string ;
}
