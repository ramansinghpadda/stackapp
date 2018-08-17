<?php

use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plans')->truncate();

        DB::table('plans')->insert([
            'name' => 'Free',
            'stripe_plan_id' =>'free' ,
            'description' => '',
            'currency'=>'USD',
            'price'=>'0',
            'num_organizations_limit'=>'1',
            'num_applications_limit'=>'10',
            'num_collaborators'=>'0'

        ]);
        DB::table('plans')->insert([
            'name' => 'LITE $19',
            'stripe_plan_id' =>'lite' ,
            'description' => '',
            'currency'=>'USD',
            'price'=>'19',
            'num_organizations_limit'=>'1',
            'num_applications_limit'=>'~',
            'num_collaborators'=>'0'

        ]);
        DB::table('plans')->insert([
            'name' => 'PRO $99',
            'stripe_plan_id' =>'pro' ,
            'description' => '',
            'currency'=>'USD',
            'price'=>'99',
            'num_organizations_limit'=>'5',
            'num_applications_limit'=>'~',
            'num_collaborators'=>'~'

        ]);
        DB::table('plans')->insert([
            'name' => 'AGENCY $299',
            'stripe_plan_id' =>'agency' ,
            'description' => '',
            'currency'=>'USD',
            'price'=>'299',
            'num_organizations_limit'=>'~',
            'num_applications_limit'=>'~',
            'num_collaborators'=>'~'

        ]);
        DB::table('plans')->insert([
            'name' => 'NON PROFIT / EDU $49',
            'stripe_plan_id' =>'non-profit' ,
            'description' => '',
            'currency'=>'USD',
            'price'=>'49',
            'num_organizations_limit'=>'1',
            'num_applications_limit'=>'~',
            'num_collaborators'=>'~'
        ]);
    }
}
