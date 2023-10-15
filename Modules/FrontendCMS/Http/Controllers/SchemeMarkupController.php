<?php

namespace Modules\FrontendCMS\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FrontendCMS\Http\Requests\CreateDynamicPageRequest;
use Modules\FrontendCMS\Http\Requests\UpdateDynamicPageRequest;
use Modules\FrontendCMS\Entities\SchemeMarkups;
use Modules\FrontendCMS\Services\DynamicPageService;
use Modules\UserActivityLog\Traits\LogActivity;

class SchemeMarkupController extends Controller
{
    public function index()
    {
        try {
            $pageList = SchemeMarkups::get();
            return view('frontendcms::scheme_markups.index', compact('pageList'));
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }
    }

    public function create()
    {
        try {
            return view('frontendcms::scheme_markups.components.create');
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'json' => 'required'
            ]);

            SchemeMarkups::create([
                'title' => $request->title,
                'json' => $request->json,
                'status' => $request->status
            ]);

            Toastr::success(__('common.created_successfully'), __('common.success'));
            LogActivity::successLog('Scheme markup created.');
            return redirect(route('scheme-markup.list'));
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }
    }


    public function edit($id)
    {
        try {
            $scheme_markup = SchemeMarkups::find($id);
            return view('frontendcms::scheme_markups.components.edit', compact('scheme_markup'));
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $row = SchemeMarkups::find($id);

            $data = [];

            if(isset($request->title)){
                $data['title'] = $request->title;
            }
            if(isset($request->json)){
                $data['json'] = $request->json;
            }
            if(isset($request->status)){
                $data['status'] = $request->status;
            }

            $row->update($data);

            Toastr::success(__('common.updated_successfully'),__('common.success'));
            LogActivity::successLog('Scheme Markup updated.');
            return redirect(route('scheme-markup.list'));
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }
    }

    public function destroy(Request $request)
    {
        try {
            SchemeMarkups::where('id', $request->id)->delete();

            LogActivity::successLog('Scheme markup deleted deleted.');
            return $this->loadTableData();
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return response()->json([
                'status'    =>  false,
                'message'   =>  $e->getMessage()
            ]);
        }

    }

    private function loadTableData()
    {
        try {
            $pageList = SchemeMarkups::get();

            return response()->json([
                'TableData' =>  (string)view('frontendcms::scheme_markups.components.list', compact('pageList'))
            ]);
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.operation_failed'));
            return back();
        }
    }

    public function status(Request $request)
    {
        try {
            $data = [
                'status' => $request->status == 1 ? 0 : 1
            ];
            SchemeMarkups::where('id', $request->id)->update($data);
            LogActivity::successLog('Scheme markup status changed.');
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage());
            return $e->getMessage();
        }
        return $this->loadTableData();
    }
}
