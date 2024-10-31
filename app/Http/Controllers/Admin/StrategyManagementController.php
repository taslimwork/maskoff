<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Strategy;
use App\Models\StrategySub;
use App\Models\StrategyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class StrategyManagementController extends Controller
{
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

            $filters = $request->all('name', 'active');

            return Inertia::render('Admin/Strategy/StrategyList', compact('filters','strategies'));

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

    public function strategyDestroy($id)
    {
        try{
            $strategy = Strategy::find($id);
            $strategy->delete();
            session()->flash('success', 'Strategy has been deleted successfully.');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

    public function strategyTypeAdd(Request $request)
    {
        request()->validate([
            'strategy_id' => 'required|exists:strategies,id',
            'strategy_type' => 'required|string|max:255|unique:strategy_types,strategy_type',
        ]);

        try{
            $strategyType = new StrategyType();
            $strategyType->strategy_id = $request->strategy_id;
            $strategyType->strategy_type = $request->strategy_type;
            $strategyType->save();
            session()->flash('success', 'Strategy type has been Added successfully.');
            return redirect()->route('admin.strategies')->with('Strategy type has been Added successfully.');

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

    public function strategyTypeDelete(StrategyType $id)
    {
        try{
            // $type = StrategyType::find($id);
            $id->delete();
            session()->flash('success', 'Strategy type has been deleted successfully.');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }
    public function strategySubList(Request $request, $strategy_id)
    {
        try {
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

            $strategy = Strategy::with('strategyType')->find($strategy_id);

            $filters = $request->all('name', 'active');

            return Inertia::render('Admin/Strategy/SubStrategyList', compact('filters','subStrategies','strategy'));

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

    public function strategySubAdd(Request $request)
    {
        request()->validate([
            'strategy_id' => 'required|exists:strategies,id',
            'strategy_type_id' => 'required|exists:strategy_types,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:10000',
        ],[
            'strategy_type_id.required' => 'The strategy type field is required.'
        ]);

        try{

            $strategySub = new StrategySub();
            $strategySub->strategy_id = $request->strategy_id;
            $strategySub->strategy_type_id = $request->strategy_type_id;
            $strategySub->title = $request->title;
            $strategySub->description = $request->description;
            $strategySub->image = $request->file('image') ? $request->file('image')->store('strategy') : null;
            $strategySub->save();
            session()->flash('success', 'Strategy sub has been added successfully.');
            return redirect()->route('admin.strategy-sub',['strategy_id'=> $request->strategy_id]);

          } catch (\Exception $e) {
              Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
              return back()->with('error','Server error');
          }

    }
    public function strategySubUpdate(Request $request, $id)
    {
        request()->validate([
            'strategy_id' => 'required|exists:strategies,id',
            'strategy_type_id' => 'required|exists:strategy_types,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:10000',
        ],[
            'strategy_type_id.required' => 'The strategy type field is required.'
        ]);

        try{
            $strategySub = StrategySub::find($id);
            $strategySub->strategy_id = $request->strategy_id;
            $strategySub->strategy_type_id = $request->strategy_type_id;
            $strategySub->title = $request->title;
            $strategySub->description = $request->description;
            $strategySub->image = $request->file('image') ? $request->file('image')->store('strategy') : null;
            $strategySub->save();
            session()->flash('success', 'Strategy sub has been updated successfully.');
            return redirect()->route('admin.strategy-sub',['strategy_id'=> $request->strategy_id]);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

    public function subStrategyDelete($id)
    {
        try{
            $StrategySub = StrategySub::find($id);
            $StrategySub->delete();
            session()->flash('success', 'Sub strategy has been deleted successfully.');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }
}
