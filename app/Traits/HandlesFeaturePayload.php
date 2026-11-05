<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\Request;

trait HandlesFeaturePayload
{
    /**
     * Build the filtered features JSON array storing only enabled modules and enabled sub-features.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    protected function buildFeaturesPayload(Request $request): array
    {
        $features = [];
        $available = config('features.map', []);

        foreach ($available as $fKey => $fConfig) {
            $submittedSubs = (array) $request->input("features.{$fKey}.sub_features", []);
            $enabledSubs = [];

            if (!empty($fConfig['sub_features']) && is_array($fConfig['sub_features'])) {
                foreach ($fConfig['sub_features'] as $subKey) {
                    if (!empty($submittedSubs[$subKey]) || in_array($subKey, $submittedSubs, true)) {
                        $enabledSubs[] = $subKey;
                    }
                }
            }

            // Auto-select main feature on backend if any sub-feature is selected, or if master is checked
            $hasAnySubSelected = count($enabledSubs) > 0;
            $masterChecked = !empty($request->input("features.{$fKey}.enabled"))
                || $request->input("features.{$fKey}") === '1'
                || $request->input("features.{$fKey}") === 1;

            $isModuleEnabled = $masterChecked || $hasAnySubSelected;

            // If main feature is not enabled, auto-deselect (omit module and all sub-features completely)
            if (!$isModuleEnabled) {
                continue;
            }

            $featureEntry = [];
            if (!empty($fConfig['sub_features']) && is_array($fConfig['sub_features'])) {
                $featureEntry['sub_features'] = array_values($enabledSubs);
            }

            // (object) [] ensures it serializes to "{}" in JSON when empty
            $features[$fKey] = !empty($featureEntry) ? $featureEntry : (object) [];
        }

        return $features;
    }
}
