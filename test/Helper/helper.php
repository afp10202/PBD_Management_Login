<?php

namespace GroupDuaPBD\Management\Login\Php\App {

    function header(string $value){
        echo $value;
    }

}

namespace GroupDuaPBD\Management\Login\Php\Service {

    function setcookie(string $name, string $value){
        echo "$name: $value";
    }

}