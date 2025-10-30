<?php

namespace App\Http\Controllers\Api;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PlanResource;
use App\Http\Requests\StorePlanRequest; // We'll create this
use App\Http\Requests\UpdatePlanRequest; // We'll create this

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Fetch plans for the authenticated merchant
        $plans = Plan::where('merchant_id', Auth::id())
                     ->with('product')
                     ->latest()
                     ->paginate($request->get('per_page', 10)); // Allow mobile app to set per_page

        // Return the collection using the PlanResource for consistent formatting
        return PlanResource::collection($plans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlanRequest $request)
    {
        // Validation and authorization is handled by StorePlanRequest
        
        $plan = Plan::create([
            ...$request->validated(),
            'merchant_id' => Auth::id(), // Ensure the plan is linked to the current merchant
        ]);

        // Return the newly created plan with a 201 Created status
        return new PlanResource($plan);
    }

    /**
     * Display the specified resource.
     * Route Model Binding automatically fetches the Plan.
     */
    public function show(Plan $plan)
    {
        // We'll use a Policy or middleware to check ownership (see notes below)

        // Ensure product data is loaded when showing the single resource
        $plan->load('product'); 

        return new PlanResource($plan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        // Validation and authorization is handled by UpdatePlanRequest
        
        $plan->update($request->validated());

        return new PlanResource($plan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        // We'll use a Policy or middleware to check ownership
        
        $plan->delete();

        // Return a successful but empty response (204 No Content)
        return response()->noContent();
    }
}