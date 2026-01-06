<?php

namespace Database\Seeders;

use App\Models\CaseStatus;
use Illuminate\Database\Seeder;

class CaseStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Draft', 'slug' => 'draft', 'bn_name' => 'খসড়া'],
            ['name' => 'Pending Lawyer', 'slug' => 'pending-lawyer', 'bn_name' => 'আইনজীবী অপেক্ষায়'],
            ['name' => 'Pending Approval', 'slug' => 'pending-approval', 'bn_name' => 'অনুমোদন অপেক্ষায়'],
            ['name' => 'Active', 'slug' => 'active', 'bn_name' => 'বিচারাধীন'],
            ['name' => 'Disposed', 'slug' => 'disposed', 'bn_name' => 'নিষ্পত্তি'],
            ['name' => 'Transferred', 'slug' => 'transferred', 'bn_name' => 'স্থানান্তরিত'],
        ];

        foreach ($statuses as $status) {
            CaseStatus::firstOrCreate(['slug' => $status['slug']], $status);
        }
    }
}
