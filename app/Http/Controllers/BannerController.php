<?php

namespace App\Http\Controllers;

use App\Actions\CreateBanner;
use App\Actions\DeleteBanner;
use App\Actions\UpdateBanner;
use App\Enums\BannerStatus;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Inertia\Inertia;
use Inertia\Response;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $banners = Banner::latest()->paginate(10);

        return Inertia::render('banners/Index', [
            'banners' => BannerResource::collection($banners),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('banners/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBannerRequest $request, CreateBanner $createBanner)
    {
        $validated = $request->validated();

        $createBanner->execute(
            $validated['title'],
            $validated['target_url'],
            $validated['link_text'] ?? null,
            BannerStatus::from($validated['status']),
            $request->file('image')
        );

        return to_route('banners.index')->with('success', 'Banner created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        // Not used
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner): Response
    {
        return Inertia::render('banners/Edit', [
            'banner' => (new BannerResource($banner))->resolve(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerRequest $request, Banner $banner, UpdateBanner $updateBanner)
    {
        $validated = $request->validated();

        $updateBanner->execute(
            $banner,
            $validated['title'],
            $validated['target_url'],
            $validated['link_text'] ?? null,
            BannerStatus::from($validated['status']),
            $request->file('image')
        );

        return to_route('banners.index')->with('success', 'Banner updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner, DeleteBanner $deleteBanner)
    {
        $deleteBanner->execute($banner);

        return to_route('banners.index')->with('success', 'Banner deleted successfully.');
    }
}
