<?php
namespace Larakuy\GenCRUD\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepositories
{
    /**
     * The Model name.
     *
     * @var \Illuminate\Database\Eloquent\Model;
     */
    protected $model;

    

    /**
     * Paginate the given query.
     *
     * @param The number of models to return for pagination $n integer
     *
     * @return mixed
     */
    public function newInstance()
    {
        return new $this->model;
    }


    /**
     * Paginate the given query.
     *
     * @param The number of models to return for pagination $n integer
     *
     * @return mixed
     */
    public function getPaginate($n)
    {
        return $this->model->paginate($n);
    }

    /**
     * Paginate the given query.
     *
     * @param The number of models to return for pagination $n integer
     *
     * @return mixed
     */
    public function getPaginateSearch($n, $searchString = "", $searchIn = array())
    {
        $search = $this->model;
        if ($searchString != "") {
            foreach ($searchIn as $value) {
                $search = $search->orWhere($value, 'like', $searchString. "%");
            }
        }
        return $search->paginate($n);
    }

    /**
     * Create a new model and return the instance.
     *
     * @param array $inputs
     *
     * @return Model instance
     */
    public function store(array $inputs, $request = null, $imageField = array())
    {
        foreach($imageField as $filedName) {
            if ($request && $request->hasFile($filedName)) {
                $ext = $request->file($filedName)->extension();
                $inputs[$filedName] =  $request->file($filedName)->store('images');
            }else{
                unset($inputs[$filedName]);
            }
        }
        return $this->model->create($inputs);
    }

    public function storeWithId(array $inputs, $request = null, $imageField = array())
    {
        foreach($imageField as $filedName) {
            if ($request && $request->hasFile($filedName)) {
                $ext = $request->file($filedName)->extension();
                $inputs[$filedName] =  $request->file($filedName)->store('images');
            }else{
                unset($inputs[$filedName]);
            }
        }
        $inputs['id'] = str_random(32);
        return $this->model->create($inputs);
    }

    /**
     * Create a new model and return the instance.
     *
     * @param array $inputs
     *
     * @return Model instance
     */
    public function updateOrCreate(array $attrs, array $inputs)
    {
        return $this->model->updateOrCreate($attrs, $inputs);
    }

    /**
     * FindOrFail Model and return the instance.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Update the model in the database.
     *
     * @param $id
     * @param array $inputs
     */
    public function update($id, array $inputs, $request = null, $imageField = array())
    {
        foreach($imageField as $filedName) {
            if ($request && $request->hasFile($filedName)) {
                $ext = $request->file($filedName)->extension();
                $inputs[$filedName] =  $request->file($filedName)->store('images');
            }else{
                unset($inputs[$filedName]);
            }
        }
        $this->getById($id)->update($inputs);
    }

    /**
     * Delete the model from the database.
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->getById($id)->delete();
    }
}