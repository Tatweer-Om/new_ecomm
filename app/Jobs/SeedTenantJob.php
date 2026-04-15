<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Tenant;
use App\Models\User;
use Modules\Branch\Models\Branch;
use Modules\Setting\Models\Setting;


class SeedTenantJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $tenant;
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->tenant->run(function(){
            $user = User::create([
                'name' => $this->tenant->name,
                'email' => $this->tenant->email,
                'contact' => $this->tenant->contact,
                'branch_id' => 1,
                'password' => $this->tenant->password,
            ]);
            $b=0;
            for ($i=0; $i <$this->tenant->branches ; $i++) { 
                $b=$b+$i;
                $branch = Branch::create([
                    'branch_name' => 'Branch'.$b, 
                ]);
            }
            $setting = Setting::create([
                'tenant_id' => $this->tenant->id, 
            ]);
            // $user->assignRole('admin');
        });
    }
}