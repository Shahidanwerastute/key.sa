<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Settings;
use App\Models\Front\Page;
use Illuminate\Http\Request;
use App\Models\Admin\Booking;
use App\Models\Admin\MetaPage;
use App\Helpers\custom;
use Excel;
use Auth;
use Session;
use DB;
use Mockery\Exception;


use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Writer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MetaPageController extends Controller
{

    public function index()
    {
        $data['main_section'] = 'metapages';
        $data['inner_section'] = 'metapages';

        return view('admin/meta_pages/pages_meta', $data);
    }

    public function save(Request $request)
    {

        $validatedData = $request->validate([
            'page' => 'required|string|max:255',
            'eng_meta_title' => 'nullable|string|max:255',
            'arb_meta_title' => 'nullable|string|max:255',
            'eng_meta_description' => 'nullable|string|max:255',
            'arb_meta_description' => 'nullable|string|max:255',
            'eng_meta_keyword' => 'nullable|string|max:255',
            'arb_meta_keyword' => 'nullable|string|max:255',
        ]);

        $page = MetaPage::updateOrCreate(
            ['page' => $validatedData['page']],
            [
                'eng_meta_title' => $validatedData['eng_meta_title'],
                'arb_meta_title' => $validatedData['arb_meta_title'],
                'eng_meta_description' => $validatedData['eng_meta_description'],
                'arb_meta_description' => $validatedData['arb_meta_description'],
                'eng_meta_keyword' => $validatedData['eng_meta_keyword'],
                'arb_meta_keyword' => $validatedData['arb_meta_keyword'],
            ]
        );

        return redirect()->back()->with('success', 'Meta data have been saved');
    }

    public function getMetaData(Request $request)
    {
        $selectedPage = $request->page;
        $metaData = MetaPage::where('page', $selectedPage)->first();


        if ($metaData) {
            return response()->json(['metaData' => $metaData]);
        } else {
            return response()->json(['metaData' => null]);
        }
    }


}

