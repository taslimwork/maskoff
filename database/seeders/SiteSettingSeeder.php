<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            [
                'key'=>'logo',
                'value'=>'',
            ],
            [
                'key'=>'date_format',
                'value'=>'YYYY/MM/DD',
            ],
            [
                'key'=>'per_page',
                'value'=> 10,
            ],
            [
                'key'=>'main_color',
                'value'=>'#bd9df1',
            ],
            [
                'key'=>'hover_color',
                'value'=>'#ed521b',
            ],
            [
                'key'=>'button_color',
                'value'=>'#EC7075',
            ],


           ];

           foreach($data as $value){
            $setting = SiteSetting::where('key',$value['key'])->first();
            if(empty( $setting )){
                $setting = new SiteSetting();
            }

             $setting->key = $value['key'];
             $setting->value = $value['value'];
             $setting->save();
           }

    }
}
