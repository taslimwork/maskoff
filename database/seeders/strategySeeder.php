<?php

namespace Database\Seeders;

use App\Models\Strategy;
use App\Models\StrategySub;
use App\Models\StrategyType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class strategySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $strategies = [
            [
                'name'=> 'Meditation',
                'description'=> $faker->paragraph,
            ],
            [
                'name'=> 'Deep breathing',
                'description'=> $faker->paragraph,
            ],
        ];

        $strategyTypes = [
            ['strategy_type'=>'Awaken'],
            ['strategy_type'=>'Focus'],
            ['strategy_type'=>'Relax'],
            ['strategy_type'=>'Reset'],
            ['strategy_type'=>'Unwind For Sleep']
        ];

        foreach ($strategies as $key => $value)
        {
            $strategy = Strategy::create($value);

            foreach ($strategyTypes as $key => $type)
            {
                $type['strategy_id'] = $strategy->id;
                $types = StrategyType::create($type);

                for ($i=0; $i < 10; $i++)
                {
                    $strategySub = [
                        'title'=> $faker->sentence($nbWords = 2, $variableNbWords = true),
                        'description'=> $faker->sentence,
                        'strategy_id'=>  $strategy->id,
                        'strategy_type_id'=>  $types->id
                    ];

                    StrategySub::create($strategySub);
                }
            }
        }
    }
}

