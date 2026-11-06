<?php

namespace App\Exports;

use App\Models\RawMaterial;
use App\Models\RawProduct;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RawProductExport implements FromCollection, WithHeadings
{
    /**
     * @return Collection
     */
    public function collection()
    {
        $rawproducts = RawProduct::with(['product', 'rawMaterial'])->get();

        return $rawproducts->map(function ($rawproduct, $index) {
            $productName = $rawproduct->product ? $rawproduct->product->name : 'N/A';

            $ids = explode('::', $rawproduct->rawmaterial_id);
            $materials = RawMaterial::whereIn('id', $ids)->pluck('name')->toArray();
            $rawMaterials = implode(', ', $materials);

            return [
                'SN' => $index + 1,
                'Product Name' => $productName,
                'Raw Materials' => $rawMaterials,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'SN',
            'Product Name',
            'Raw Materials',
        ];
    }
}
