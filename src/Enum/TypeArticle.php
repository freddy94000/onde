<?php

namespace App\Enum;

enum TypeArticle : string
{
    case EVENT = 'event';
    case ACTIVITY = 'activity';
    case PLACE = 'place';
    case PROVIDER = 'provider';
    case INFORMATION = 'information';
    case BLOG = 'blog';
    case ACTUALITE = 'actualite';
}
