<?php

namespace App\Exception;

use RuntimeException;
//On importe l'exception native de PHP RuntimeException.
class SalleIndisponibleException extends RuntimeException
{
}
//On crée une exception personnalisée.