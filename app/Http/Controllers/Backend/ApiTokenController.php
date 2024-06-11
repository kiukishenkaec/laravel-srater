<?php

namespace App\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Yajra\DataTables\DataTables;

class ApiTokenController extends Controller
{
    use Authorizable;

    public $module_title;
    public $module_name;
    public $module_path;
    public $module_icon;
    public $module_model;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'API Tokens';
        // Module name
        $this->module_name = 'api-tokens';
        // Directory path of the module
        $this->module_path = 'backend';
        // Module icon
        $this->module_icon = 'fa-solid fa-key';
        // Module model name, path
        $this->module_model = "Laravel\Sanctum\PersonalAccessToken";
    }

    public function index()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);
        $module_action = 'List';

        $page_heading = ucfirst($module_title);
        $title = $page_heading.' '.ucfirst($module_action);

        $$module_name = $module_model::paginate();

        logUserAccess($module_title.' '.$module_action);

        return view(
            "{$module_path}.{$module_name}.index",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', 'page_heading', 'title')
        );

    }

    public function create()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_action = 'Create';

        $users = User::all();
        return view(
            "{$module_path}.{$module_name}.create",
            compact('module_title',
                'module_name',
                'module_path',
                'module_action',
                'module_icon', 'users'));
    }

    public function index_data()
    {
        $module_name = $this->module_name;
        $module_model = $this->module_model;

        $$module_name = $module_model::select('id', 'tokenable_type', 'tokenable_id', 'name', 'last_used_at');

        return Datatables::of($$module_name)
            ->editColumn('name', '<strong>{{$name}}</strong>')
            ->orderColumns(['id'], '-:column $1')
            ->make(true);
    }

    /**
     * Retrieves a list of items based on the search term.
     *
     * @param  Request  $request  The HTTP request object.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the list of items.
     *
     * @throws None
     */
    public function index_list(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_model = $this->module_model;

        $module_action = 'Index List';

        $term = trim($request->q);

        if (empty($term)) {
            return response()->json([]);
        }

        $query_data = $module_model::where('name', 'LIKE', "%{$term}%")->limit(10)->get();

        $$module_name = [];

        foreach ($query_data as $row) {
            $$module_name[] = [
                'id' => $row->id,
                'text' => $row->name,
            ];
        }

        logUserAccess($module_title.' '.$module_action);

        return response()->json($$module_name);
    }




    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:1',
        ]);

        $user = User::findOrFail($request->user_id);
        $token = $user->createToken($request->name);

        return redirect()->route("{$this->module_path}.{$this->module_name}.index")->with('success', 'Token created successfully!');
    }

    public function destroy($id)
    {
        $token = PersonalAccessToken::findOrFail($id);
        $token->delete();

        return redirect()->route("{$this->module_path}.{$this->module_name}.index")->with('success', 'Token deleted successfully!');
    }
}
