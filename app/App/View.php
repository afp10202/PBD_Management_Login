<?php

<<<<<<< HEAD
namespace GroupDuaPBD\Management\Login\Php\App;
=======
namespace ProgrammerZamanNow\Belajar\PHP\MVC\App;
>>>>>>> parent of 00c17c2 (Konfigurasi Awal dan Database)

class View
{

    public static function render(string $view, $model)
    {
        require __DIR__ . '/../View/' . $view . '.php';
    }

}