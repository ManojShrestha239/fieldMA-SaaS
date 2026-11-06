<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\IssueType;
use App\Models\Purpose;
use Illuminate\Database\Seeder;

class IssueTypeSedder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $issueTypes = [
            'Account',
            'Product',
            'Machine',
            'Other',
        ];

        foreach ($issueTypes as $issueType) {
            $existingIssueType = IssueType::where('type', $issueType)->first();
            if (! $existingIssueType) {
                IssueType::create(['type' => $issueType]);
            }
        }

        $purposes = ['Feedback Collection', 'Order', 'Collection'];

        foreach ($purposes as $purpose) {
            $existingPurpose = Purpose::where('purpose', $purpose)->first();
            if (! $existingPurpose) {
                Purpose::create(['purpose' => $purpose]);
            }
        }

        $existingBank = Bank::where('name', 'cash')->first();
        if (! $existingBank) {
            Bank::create(['name' => 'cash']);
        }
    }
}
