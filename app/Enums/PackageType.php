<?php

namespace App\Enums;

enum PackageType: string
{
    case WEDDING = 'wedding';
    case BIRTHDAY = 'birthday';
    case DEBUT = 'debut';
    case CORPORATE = 'corporate';
    case ANNIVERSARY = 'anniversary';
    case CHRISTENING = 'christening';
    case GRADUATION = 'graduation';
    case ENGAGEMENT = 'engagement';
    case BABY_SHOWER = 'baby_shower';
    case BRIDAL_SHOWER = 'bridal_shower';
    case RETIREMENT = 'retirement';
    case REUNION = 'reunion';
    case CONFERENCE = 'conference';
    case SEMINAR = 'seminar';
    case TEAM_BUILDING = 'team_building';
    case HOLIDAY_PARTY = 'holiday_party';
    case FUNDRAISER = 'fundraiser';
    case PRODUCT_LAUNCH = 'product_launch';
    case GALA = 'gala';
    case FESTIVAL = 'festival';
    case CONCERT = 'concert';
    case EXHIBITION = 'exhibition';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::WEDDING => 'Wedding',
            self::BIRTHDAY => 'Birthday',
            self::DEBUT => 'Debut',
            self::CORPORATE => 'Corporate Event',
            self::ANNIVERSARY => 'Anniversary',
            self::CHRISTENING => 'Christening/Baptism',
            self::GRADUATION => 'Graduation',
            self::ENGAGEMENT => 'Engagement Party',
            self::BABY_SHOWER => 'Baby Shower',
            self::BRIDAL_SHOWER => 'Bridal Shower',
            self::RETIREMENT => 'Retirement Party',
            self::REUNION => 'Reunion',
            self::CONFERENCE => 'Conference',
            self::SEMINAR => 'Seminar',
            self::TEAM_BUILDING => 'Team Building',
            self::HOLIDAY_PARTY => 'Holiday Party',
            self::FUNDRAISER => 'Fundraiser',
            self::PRODUCT_LAUNCH => 'Product Launch',
            self::GALA => 'Gala',
            self::FESTIVAL => 'Festival',
            self::CONCERT => 'Concert',
            self::EXHIBITION => 'Exhibition',
            self::OTHER => 'Other',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::WEDDING => 'bg-pink-100 text-pink-800',
            self::BIRTHDAY => 'bg-blue-100 text-blue-800',
            self::DEBUT => 'bg-purple-100 text-purple-800',
            self::CORPORATE => 'bg-gray-100 text-gray-800',
            self::ANNIVERSARY => 'bg-red-100 text-red-800',
            self::CHRISTENING => 'bg-sky-100 text-sky-800',
            self::GRADUATION => 'bg-green-100 text-green-800',
            self::ENGAGEMENT => 'bg-rose-100 text-rose-800',
            self::BABY_SHOWER => 'bg-cyan-100 text-cyan-800',
            self::BRIDAL_SHOWER => 'bg-fuchsia-100 text-fuchsia-800',
            self::RETIREMENT => 'bg-amber-100 text-amber-800',
            self::REUNION => 'bg-indigo-100 text-indigo-800',
            self::CONFERENCE => 'bg-slate-100 text-slate-800',
            self::SEMINAR => 'bg-zinc-100 text-zinc-800',
            self::TEAM_BUILDING => 'bg-teal-100 text-teal-800',
            self::HOLIDAY_PARTY => 'bg-emerald-100 text-emerald-800',
            self::FUNDRAISER => 'bg-lime-100 text-lime-800',
            self::PRODUCT_LAUNCH => 'bg-orange-100 text-orange-800',
            self::GALA => 'bg-violet-100 text-violet-800',
            self::FESTIVAL => 'bg-yellow-100 text-yellow-800',
            self::CONCERT => 'bg-red-100 text-red-800',
            self::EXHIBITION => 'bg-stone-100 text-stone-800',
            self::OTHER => 'bg-neutral-100 text-neutral-800',
        };
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
