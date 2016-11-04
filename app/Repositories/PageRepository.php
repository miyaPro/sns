<?php

namespace App\Repositories;

use App\Page;

class PageRepository extends BaseRepository
{
    /**
     * Create a new UserRepository instance.
     *
     * @return void
     */
    public function __construct(Page $page)
    {
        $this->model = $page;
    }
    /**
     * Save the User.
     * @param  Array  $inputs
     * @return page
     */
    private function save($page, $inputs)
    {
        $page->access_token = $inputs['access_token'];
        $page->name                      = $inputs['name'];
        $page->screen_name               = @$inputs['screen_name'];
        $page->category                  = @$inputs['category'];
        $page->link                      = @$inputs['link'];
        $page->avatar_url                = @$inputs['avatar_url'];
        $page->banner_url                = @$inputs['banner_url'];
        $page->location                  = @$inputs['location'];
        $page->description               = @$inputs['description'];
        $page->created_time              = @$inputs['created_time'];
        $page->save();
        return $page;
    }
    /**
     * Create a company.
     *
     * @param  array  $inputs
     * @param  int    $confirmation_code
     */
    public function store($inputs, $auth_id)
    {
        $page = new $this->model;
        $page->auth_id   = $auth_id;
        $page->sns_page_id   = $inputs['sns_page_id'];
        $page = $this->save($page, $inputs);
        return $page;
    }
    /**
     * Update a token.
     *
     * @param  array  $inputs
     * @return page
     */
    public function update($page, $inputs)
    {
        $page = $this->save($page, $inputs);
        return $page;
    }

    public function getPageId($sns_page_id) {
        $model = new $this->model();
        $model = $model->where('sns_page_id', $sns_page_id)->first();
        $id = $model['id'];
        return $id;
    }

    public function getPage($auth_id, $sns_page_id)
    {
        $model = new $this->model();
        $model = $model->where('auth_id', $auth_id)
                       ->where('sns_page_id', $sns_page_id);
        return $model->first();
    }
}
