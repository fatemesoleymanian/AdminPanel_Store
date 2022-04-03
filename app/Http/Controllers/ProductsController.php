<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\productcolors;
use App\Models\ProductImage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function show(Request $request)
    {
        //only show products who created currently and in the past
//        $product = Product
//            ::where(['published_at', '<', now(),'visible',true]);
//        return Product::with(['category'])->orderByDesc('id')->get();
        return Product::with(['category'])->orderByDesc('id')->paginate(2);
    }
    public function showDetails($id)
    {
        $product = Product::with(['category', 'brand','color'])->where('id',$id)->first();
        return $product;
    }
    public function add(Request $request)
    {
        Validator::validate($request->all(),[
            'name'=>'required',
            'model'=>'required',
            'description'=>'required',
            'guarantee'=>'required',
            'visible'=>'required',
            'inventory'=>'required',
            'brand_id'=>'required',
            'color_id'=>'required',
            'category_id'=>'required',
            'images_id'=>'required',
            'prices'=>'required',
            'publishDate'=>'required',
        ],
            [
            'name.required'=>'لطفا نام محصول را وارد کنید.',
            'model.required'=>'لطفا مدل محصول را وارد کنید.',
            'description.required'=>'لطفا توضیحات محصول را وارد کنید.',
            'guarantee.required'=>'لطفا مدت زمان گارانتی محصول را وارد کنید.',
            'visible.required'=>'لطفا وضعیت نمایش محصول را وارد کنید.',
            'inventory.required'=>'لطفا موجودی محصول را وارد کنید.',
            'brand_id.required'=>'لطفا برند محصول را وارد کنید.',
            'color_id.required'=>'لطفا حداقل یک رنگ برای محصول انتخاب کنید.',
            'category_id.required'=>'لطفا دسته بندی محصول را وارد کنید.',
            'images_id.required'=>'لطفا حداقل یک عکس برای محصول انتخاب کنید.',
            'prices.required'=>'لطفا حداقل یک قیمت برای محصول انتخاب کنید.',
            'publishDate.required'=>'لطفا رئز ایجاد را انتخاب کنید.',
        ]);

        $categories = $request->category_id;
        $colors = $request->color_id;
        $images = $request->images_id;

        $productCategories = [];
        $productColors = [];
        $productImages = [];
        DB::beginTransaction();
        try {
            if ($request->publishDate) {
                $product = Product::create([
                    'name' => $request->name,
                    'model' => $request->model,
                    'description' => $request->description,
                    'guarantee' => $request->guarantee,
                    'visible' => $request->visible,
                    'inventory' => $request->inventory,
                    'brand_id' => $request->brand_id,
                    'details' => $request->details,
                    'extraProperty' => $request->extraProperty,
                    'updated_at'=> new Carbon($request->publishDate)
                ]);
            }else
            {
                $product = Product::create([
                    'name' => $request->name,
                    'model' => $request->model,
                    'description' => $request->description,
                    'guarantee' => $request->guarantee,
                    'visible' => $request->visible,
                    'inventory' => $request->inventory,
                    'brand_id' => $request->brand_id,
                    'details' => $request->details,
                    'extraProperty' => $request->extraProperty
                ]);
            }
            // insert product categories
            ProductCategory::create([
                'product_id'=>$product->id,
                'category_id'=>$request->category_id,
            ]);
            // insert product colors
            foreach ($colors as $c) {
                array_push($productColors, ['color_id' => $c, 'product_id' => $product->id]);
            }
            productcolors::insert($productColors);
            // insert product prices
            Price::create([
                'amount'=>$request->input('prices.amount'),
                'off'=>$request->input('prices.off'),
                'special'=>$request->input('prices.special'),
                'discount'=>$request->input('prices.discount'),
                'product_id'=>$product->id,
            ]);
            // insert product images
            foreach ($images as $i) {
                array_push($productImages, ['imageUrl' => $i, 'product_id' => $product->id]);
            }
            ProductImage::insert($productImages);

            DB::commit();
            return 'done';
        }
        catch (\Throwable $th) {
            DB::rollback();
            return $th;
        }

    }
    public function update(Request $request)
    {
        Validator::validate($request->all(),[
            'name'=>'required',
            'model'=>'required',
            'description'=>'required',
            'guarantee'=>'required',
            'visible'=>'required',
            'inventory'=>'required',
            'brand_id'=>'required',
            'color_id'=>'required',
            'category_id'=>'required',
            'images_id'=>'required',
            'prices'=>'required',
            'publishDate'=>'required',
        ],
            [
                'name.required'=>'لطفا نام محصول را وارد کنید.',
                'model.required'=>'لطفا مدل محصول را وارد کنید.',
                'description.required'=>'لطفا توضیحات محصول را وارد کنید.',
                'guarantee.required'=>'لطفا مدت زمان گارانتی محصول را وارد کنید.',
                'visible.required'=>'لطفا وضعیت نمایش محصول را وارد کنید.',
                'inventory.required'=>'لطفا موجودی محصول را وارد کنید.',
                'brand_id.required'=>'لطفا برند محصول را وارد کنید.',
                'color_id.required'=>'لطفا حداقل یک رنگ برای محصول انتخاب کنید.',
                'category_id.required'=>'لطفا دسته بندی محصول را وارد کنید.',
                'images_id.required'=>'لطفا حداقل یک عکس برای محصول انتخاب کنید.',
                'prices.required'=>'لطفا حداقل یک قیمت برای محصول انتخاب کنید.',
                'publishDate.required'=>'لطفا رئز ایجاد را انتخاب کنید.',
            ]);

        $categories = $request->category_id;
        $colors = $request->color_id;
        $images = $request->images_id;

        $productCategories = [];
        $productColors = [];
        $productImages = [];
        DB::beginTransaction();
        try {
            if ($request->publishDate) {
                $product = Product::where('id',$request->id)->update([
                    'name' => $request->name,
                    'model' => $request->model,
                    'description' => $request->description,
                    'guarantee' => $request->guarantee,
                    'visible' => $request->visible,
                    'inventory' => $request->inventory,
                    'brand_id' => $request->brand_id,
                    'details' => $request->details,
                    'extraProperty' => $request->extraProperty,
//                    'updated_at'=> new Carbon($request->publishDate)
                ]);
            }else
            {
                $product = Product::where('id',$request->id)->update([
                    'name' => $request->name,
                    'model' => $request->model,
                    'description' => $request->description,
                    'guarantee' => $request->guarantee,
                    'visible' => $request->visible,
                    'inventory' => $request->inventory,
                    'brand_id' => $request->brand_id,
                    'details' => $request->details,
                    'extraProperty' => $request->extraProperty
                ]);
            }
            // update product categories
            ProductCategory::where('product_id',$request->id)->delete();
            ProductCategory::insert([
                'category_id'=>$request->category_id,
                'product_id'=>$request->id,
            ]);
            // update product colors
            foreach ($colors as $c) {
                array_push($productColors, ['color_id' => $c, 'product_id' => $request->id]);
            }
            productcolors::where('product_id',$request->id)->delete();
            productcolors::insert($productColors);
            // update product prices
            Price::where('product_id',$request->id)->update([
                'amount'=>$request->input('prices.amount'),
                'off'=>$request->input('prices.off'),
                'special'=>$request->input('prices.special'),
                'discount'=>$request->input('prices.discount'),
                'product_id'=>$request->id,
            ]);
            // update product images
            foreach ($images as $i) {
                array_push($productImages, ['imageUrl' => $i, 'product_id' => $request->id]);
            }
            ProductImage::where('product_id',$request->id)->delete();
            ProductImage::insert($productImages);

            DB::commit();
            return 'done';
        }
        catch (\Throwable $th) {
            DB::rollback();
            return $th;
        }

    }
    public function delete(Request $request)
    {
        return Product::where('id',$request->id)->delete();
    }
    public function upload(Request $request)
    {
        Validator::validate($request->all(), [

            'file' => "required|image|mimes:jpg,png,jpeg,gif,svg",
        ], [
            'file.required' => 'لطفا لوگو را انتخاب کنید!',
            'file.image' => 'لطفا لوگو را به درستی انتخاب کنید!',
            'file.mimes' => 'jpeg,jpg,png,svg میباشد فرمت های قابل قبول برای عکس!',
        ]);
        $pic = time() . '.' . $request->file->extension();
        $request->file->move(public_path('images\products'), $pic);
        return response()->json([
            'success'=> 1,
            'file'=>["url"=>"http://localhost:8000/images/products/$pic"]
        ]);
        return $pic;
    }
    public function showImages($id)
    {
        return ProductImage::where('product_id',$id)->get();
    }
    public function showPrices($id)
    {
        return Price::where('product_id',$id)->get();
    }
    public function productNumbers()
    {
       return Product::get()->count();
    }
    public function filter(Request $request)
    {
        $priceSort ='';
        $normalSort = '';
        $model = $request->model;

        $price = $request->price;
        $color_ids=[];
        foreach ($request->color as $c)
        {
            array_push($color_ids,$c);
        }
        $brand=[];
        foreach ($request->brand as $c)
        {
            array_push($brand,$c);
        }
        $category=[];
        foreach ($request->category as $c)
        {
            array_push($category,$c);
        }
        if ($request->sort == 'amount') {
            $normalSort='id';
            $priceSort = $request->sort;
        }
        else {
            $normalSort = $request->sort;
            $priceSort='id';
        }

        $products = Product::with(['category','color','brand','catFilter'])
            ->when($color_ids, function ($q) use ($color_ids){
             $q->whereHas('color',function ($qu) use($color_ids){
                 $qu->whereIn('color_id',$color_ids);
            });
          })
            ->when($brand,function ($q) use ($brand){
             $q->whereIn('brand_id',$brand);

        })
            ->when($category , function ($q) use ($category){
                $q->whereHas('catFilter', function ($query) use ($category){
                    $query->whereIn('category_id',$category);
                });
            })
            ->when($request->inventory , function ($q) {
                $q->where('inventory','>',0);
            })
            ->when($request->model , function ($q) use ($model){
                $q->where('model',$model);
            })
            ->when($request->guarantee , function ($q) {
                $q->where('guarantee','>',0);
            })
            ->orderBy($normalSort,$request->direction)->get();
        $price = DB::table('prices')
            ->when($request->price , function ($q) use ($price){
            $q->where('off',0)->where('amount','>=',$price[0])->where('amount','<=',$price[1])
            ->orWhere('off','!=',0)->where('discount','>=',$price[0])->where('discount','<=',$price[1])
            ->orWhere('special','!=',0)->where('special','>=',$price[0])->where('special','<=',$price[1]);
        })
            ->when($request->off,function ($q){
                $q->where('off','>',0);
            })
            ->when($request->special,function ($q){
                $q->where('special','>',0);
            })
            ->orderBy($priceSort,$request->direction)->get('product_id');

        $prices =[];
        foreach ($price as $p)
        {
            array_push($prices,$p->product_id);
        }
        return $products->whereIn('id',$prices)->toQuery()->paginate(1);
    }
}
