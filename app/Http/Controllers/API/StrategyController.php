<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Strategy;
use App\Models\StrategySub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


/**
 * @tags Strategy-management
*/

class StrategyController extends BaseController
{
    /**
     * @operationId Strategy-List
     *   @param string $name to filter the event.
    */
    public function strategyList(Request $request){

        try {
            $strategies = Strategy::filter($request->only('name', 'active'))
            ->ordering($request->only('fieldName','shortBy'))
            ->orderBy('id','desc')->paginate(request()
            ->perPage ?? $this->per_page)->withQueryString()
            ->through(fn ($strategy) => [
              'id' => $strategy->id,
              'name' => $strategy->name,
              'image_path' => $strategy->image_path,
              'description' => $strategy->description,
              'types' => $strategy->strategyType,
              'active' => $strategy->active,
            ]);

            return $this->sendResponse($strategies, "All strategy list");
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }

     /**
     * @operationId Strategy-Sub-List
     *   @param string $title to filter the event.
     */

    public function strategySubList(Request $request, $strategy_id, $strategy_type_id = null)
    {
        try {
            if($strategy_type_id == null)
            {
                $subStrategies = StrategySub::filter($request->only('title', 'active'))
                ->where('strategy_id', $strategy_id)
                ->ordering($request->only('fieldName','shortBy'))
                ->orderBy('id','desc')->paginate(request()
                ->perPage ?? $this->per_page)->withQueryString()
                ->through(fn ($strategy_sub) => [
                    'id' => $strategy_sub->id,
                    'title' => $strategy_sub->title,
                    'image_path' => $strategy_sub->image_path,
                    'description' => $strategy_sub->description,
                    'strategy_type_id' => $strategy_sub->strategy_type_id,
                    'strategy_id' => $strategy_sub->strategy_id,
                    'type' => $strategy_sub->strategyType,
                    'strategy' => $strategy_sub->strategy,
                    'active' => $strategy_sub->active,
                ]);
            }
            else{

                $subStrategies = StrategySub::filter($request->only('title', 'active'))
                ->where('strategy_id', $strategy_id)
                ->where('strategy_type_id', $strategy_type_id)
                ->ordering($request->only('fieldName','shortBy'))
                ->orderBy('id','desc')->paginate(request()
                ->perPage ?? $this->per_page)->withQueryString()
                ->through(fn ($strategy_sub) => [
                    'id' => $strategy_sub->id,
                    'title' => $strategy_sub->title,
                    'image_path' => $strategy_sub->image_path,
                    'description' => $strategy_sub->description,
                    'strategy_type_id' => $strategy_sub->strategy_type_id,
                    'strategy_id' => $strategy_sub->strategy_id,
                    'type' => $strategy_sub->strategyType,
                    'strategy' => $strategy_sub->strategy,
                    'active' => $strategy_sub->active,
                ]);
            }

            $strategies = Strategy::with('strategyType')->find($strategy_id);
            $data['strategies']= $strategies;
            $data['subStrategies']= $subStrategies;

        return $this->sendResponse($data, "All sub strategy list");
        } catch (\Throwable $th) {
            Log::error(" :: EXCEPTION :: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return $this->sendError('Server Error!', [], 500);
        }
    }
}
