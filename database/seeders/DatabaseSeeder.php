<?php

use App\Helpers\Installer;
use App\Models\Admin\Company;
use Database\Seeders\BdgClassifiersTableSeeder;
use Database\Seeders\BdgFinancingSourceClassifiersTableSeeder;
use Database\Seeders\CatalogGeographicClassifiersTableSeeder;
use Database\Seeders\CatalogSeeder;
use Database\Seeders\CompaniesTableSeeder;
use Database\Seeders\DepartmentsProgramsTableSeeder;
use Database\Seeders\DepartmentsTableSeeder;
use Database\Seeders\IndicatorSourcesTableSeeder;
use Database\Seeders\IndicatorUnitSeeder;
use Database\Seeders\ModelHasRolesTableSeeder;
use Database\Seeders\PrjProjectCatalogAssistantsTableSeeder;
use Database\Seeders\PrjProjectCatalogFundersTableSeeder;
use Database\Seeders\PrjProjectCatalogLineActionServicesTableSeeder;
use Database\Seeders\PrjProjectCatalogLineActionsTableSeeder;
use Database\Seeders\RoleHasPermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\SettingsTableSeeder;
use Database\Seeders\Strategy\PlanDetailsTableSeeder;
use Database\Seeders\Strategy\PlanRegisteredTemplateDetailsTableSeeder;
use Database\Seeders\Strategy\PlansTableSeeder;
use Database\Seeders\Strategy\PlanTemplateDetailsTableSeeder;
use Database\Seeders\Strategy\PlanTemplatesTableSeeder;
use Database\Seeders\UserCompaniesTableSeeder;
use Database\Seeders\UserDepartmentsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\PublicPurchasesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Company::reguard();
        Installer::createCompany('Sede Central', 'es_ES', '', null);
        Installer::createUser('Administrador', 'admin@admin.com', 'password', 'es_ES');
        $this->call(IndicatorSourcesTableSeeder::class);
        $this->call(IndicatorUnitSeeder::class);
        $this->call(CatalogSeeder::class);
        $this->call(BdgClassifiersTableSeeder::class);
        $this->call(BdgFinancingSourceClassifiersTableSeeder::class);
        $this->call(CatalogGeographicClassifiersTableSeeder::class);
        $this->call(PublicPurchasesSeeder::class);
        $this->call(PrjProjectCatalogFundersTableSeeder::class);
        $this->call(PrjProjectCatalogAssistantsTableSeeder::class);
        $this->call(PrjProjectCatalogLineActionsTableSeeder::class);
        $this->call(PrjProjectCatalogLineActionServicesTableSeeder::class);
//        $this->call(RolesTableSeeder::class);
//        $this->call(RoleHasPermissionsTableSeeder::class);
//        $this->call(CompaniesTableSeeder::class);
//        $this->call(SettingsTableSeeder::class);
//        $this->call(UsersTableSeeder::class);
//        $this->call(ModelHasRolesTableSeeder::class);
//        $this->call(UserCompaniesTableSeeder::class);
//        $this->call(DepartmentsTableSeeder::class);
//        $this->call(UserDepartmentsTableSeeder::class);
//        $this->call(DepartmentsProgramsTableSeeder::class);
//        $this->call(PlanTemplatesTableSeeder::class);
//        $this->call(PlanTemplateDetailsTableSeeder::class);
//        $this->call(PlansTableSeeder::class);
//        $this->call(PlanRegisteredTemplateDetailsTableSeeder::class);
//        $this->call(PlanDetailsTableSeeder::class);
        $this->call(\Database\Seeders\ScriptSequencialsSeeder::class);
      $this->call(PerspectivesTableSeeder::class);
        $this->call(PrjProjectCatalogRiskClassificationTableSeeder::class);
        $this->call(GeneratedServicesTableSeeder::class);
    }
}