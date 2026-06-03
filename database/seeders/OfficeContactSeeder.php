<?php

namespace Database\Seeders;

use App\Models\Ciudad;
use App\Models\OfficeContact;
use Illuminate\Database\Seeder;

class OfficeContactSeeder extends Seeder
{
    public function run(): void
    {
        $offices = [
            'Bucaramanga' => [
                ['department' => 'Comercial (arriendos-ventas)', 'email' => 'comercial@paseoespana.com', 'phones' => '3108180746 - 3102586500', 'manager_name' => 'GLADYS CARVAJAL'],
                ['department' => 'Contratos Vigentes', 'email' => 'arriendos@paseoespana.com', 'phones' => '3108081973', 'manager_name' => 'CLAUDIA CAMARGO'],
                ['department' => 'Reparaciones', 'email' => 'arreglos@paseoespana.com', 'phones' => '3125811186', 'manager_name' => 'YESENIA ESPARZA'],
                ['department' => 'Caja-Cartera', 'email' => 'cartera@paseoespana.com', 'phones' => '3208128962', 'manager_name' => 'MILDRED PRADA'],
                ['department' => 'Administraciones P.H', 'email' => 'admon@paseoespana.com', 'phones' => '3204226287', 'manager_name' => 'GLORIA LUNA'],
                ['department' => 'Gerencia', 'email' => 'gerencia@paseoespana.com', 'phones' => '3108081970', 'manager_name' => 'ZAYRA GONZALEZ'],
            ],
            'Floridablanca' => [
                ['department' => 'Comercial (arriendos-ventas)', 'email' => 'comercialflorida@paseoespana.com', 'phones' => '3138050296', 'manager_name' => 'VALENTINA RUEDA'],
                ['department' => 'Contratos Vigentes', 'email' => 'arriendosflorida@paseoespana.com', 'phones' => '3102881951', 'manager_name' => 'ANDREA RODRIGUEZ'],
                ['department' => 'Reparaciones', 'email' => 'arreglosflorida@paseoespana.com', 'phones' => '3134526697', 'manager_name' => 'EDNA TAVERA'],
                ['department' => 'Caja-Cartera', 'email' => 'carteraflorida@paseoespana.com', 'phones' => '3214628445', 'manager_name' => 'MARCELA GUARÍN'],
                ['department' => 'Administraciones P.H', 'email' => 'admonflorida@paseoespana.com', 'phones' => '3183441402', 'manager_name' => 'DEICE TARAZONA'],
                ['department' => 'Gerencia', 'email' => 'gerenciaflorida@paseoespana.com', 'phones' => '3102517792', 'manager_name' => 'WILSON HERNANDEZ'],
            ],
            'Piedecuesta' => [
                ['department' => 'Comercial (arriendos-ventas)', 'email' => 'comercialp.ta@paseoespana.com', 'phones' => '3213439492', 'manager_name' => 'VIVIAN SALAZAR'],
                ['department' => 'Contratos Vigentes', 'email' => 'arriendosp.ta@paseoespana.com', 'phones' => '3112239605', 'manager_name' => 'ELIANA DIAZ ESTEVEZ'],
                ['department' => 'Reparaciones', 'email' => 'arreglosp.ta@paseoespana.com', 'phones' => '3213838416', 'manager_name' => 'DAJHANIRA URREGO'],
                ['department' => 'Caja-Cartera', 'email' => 'carterap.ta@paseoespana.com', 'phones' => '3117596068', 'manager_name' => 'VIVIANA SANDOVAL'],
                ['department' => 'Administraciones P.H', 'email' => 'admonp.ta@paseoespana.com', 'phones' => '3176468450', 'manager_name' => 'SILVIA MONTAÑEZ'],
                ['department' => 'Gerencia', 'email' => 'gerenciap.ta@paseoespana.com', 'phones' => '3102513198', 'manager_name' => 'DIANIS ACUÑA'],
            ],
            'Giron' => [
                ['department' => 'Comercial', 'email' => 'comercialgiron@paseoespana.com', 'phones' => '3118976591', 'manager_name' => 'SILVIA CHACON'],
                ['department' => 'Arriendos (Contratos Vigentes)', 'email' => 'arriendosgiron@paseoespana.com', 'phones' => '607 6071047', 'manager_name' => 'LICETH RODRIGUEZ'],
                ['department' => 'Reparaciones', 'email' => 'arreglosgiron@paseoespana.com', 'phones' => '', 'manager_name' => ''],
                ['department' => 'Caja-Cartera', 'email' => 'carteragiron@paseoespana.com', 'phones' => '3106739929', 'manager_name' => 'LEIDY JAIMES'],
                ['department' => 'Administraciones P.H', 'email' => 'admongiron@paseoespana.com', 'phones' => '', 'manager_name' => ''],
                ['department' => 'Gerencia', 'email' => 'gerenciagiron@paseoespana.com', 'phones' => '3106867441', 'manager_name' => 'ERIKA HERRERA'],
            ],
        ];

        foreach ($offices as $cityName => $contacts) {
            $ciudad = Ciudad::query()
                ->whereRaw('LOWER(nombre) = ?', [strtolower($cityName)])
                ->first();

            if (! $ciudad) {
                continue;
            }

            $ciudad->update(['has_office' => true]);

            foreach ($contacts as $sortOrder => $contact) {
                OfficeContact::updateOrCreate(
                    [
                        'ciudad_id' => $ciudad->id,
                        'department' => $contact['department'],
                    ],
                    [
                        ...$contact,
                        'sort_order' => $sortOrder,
                    ],
                );
            }
        }
    }
}
