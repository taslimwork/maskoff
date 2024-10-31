<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function reportTypeList() {
        try {
            $reportTypes= ReportType::filter(Request::only('name', 'status'))
            ->ordering(Request::only('fieldName','shortBy'))
            ->orderBy('id','desc')
            ->paginate(request()->perPage ?? $this->per_page)
            ->withQueryString()
            ->through(fn ($type) => [
                'id' => $type->id,
                'name' => $type->name,
                'status' => $type->status,
            ]);

            $filters = Request::all('name', 'status');
            return Inertia::render('Admin/ReportType/List', compact('filters','reportTypes'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

    public function addReportType() {
        if (request()->isMethod('post')) {
            // dd(request()->all());
            request()->validate([
                'name' => 'required|min:3',
                'status' => 'required',
            ],[
                'name.required' => 'The report type field is required.',
                'name.min' => 'The report type field must be at least 3 characters.',
  
            ]);
  
            $reportType = new ReportType();
            $reportType->name = request()->name;
            $reportType->slug = str_replace(" ", "-", strtolower(request()->name));
            $reportType->status = request()->status;
            $reportType->save();
            return redirect()->route('admin.reportTypes')->with('success', 'Report type has been created successfully.');
        }

    }
  
    public function editReportType(ReportType $reportType) {
        if (request()->isMethod('post')) {
            request()->validate([
                'name' => 'required|min:3|unique:report_types,name,'. $reportType->id,
                'status' => 'required',
            ],[
                'name.required' => 'The report type field is required.',
                'name.min' => 'The report type field must be at least 3 characters.',
  
            ]);
  
            $reportType->name = request()->name;
            $reportType->status = request()->status;
            $reportType->save();
            return redirect()->route('admin.reportTypes')->with('success', 'Report type has been updated successfully.');
        }
  
    }

    public function reportTypeChangeStatus(ReportType $reportType) {
        try {
            $reportType->status = $reportType->status == 1 ? 0 : 1;
            $reportType->save();
            session()->flash('success', 'Report type status has been updated successfully.');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

    public function reportTypeDelete(ReportType $reportType) {
        try {
            // $eventType->group()->delete();
            $reportType->delete();
            return redirect()->route('admin.reportTypes')->with('success', 'Report Type has been deleted successfully.');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

    public function reportList(){
        try {
            $reports= Report::filter(Request::only('name', 'report_type', 'post', 'creator', 'status'))
            ->ordering(Request::only('fieldName','shortBy'))
            ->orderBy('id','desc')
            ->paginate(request()->perPage ?? $this->per_page)
            ->withQueryString()
            ->through(fn ($report) => [
                'id' => $report->id,
                'reporter_full_name' => $report->user->full_name,
                'reporter_username' => $report->user->username,
                'reporter_profile_photo' => $report->user->profile_photo_url,
                'reporte_on' => $report->created_at,
                'type' => $report->reportType->name,
                'post_description' => $report->post->post_description,
                'post_creator_full_name' => $report->post->user->full_name,
                'post_creator_profile_photo' => $report->post->user->profile_photo_url,
                'post_creator_username' => $report->post->user->username,
                'post_create_on' => $report->post->created_at,
                'status' => $report->status,
            ]);

            $filters = Request::all('name', 'report_type', 'post', 'creator', 'status');
            return Inertia::render('Admin/Report/List', compact('filters','reports'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }
    
    public function reportChangeStatus(Report $report) {
        try {
            $report->status = $report->status == 1 ? 0 : 1;
            $report->save();
            session()->flash('success', 'Report status has been updated successfully.');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

}
