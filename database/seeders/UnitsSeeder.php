<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Models\Tenant;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Get all tenants
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->seedUnitsForTenant($tenant->id);
        }
    }

    /**
     * Seed units for a specific tenant.
     */
    private function seedUnitsForTenant(int $tenantId): void
    {
        // Length/Distance Units
        $meter = Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Meter',
            'symbol' => 'm',
            'description' => 'Base unit for length measurement',
            'is_base_unit' => true,
            'conversion_factor' => 1.0,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Centimeter',
            'symbol' => 'cm',
            'description' => 'Centimeter - 1/100 of a meter',
            'base_unit_id' => $meter->id,
            'is_base_unit' => false,
            'conversion_factor' => 0.01,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Millimeter',
            'symbol' => 'mm',
            'description' => 'Millimeter - 1/1000 of a meter',
            'base_unit_id' => $meter->id,
            'is_base_unit' => false,
            'conversion_factor' => 0.001,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Kilometer',
            'symbol' => 'km',
            'description' => 'Kilometer - 1000 meters',
            'base_unit_id' => $meter->id,
            'is_base_unit' => false,
            'conversion_factor' => 1000.0,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Inch',
            'symbol' => 'in',
            'description' => 'Inch - Imperial unit of length',
            'base_unit_id' => $meter->id,
            'is_base_unit' => false,
            'conversion_factor' => 0.0254,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Foot',
            'symbol' => 'ft',
            'description' => 'Foot - Imperial unit of length',
            'base_unit_id' => $meter->id,
            'is_base_unit' => false,
            'conversion_factor' => 0.3048,
        ]);

        // Weight/Mass Units
        $kilogram = Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Kilogram',
            'symbol' => 'kg',
            'description' => 'Base unit for weight measurement',
            'is_base_unit' => true,
            'conversion_factor' => 1.0,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Gram',
            'symbol' => 'g',
            'description' => 'Gram - 1/1000 of a kilogram',
            'base_unit_id' => $kilogram->id,
            'is_base_unit' => false,
            'conversion_factor' => 0.001,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Pound',
            'symbol' => 'lb',
            'description' => 'Pound - Imperial unit of weight',
            'base_unit_id' => $kilogram->id,
            'is_base_unit' => false,
            'conversion_factor' => 0.453592,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Ounce',
            'symbol' => 'oz',
            'description' => 'Ounce - Imperial unit of weight',
            'base_unit_id' => $kilogram->id,
            'is_base_unit' => false,
            'conversion_factor' => 0.0283495,
        ]);

        // Volume Units
        $liter = Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Liter',
            'symbol' => 'L',
            'description' => 'Base unit for volume measurement',
            'is_base_unit' => true,
            'conversion_factor' => 1.0,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Milliliter',
            'symbol' => 'mL',
            'description' => 'Milliliter - 1/1000 of a liter',
            'base_unit_id' => $liter->id,
            'is_base_unit' => false,
            'conversion_factor' => 0.001,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Gallon',
            'symbol' => 'gal',
            'description' => 'Gallon - Imperial unit of volume',
            'base_unit_id' => $liter->id,
            'is_base_unit' => false,
            'conversion_factor' => 3.78541,
        ]);

        // Count/Quantity Units
        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Piece',
            'symbol' => 'pcs',
            'description' => 'Individual pieces or items',
            'is_base_unit' => true,
            'conversion_factor' => 1.0,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Dozen',
            'symbol' => 'doz',
            'description' => 'Dozen - 12 pieces',
            'is_base_unit' => true,
            'conversion_factor' => 1.0,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Gross',
            'symbol' => 'gr',
            'description' => 'Gross - 144 pieces',
            'is_base_unit' => true,
            'conversion_factor' => 1.0,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Box',
            'symbol' => 'box',
            'description' => 'Box or carton',
            'is_base_unit' => true,
            'conversion_factor' => 1.0,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Pack',
            'symbol' => 'pack',
            'description' => 'Pack or package',
            'is_base_unit' => true,
            'conversion_factor' => 1.0,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Set',
            'symbol' => 'set',
            'description' => 'Set of items',
            'is_base_unit' => true,
            'conversion_factor' => 1.0,
        ]);

        // Area Units
        $squareMeter = Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Square Meter',
            'symbol' => 'm²',
            'description' => 'Base unit for area measurement',
            'is_base_unit' => true,
            'conversion_factor' => 1.0,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Square Foot',
            'symbol' => 'ft²',
            'description' => 'Square foot - Imperial unit of area',
            'base_unit_id' => $squareMeter->id,
            'is_base_unit' => false,
            'conversion_factor' => 0.092903,
        ]);

        // Time Units
        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Hour',
            'symbol' => 'hr',
            'description' => 'Hour - unit of time',
            'is_base_unit' => true,
            'conversion_factor' => 1.0,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Day',
            'symbol' => 'day',
            'description' => 'Day - unit of time',
            'is_base_unit' => true,
            'conversion_factor' => 1.0,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Month',
            'symbol' => 'mo',
            'description' => 'Month - unit of time',
            'is_base_unit' => true,
            'conversion_factor' => 1.0,
        ]);

        Unit::create([
            'tenant_id' => $tenantId,
            'name' => 'Year',
            'symbol' => 'yr',
            'description' => 'Year - unit of time',
            'is_base_unit' => true,
            'conversion_factor' => 1.0,
        ]);
    }
}
