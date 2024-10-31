<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Cms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\FileHelperTrait;
use App\Models\CmsDetail;
use App\Models\HomePageCms;
use Inertia\Inertia;

class CmsController extends Controller
{
    use FileHelperTrait;
    /**
     * function details
     * @date date
     * @param
     * @return view
     * @author Gourab
     */
    public function index()
    {
        try {
            $filters = request()->all('title');
            $pages = Cms::filter(request()->only('title'))
            ->ordering(request()->only('fieldName','shortBy'))
            ->orderBy('id','desc')
            ->paginate(request()->perPage ?? $this->per_page)
            ->withQueryString()->through(fn ($user) => [
                'id' => $user->id,
                'title' => $user->title,
                'slug' => $user->slug,
            ]);
            return Inertia::render('Admin/cms/List',compact('pages','filters'));

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }


    /**
     * function details
     * @date date
     * @param
     * @return view
     * @author Gourab
     */
    public function edit($slug)
    {
        try {

            if($slug == 'about-us')
            {
                $cms = Cms::with('aboutUsContent')->where('slug',$slug)->first();
                return Inertia::render('Admin/cms/AboutUs',compact('cms','slug'));
            }
            if($slug == 'home-page')
            {
                $cms = HomePageCms::first();
                return Inertia::render('Admin/cms/HomePage',compact('cms','slug'));
            }
            else{
                $cms = Cms::where('slug',$slug)->first();
                return Inertia::render('Admin/cms/CreateEdit',compact('cms','slug'));
            }

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }


    /**
     * function details
     * @date date
     * @param
     * @return view
     * @author Gourab
     */
    public function update(Request $request, $slug)
    {

        $page = Cms::find($slug);

        if( $page->slug == 'about-us')
        {
            $result = $this->aboutUsUpdate($request, $page);
            if($result)
                return redirect()->route('admin.cms.index')->with('success', $page->title . ' has been updated');
        }
        else{

            request()->validate([
                'title' => 'required|max:255',
                'content' => 'required'
              ]);


            try{

                $page = Cms::find($slug);

                if($request->file('bannerImage')){

                    $request->validate([
                        'bannerImage' => 'required|mimes:jpeg,jpg,png,gif|max:50000',
                    ]);
                    $file_name = $request->file('bannerImage')->store('cmsBanner');

                    $page->page_banner_image = 'storage/'. $file_name;
                }

                if($request->file('contentImage')){

                    $request->validate([
                        'contentImage' => 'required|mimes:jpeg,jpg,png,gif|max:50000',
                    ]);
                    $file_name = $request->file('contentImage')->store('cmsBanner');

                    $page->content_image1 = 'storage/'. $file_name;
                }

                $page->title = $request->title;
                $page->text_content = $request->content;
                $page->save();

                return redirect()->route('admin.cms.index')->with('success', $page->title . ' has been updated');

            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                return back()->with('error','Server error');
            }

        }

    }

    public function aboutUsUpdate($request, $page)
    {
        request()->validate([
            'title' => 'required|max:255',
            'about_us_content'=>'required',
            'company_values_content'=> 'required',
            'total_sell_text'=> 'required',
            'trusted_buyer_text'=> 'required',
            'rating_text'=> 'required'
          ]);

        try{
            $page->title = $request->title;

            $page->aboutUsContent->about_us_content = $request->about_us_content;
            $page->aboutUsContent->company_values_content = $request->company_values_content;
            $page->aboutUsContent->total_sell_text = $request->total_sell_text;
            $page->aboutUsContent->trusted_buyer_text = $request->trusted_buyer_text;
            $page->aboutUsContent->rating_text = $request->rating_text;


           if($request->file('bannerImage')){

                $request->validate([
                    'bannerImage' => 'required|mimes:jpeg,jpg,png,gif|max:50000',
                ]);
                $file_name = $request->file('bannerImage')->store('cmsBanner');

                $page->page_banner_image = 'storage/'. $file_name;
            }


           if($request->file('photo1')){

                $request->validate([
                    'photo1' => 'required|mimes:jpeg,jpg,png,gif|max:50000',
                ]);
                $file_name = $request->file('photo1')->store('aboutUs');

                $page->aboutUsContent->image1 = 'storage/'. $file_name;
            }
            if($request->file('photo2')){
                $request->validate([
                    'photo2' => 'required|mimes:jpeg,jpg,png,gif|max:50000',
                ]);
                $page->aboutUsContent->image2 = 'storage/'.$request->file('photo2')->store('aboutUs');
            }
            if($request->file('photo3')){
                $request->validate([
                    'photo3' => 'required|mimes:jpeg,jpg,png,gif|max:50000',
                ]);
                $page->aboutUsContent->image3 = 'storage/'.$request->file('photo3')->store('aboutUs');
            }

            $page->aboutUsContent->save();
            $page->save();

            $message =  $page->title . ' has been updated';

            return true;

            // return redirect()->route('admin.cms.index')->with('success', $page->title . ' has been updated');

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error','Server error');
        }
    }

    public function homePageCmsUpdate(Request $request)
    {

        request()->validate([
            'banner_text_one' => 'required|max:255',
            'banner_text_two' => 'required|max:255',
            'banner_description'=>'required',
            'banner_btn_text' => 'required|max:255',
            'sub_banner_text_one' => 'required|max:255',
            'sub_banner_text_two' => 'required|max:255',
            'PopularBrand'=>'required|Array'
          ]);

        // dd($request->all());

        $popular_brandArray = [];
        foreach ($request->PopularBrand as $popular_brand)
        {
            if(isset($popular_brand['new_image']))
            {
               $popular_brand['brand_logo'] = '/storage/'.$popular_brand['new_image']->store('brandLogo');
                unset( $popular_brand['new_image']);
            }

            $popular_brandArray[] = $popular_brand;
        }

        $popular_modelArray = [];
        foreach ($request->PopularModel as $popular_model)
        {
            if(isset($popular_model['new_image']))
            {
               $popular_model['model_logo'] = '/storage/'.$popular_model['new_image']->store('ModelLogo');
                unset( $popular_model['new_image']);
            }

            $popular_modelArray[] = $popular_model;
        }


        $homePage = HomePageCms::first();

        $homePage->banner_text_one = $request->banner_text_one;
        $homePage->banner_text_two = $request->banner_text_two;
        $homePage->banner_description = $request->banner_description;
        $homePage->banner_btn_text = $request->banner_btn_text;
        $homePage->banner_image = $request->banner_image;
        $homePage->sub_banner_image = $request->sub_banner_image;
        $homePage->sub_banner_text_one = $request->sub_banner_text_one;
        $homePage->popular_brand_json = json_encode($popular_brandArray);
        $homePage->popular_models = json_encode($popular_modelArray);


        if($request->file('bannerImage')){
            $request->validate([
                'banner_image' => 'required|mimes:jpeg,jpg,png,gif|max:50000',
            ]);
            $homePage->aboutUsContent->image2 = '/storage/'.$request->file('bannerImage')->store('banner');
        }

        if($request->file('subBannerImage')){
            $request->validate([
                'sub_banner_image' => 'required|mimes:jpeg,jpg,png,gif|max:50000',
            ]);
            $homePage->aboutUsContent->image2 = '/storage/'.$request->file('subBannerImage')->store('banner');
        }

        $homePage->save();

        return redirect()->route('admin.cms.index')->with('success', 'Home page has been updated');

    }
}
