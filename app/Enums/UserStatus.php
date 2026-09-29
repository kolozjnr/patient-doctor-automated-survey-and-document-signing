<?php

namespace App\Enums;

enum UserStatus: string
{
    case New = 'Nieuw';
    case LoggedIn = 'Ingelogd';
    case Active = 'Actief';
    case Inactive = 'Inactief';
    case IntakeCompleted = 'Intake Ingevuid';
    case IntakeFilled = 'Intake Invullen';
    case GeneralFilled = 'Algemeen Gevuld';
}