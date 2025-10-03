<?php

namespace App\Enums;

enum InclusionCategory: string
{
    case INVITATIONS = 'Invitations';
    case GIVEAWAYS = 'Giveaways';
    case PHOTOS = 'Photos';
    case VIDEOS = 'Videos';
    case CAKE = 'Cake';
    case HMUA = 'HMUA';
    case HOST = 'Host';
    case CATERING = 'Catering';
    case VENUE = 'Venue';
    case DECORATION = 'Decoration';
    case LIGHTS_SOUNDS = 'Lights & Sounds';
    case TRANSPORTATION = 'Transportation';
    case GOWN = 'Gown';
    case OTHER = 'Other';

    public function label(): string
    {
        return $this->value;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
