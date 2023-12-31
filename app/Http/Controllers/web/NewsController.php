<?php


namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Services\NewsService;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class NewsController extends Controller
{

    private NewsService $newsService;


    public function __construct()
    {
        $this->newsService = new NewsService();
    }


    public function index(Request $request)
    {
        $data = $this->newsService->findAll($request->get('page'));

        // dd($data);

        // $counts_by_day_active = array_values($data['count_by_active']);
        // $counts_by_day_non_active = array_values($data['count_by_day_nonactive']);
        // $counts_by_day_all = array_values($data['count_all']);



        $tempActive = 0;
        $tempNonActive = 0;




        $total = [
            'active' => $tempActive,
            'nonactive' => $tempNonActive
        ];

        return view('admin.berita.berita', [
            'data' => $data,
            'total' => [
                'active' => $total['active'],
                'nonactive' => $total['nonactive'],
                'total' => sizeof($data['data']),
            ],
            'count' => [
                'active' => [],
                'non_active' => [],
                'all' => array_values($data['count_all'])
            ]
        ]);
    }

    public function store(Request $request)
    {

        $rules = [
            'title' => 'required|max:100',
            'description' => 'required',
            'image' => 'required|max:3072|mimes:jpeg,png,jpg',
        ];


        $customMessages = [
            'required' => ':attribute Dibutuhkan.',
            'dimensions' => ':attribute Harus berukuran 16:9'
        ];

        $data = $this->validate($request, $rules, $customMessages);

        $response = $this->newsService->addNews($data, $data['image']);

        Alert::success('Sukses', $response['message']);
        return back();
    }

    public function update(Request $request)
    {

        $image = $request->file('image-update');
        $checked = false;
        $isChecked = $request->input('active');
        if (isset($isChecked)) {
            $checked = true;
        }
        $rules = [];
        if (isset($image)) {
            $rules = [
                'title' => 'required|max:100',
                'description' => 'required|max:10000',
                'image-update' => 'required|max:3072|mimes:jpeg,png,jpg',
                'id' => 'required'
            ];
        } else {
            $rules = [
                'title' => 'required|max:100',
                'description' => 'required|max:10000',
                'id' => 'required'
            ];
        }
        $customMessages = [
            'required' => ':attribute Dibutuhkan.',
        ];
        $data = $this->validate($request, $rules, $customMessages);
        $data['active'] = $checked;
        $response = $this->newsService->update($data, $image, $data['id']);
        Alert::success('Success', $response['message']);
        return back();
    }

    public function delete(Request $request)
    {
        $rules = [
            'id' => 'required'
        ];
        $customMessages = [
            'required' => ':attribute Dibutuhkan.',
        ];
        $data = $this->validate($request, $rules, $customMessages);
        return $this->newsService->delete($data['id']);
    }

    public function findAllBlog(Request $request)
    {
        $data = $this->newsService->findAll($request->get('page'));
        $latest = $this->newsService->findLastInserted();
        unset($data['count_all']);
        return view('landing-page.blog', ['data' => $data, 'lastest' => $latest]);
    }

    public function detailBlog($id)
    {
    }
}
