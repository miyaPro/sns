<?php
/**
 * Created by PhpStorm.
 * User: le.van.hai
 * Date: 10/20/2016
 * Time: 3:40 PM
 */

namespace App\Repositories;

use App\PageDetailInstagram;

class PageDetailInstagramRepository extends BaseRepository
{
    /**
     * Create a new UserRepository instance.
     *
     * @return void
     */
    public function __construct(PageDetailInstagram $pageDetailInstagram)
    {
        $this->model = $pageDetailInstagram;
    }

    /**
     * Save the User.
     * @param  Array  $inputs
     * @return void
     */
    private function save($pageDetailInstagram, $inputs)
    {
        $pageDetailInstagram['new_follow'] = $inputs['new_follow'];
        $pageDetailInstagram['new_post'] = $inputs['new_post'];
        $pageDetailInstagram['new_like'] = $inputs['new_like'];
        $pageDetailInstagram->save();
    }

    /**
     * Create a company.
     *
     * @param  array  $inputs
     * @param  int    $confirmation_code
     */
    public function store($inputs)
    {
        $pageDetailInstagram = new $this->model;
        $pageDetailInstagram['page_id'] = $inputs['page_id'];
        $pageDetailInstagram['date'] = $inputs['date'];
        $this->save($pageDetailInstagram, $inputs);
    }

    /**
     * Update a user.
     *
     * @param  array  $inputs
     * @return void
     */
    public function update($pageDetailInstagram, $inputs)
    {
        $this->save($pageDetailInstagram, $inputs);
    }
}