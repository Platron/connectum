<?php

namespace Platron\Connectum\handbooks;

class Expands {
    const 
        CARD = 'card',
        CLIENT = 'client',
        LOCATION = 'location',
        CUSTOM_FIELDS = 'custom_fields',
        ISSUER = 'issuer',
        SECURE3D = 'secure3d',
        CASHFLOW = 'cashflow';
    
    public static function getAllExpands(){
        return array(
            self::CARD,
            self::CLIENT,
            self::LOCATION,
            self::CUSTOM_FIELDS,
            self::ISSUER,
            self::SECURE3D,
            self::CASHFLOW,
        );
    }
}
