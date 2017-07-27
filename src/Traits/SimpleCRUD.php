<?php
namespace Larakuy\GenCRUD\Traits;

use Illuminate\Http\Request;

trait SimpleCRUD {
    /* Remove Repeatitive Sequence Like Crud Controller Function */
    protected $repositories;
    protected $viewPath;
    protected $title;
    protected $routePath;
    
    public function init(){
        if (!isset($this->repositories)){
            throw new \Exception("Need Register Reporitories", 1);
        }
        if (!($this->repositories instanceof \Larakuy\GenCRUD\Repositories\BaseRepositories)){
            throw new \Exception("Reporsitories Is Not Belongs To \Larakuy\GenCRUD\Repositories\BaseRepositories", 1);
        }
    }
    public function index(Request $request){
        $data['page_name'] = $this->title." List";
        $data['page_description'] = "Control ".$this->title ;

		$data['data'] = $this->repositories->getPaginateSearch(10);

        return view($this->viewPath. '.index', $data);
    }

    public function create(Request $request)
    {
        $data['page_name'] = $this->title." Create";
        $data['page_description'] = "Create ".$this->title ;

		$data['model'] = $this->repositories->newInstance();

        return view($this->viewPath. ".create", $data);
    }

    public function edit(Request $request, $id)
    {
        $data['page_name'] = $this->title." Edit";
        $data['page_description'] = "Edit ".$this->title ;

		$data['model'] = $this->repositories->getById($id);

        return view($this->viewPath. '.edit', $data);
    }

    public function store(Request $request)
    {
        $data = $this->repositories->storeWithId((array) $request->all(), $request);
        return redirect()->route($this->routePath. ".index")->with('message', "Data $this->title ,  Berhasil disimpan.")->with('alert', 'alert-success');
    }

    public function update(Request $request, $id)
    {
        $data = $this->repositories->update($id,  $request->all(), $request);
        return redirect()->route($this->routePath. ".index")->with('message', "Data $this->title ,  Berhasil diubah.")->with('alert', 'alert-success');
    }

    public function delete(Request $request)
    {
        $data = $this->repositories->destroy($request->id);
        return redirect()->route($this->routePath. ".index")->with('message', "Data $this->title ,  Berhasil dihapus.")->with('alert', 'alert-success');
    }

    public function deleteMultiple(Request $request){
		$id = $request->input('_ids') or [];
		if(empty($id)){
			return redirect()->route($this->routePath. ".index")->with('message', 'Tidak ada data yang dihapus.')->with('alert', 'alert-warning');
		}
		foreach($id as $id){
			$user = $this->repositories->destroy($id);
		}
		return redirect()->route($this->routePath. ".index")->with('message', 'User berhasil dihapus.')->with('alert', 'alert-success');
	}

}