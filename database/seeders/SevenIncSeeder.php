<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SevenIncSeeder extends Seeder
{
    /**
     * Seed Seven Inc. business data.
     * 6 Business Units = 6 Categories
     * Each category has its own supplier (the brand itself)
     */
    public function run(): void
    {
        // ============================================================
        // 1. CATEGORIES (Seven Inc. Business Units)
        // ============================================================
        $categories = [
            'Fashion & Apparel'       => 'Modern men\'s fashion e-commerce',
            'Production Services'     => 'Garment manufacturing & distribution',
            'IT & Digital Solutions'  => 'Web development & system integration',
            'Logistics & Courier'     => 'Shipping & last-mile delivery',
            'Safe Storage'            => 'Temporary storage for goods, houses, vehicles',
            'Local Services'          => 'Specialized trades & exterior design',
        ];

        $categoryModels = [];
        foreach ($categories as $name => $description) {
            $categoryModels[$name] = Category::firstOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }

        // ============================================================
        // 2. SUPPLIERS (Seven Inc. In-House Brands)
        // ============================================================
        $suppliers = [
            // Fashion & Apparel
            ['name' => 'Crows Denim',        'category' => 'Fashion & Apparel',      'contact' => '0812-1111-0001', 'email' => 'crows@seveninc.id',       'address' => 'Yogyakarta, Indonesia'],
            ['name' => 'Alphawear',          'category' => 'Fashion & Apparel',      'contact' => '0812-1111-0002', 'email' => 'alphawear@seveninc.id',   'address' => 'Yogyakarta, Indonesia'],
            ['name' => 'Grenade Clothing',   'category' => 'Fashion & Apparel',      'contact' => '0812-1111-0003', 'email' => 'grenade@seveninc.id',     'address' => 'Yogyakarta, Indonesia'],
            ['name' => 'Indonesia Banget',   'category' => 'Fashion & Apparel',      'contact' => '0812-1111-0004', 'email' => 'ib@seveninc.id',          'address' => 'Yogyakarta, Indonesia'],

            // Production Services
            ['name' => 'Rumah Konveksi',     'category' => 'Production Services',    'contact' => '0812-2222-0001', 'email' => 'konveksi@seveninc.id',    'address' => 'Yogyakarta, Indonesia'],

            // IT & Digital Solutions
            ['name' => 'Seven Tech',         'category' => 'IT & Digital Solutions', 'contact' => '0812-3333-0001', 'email' => 'tech@seveninc.id',        'address' => 'Yogyakarta, Indonesia'],
            ['name' => 'Republikweb.net',    'category' => 'IT & Digital Solutions', 'contact' => '0812-3333-0002', 'email' => 'hello@republikweb.net',   'address' => 'Yogyakarta, Indonesia'],

            // Logistics & Courier
            ['name' => 'Ambilpaket',         'category' => 'Logistics & Courier',    'contact' => '0812-4444-0001', 'email' => 'cs@ambilkpaket.id',       'address' => 'Yogyakarta, Indonesia'],

            // Safe Storage
            ['name' => 'Titipsini',          'category' => 'Safe Storage',           'contact' => '0812-5555-0001', 'email' => 'cs@titipsini.id',         'address' => 'Yogyakarta, Indonesia'],

            // Local Services
            ['name' => 'Tukang Las Jogja',   'category' => 'Local Services',         'contact' => '0812-6666-0001', 'email' => 'las@seveninc.id',         'address' => 'Yogyakarta, Indonesia'],
        ];

        $supplierModels = [];
        foreach ($suppliers as $s) {
            $supplierModels[$s['name']] = Supplier::firstOrCreate(
                ['name' => $s['name']],
                [
                    'contact' => $s['contact'],
                    'email'   => $s['email'],
                    'address' => $s['address'],
                ]
            );
        }

        // ============================================================
        // 3. PRODUCTS (Real product lines per brand)
        // ============================================================
        $products = [
            // ---- Fashion & Apparel ----
            ['Crows Denim',      'Fashion & Apparel', 'Crows Slim Fit Jeans',      'CD-SLJ-001',  185000, 265000, 42, 15],
            ['Crows Denim',      'Fashion & Apparel', 'Crows Straight Cut Jeans',  'CD-SCJ-002',  190000, 275000, 28, 15],
            ['Crows Denim',      'Fashion & Apparel', 'Crows Denim Jacket',        'CD-DJK-003',  245000, 375000, 18, 10],
            ['Alphawear',        'Fashion & Apparel', 'Alpha Basic Tee',           'AW-BST-001',   45000,  89000, 120, 30],
            ['Alphawear',        'Fashion & Apparel', 'Alpha Polo Shirt',          'AW-PLO-002',   85000, 149000, 75, 20],
            ['Alphawear',        'Fashion & Apparel', 'Alpha Hoodie',              'AW-HOD-003',  135000, 229000, 55, 15],
            ['Grenade Clothing', 'Fashion & Apparel', 'Grenade Cargo Pants',       'GC-CGP-001',  175000, 289000, 32, 12],
            ['Grenade Clothing', 'Fashion & Apparel', 'Grenade Tactical Shirt',    'GC-TCS-002',   95000, 175000, 48, 15],
            ['Indonesia Banget', 'Fashion & Apparel', 'Kaos Indonesia Banget',     'IB-KTS-001',   55000, 109000, 95, 25],
            ['Indonesia Banget', 'Fashion & Apparel', 'Jaket Indonesia Banget',    'IB-JKT-002',  165000, 279000, 22, 10],

            // ---- Production Services ----
            ['Rumah Konveksi',   'Production Services', 'Custom Kaos Sablon',       'RK-CKS-001',   35000,  65000, 500, 100],
            ['Rumah Konveksi',   'Production Services', 'Custom Polo Bordir',       'RK-CPB-002',   75000, 135000, 250,  50],
            ['Rumah Konveksi',   'Production Services', 'Custom Jaket Bordir',      'RK-CJB-003',  195000, 325000, 120,  30],
            ['Rumah Konveksi',   'Production Services', 'Custom Seragam Kerja',     'RK-CSK-004',   95000, 165000, 340,  80],
            ['Rumah Konveksi',   'Production Services', 'Custom Hoodie Premium',    'RK-CHP-005',  145000, 245000, 180,  40],

            // ---- IT & Digital Solutions ----
            ['Seven Tech',        'IT & Digital Solutions', 'Website Development (Starter)',     'ST-WDS-001', 3500000,  5500000, 10, 3],
            ['Seven Tech',        'IT & Digital Solutions', 'Website Development (Business)',    'ST-WDB-002', 7500000, 12000000,  6, 2],
            ['Seven Tech',        'IT & Digital Solutions', 'E-Commerce Platform Setup',         'ST-ECS-003', 9500000, 15500000,  4, 1],
            ['Seven Tech',        'IT & Digital Solutions', 'Custom ERP System',                 'ST-CES-004',20000000, 32000000,  2, 1],
            ['Seven Tech',        'IT & Digital Solutions', 'API Integration Service',           'ST-API-005', 4500000,  7500000,  8, 2],
            ['Republikweb.net',   'IT & Digital Solutions', 'Domain & Hosting Package',          'RW-DHP-001',  450000,   850000, 45, 10],
            ['Republikweb.net',   'IT & Digital Solutions', 'SSL Certificate (1 Year)',          'RW-SSL-002',  350000,   650000, 60, 15],
            ['Republikweb.net',   'IT & Digital Solutions', 'Maintenance Package (Monthly)',     'RW-MTC-003',  750000,  1250000, 25, 5],

            // ---- Logistics & Courier ----
            ['Ambilpaket',        'Logistics & Courier', 'Same-Day Delivery (Yogyakarta)',    'AP-SDD-001',   12000,   22000, 999, 50],
            ['Ambilpaket',        'Logistics & Courier', 'Regular Delivery (Java)',           'AP-RGL-002',    9000,   18000, 999, 50],
            ['Ambilpaket',        'Logistics & Courier', 'Regular Delivery (Nationwide)',     'AP-RGN-003',   15000,   28000, 999, 50],
            ['Ambilpaket',        'Logistics & Courier', 'Cargo Service (Lion Parcel)',       'AP-CGL-004',   18000,   32000, 999, 50],
            ['Ambilpaket',        'Logistics & Courier', 'Cargo Service (JNE)',               'AP-CGJ-005',   20000,   35000, 999, 50],

            // ---- Safe Storage ----
            ['Titipsini',         'Safe Storage', 'Barang Bulanan (Small)',                'TS-BBS-001',   150000,   250000, 30, 10],
            ['Titipsini',         'Safe Storage', 'Barang Bulanan (Medium)',               'TS-BBM-002',   250000,   400000, 20,  8],
            ['Titipsini',         'Safe Storage', 'Barang Bulanan (Large)',                'TS-BBL-003',   400000,   650000, 12,  5],
            ['Titipsini',         'Safe Storage', 'Penyimpanan Motor',                     'TS-MTR-004',   200000,   350000, 15,  5],
            ['Titipsini',         'Safe Storage', 'Penyimpanan Mobil',                     'TS-MBL-005',   500000,   850000,  8,  3],
            ['Titipsini',         'Safe Storage', 'Penyimpanan Rumah (Kosong)',            'TS-RMH-006',  1500000,  2500000,  5,  2],

            // ---- Local Services ----
            ['Tukang Las Jogja',  'Local Services', 'Kanopi Minimalis (per m²)',           'TL-KNP-001',   350000,   550000, 100, 20],
            ['Tukang Las Jogja',  'Local Services', 'Pagar Besi (per m²)',                 'TL-PGR-002',   450000,   700000,  80, 15],
            ['Tukang Las Jogja',  'Local Services', 'Railing Tangga (per m)',              'TL-RLT-003',   250000,   400000,  60, 12],
            ['Tukang Las Jogja',  'Local Services', 'Teralis Jendela (per unit)',          'TL-TRJ-004',   300000,   480000,  50, 10],
            ['Tukang Las Jogja',  'Local Services', 'Konstruksi Baja Ringan (per m²)',     'TL-KBR-005',   150000,   250000, 200, 40],
            ['Tukang Las Jogja',  'Local Services', 'Desain Eksterior Custom',             'TL-DEC-006',   500000,   950000,  25,  5],
        ];

        foreach ($products as $p) {
            [$supplierName, $categoryName, $name, $sku, $purchase, $selling, $stock, $minStock] = $p;

            Product::firstOrCreate(
                ['sku' => $sku],
                [
                    'name'             => $name,
                    'description'      => $name . ' — ' . $categoryName . ' by ' . $supplierName,
                    'category_id'      => $categoryModels[$categoryName]->id,
                    'supplier_id'      => $supplierModels[$supplierName]->id,
                    'purchase_price'   => $purchase,
                    'selling_price'    => $selling,
                    'stock'            => $stock,
                    'min_stock'        => $minStock,
                ]
            );
        }

        $this->command->info('✅ Seven Inc. seeder completed:');
        $this->command->info('   • 6 Categories (Business Units)');
        $this->command->info('   • 10 Suppliers (In-House Brands)');
        $this->command->info('   • ' . count($products) . ' Products');
    }
}
