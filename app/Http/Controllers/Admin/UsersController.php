<?php

namespace App\Http\Controllers\Admin;

use App\Http\Business\TagsBusiness;
use App\Http\Business\UsersBusiness;
use App\Http\Controllers\Helper;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Exceptions\ErrorHmtlOrJsonException;
use App\Http\Requests\StoreTagsRequest;

class UsersController extends Controller
{
    /**
     * @param Request $request
     * @param UsersBusiness $users_business
     * @param TagsBusiness $tags_business
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(Request $request, UsersBusiness $users_business, TagsBusiness $tags_business)
    {
        $condition = $request->all();

        $list = $users_business->index(array_merge($condition, [
            'account_id' => session('wechat_account.id', 0),
        ]));

        // 获取用户标签组列表
        $tags_list = $tags_business->index([
            'all'        => true,
            'account_id' => session('wechat_account.id', 0),
        ]);

        return view('admin.users.index', compact('list', 'condition', 'tags_list'));
    }

    /**
     * @param TagsBusiness $tags_business
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTagIndex(TagsBusiness $tags_business)
    {
        $list = $tags_business->index([
            'account_id' => session('wechat_account.id', 0),
            'all'        => true
        ]);

        return view('admin.users.tag.index', compact('list'));
    }

    /**
     * @param StoreTagsRequest $request
     * @param TagsBusiness $tags_business
     * @return array
     * @throws ErrorHmtlOrJsonException
     */
    public function postStoreTag(StoreTagsRequest $request, TagsBusiness $tags_business)
    {
        $name = $request->get('name','');

        $result = $tags_business->store($name);

        return $this->jsonFormat($result);
    }

    /**
     * @param Request $request
     * @param TagsBusiness $tags_business
     */
    public function postUpdateTag(Request $request, TagsBusiness $tags_business)
    {
        $name   = $request->get('name','');
        $tag_id = $request->get('tag_id', 0);

        $result = $tags_business->update($tag_id, session('wechat_account.id', 0), $name);


        return $this->jsonFormat($result);
    }

    /**
     * @param Request $request
     * @param TagsBusiness $tags_business
     */
    public function postDeleteTag(Request $request, TagsBusiness $tags_business)
    {
        $tag_id        = $request->get('tag_id', 0);
        $wechat_tag_id = $request->get('wechat_tag_id', 0);

        $result = $tags_business->delete($tag_id, session('wechat_account.id'), $wechat_tag_id);

        return $this->jsonFormat($result);
    }

}
