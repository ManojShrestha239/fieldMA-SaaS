<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class ImportExistingTenantCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:import 
                            {company_name : The display name of the company}
                            {slug : Unique slug identifier for the tenant}
                            {--db= : The existing MySQL database name}
                            {--email= : Contact or admin email}
                            {--domain= : Custom domain or subdomain}
                            {--plan= : Subscription plan slug (e.g. starter, professional, enterprise)}
                            {--migrate : Whether to run tenant migrations on the imported database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import an existing single-tenant FieldMA database into the SaaS platform';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $companyName = $this->argument('company_name');
        $slug = Str::slug($this->argument('slug'));
        $dbName = $this->option('db') ?? config('tenancy.database.prefix') . $slug . config('tenancy.database.suffix');
        $email = $this->option('email') ?? "admin@{$slug}.com";
        $planSlug = $this->option('plan');

        $this->info("Importing existing FieldMA database for: {$companyName} ({$slug})...");

        // Check if tenant already exists
        if (Tenant::find($slug)) {
            $this->error("A tenant with slug '{$slug}' already exists in the central database.");
            return Command::FAILURE;
        }

        // Find plan if specified
        $plan = null;
        if ($planSlug) {
            $plan = SubscriptionPlan::where('slug', $planSlug)->first();
            if (!$plan) {
                $this->warn("Plan '{$planSlug}' not found. Tenant will be created without plan assignment.");
            }
        }

        // 1. Create the tenant model WITHOUT triggering the auto database creation hook
        $tenant = new Tenant([
            'id' => $slug,
            'company_name' => $companyName,
            'slug' => $slug,
            'email' => $email,
            'status' => 'active',
            'subscription_plan_id' => $plan ? $plan->id : null,
            'data' => [
                'tenancy_db_name' => $dbName,
                'theme_color' => '#32c5d2',
                'fcm_topic' => "{$slug}_fieldMA_allUser",
            ],
        ]);

        // Save silently so Stancl doesn't fire TenantCreated DB creation pipeline on an existing DB
        $tenant->saveQuietly();

        // 2. Attach domain
        $centralDomain = config('tenancy.central_domains')[0] ?? 'localhost';
        $subdomain = "{$slug}.{$centralDomain}";
        $tenant->domains()->create(['domain' => $subdomain]);
        $this->info("Attached subdomain: {$subdomain}");

        if ($customDomain = $this->option('domain')) {
            $tenant->domains()->create(['domain' => strtolower(trim($customDomain))]);
            $this->info("Attached custom domain: {$customDomain}");
        }

        // 3. Run migrations on tenant DB if requested
        if ($this->option('migrate')) {
            $this->info("Running tenant migrations on database '{$dbName}'...");
            Artisan::call('tenants:migrate', [
                '--tenants' => [$slug],
                '--force' => true,
            ]);
            $this->line(Artisan::output());
        }

        $this->info("✨ Successfully imported tenant '{$companyName}' into SaaS registry!");
        $this->table(
            ['Key', 'Value'],
            [
                ['Tenant ID', $tenant->id],
                ['Company Name', $tenant->company_name],
                ['Database', $dbName],
                ['Primary Subdomain', $subdomain],
                ['Plan', $plan ? $plan->name : 'None'],
                ['Status', $tenant->status],
            ]
        );

        return Command::SUCCESS;
    }
}
