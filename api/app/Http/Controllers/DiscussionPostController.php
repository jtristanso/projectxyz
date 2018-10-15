<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DiscussionPost;
use App\DiscussionAnswer;
use App\DiscussionReaction;
use App\Account;
use App\AccountProfile;
use Carbon\Carbon;
class DiscussionPostController extends ClassWorxController
{
    function __construct(){
      $this->model = new DiscussionPost();
      $this->notRequired = array(
        'payload', 'payload_value'
      );
    }

    public function search(Request $request){
      $data = $request->all();
      $condition = array(
        'condition' => $data['condition'],
        'sort'      => array('created_at' => 'DESC')
      );
      $this->retrieveDB($condition);
      if(sizeof($this->response['data']) > 0){
        $i = 0;
        $result = $this->response['data'];
        foreach ($result as $key) {
          $this->response['data'][$i]['account'] = $this->getAccountDetails($result[$i]['account_id']);
          $this->response['data'][$i]['tags_array'] = $this->tags($result[$i]['tags']);
          $this->response['data'][$i]['answer'] = $this->getAnswers($result[$i]['id']);
          $this->response['data'][$i]['created_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $result[$i]['created_at'])->copy()->tz('Asia/Manila')->format('D F j, Y @ g:i A');
          $this->response['data'][$i]['reactions'] = $this->getTotalReactions($result[$i]['id']);
          $this->response['data'][$i]['account_reaction_flag'] = $this->getAccountReactionFlag($data['account_id'], $result[$i]['id']);
          $this->response['data'][$i]['new_answer'] = null;
          $this->response['data'][$i]['editable'] = (intval($data['account_id']) == intval($result[$i]['account_id'])) ? true : false; 
          $this->response['data'][$i]['edit_flag'] = false;
          $tags = PHP_EOL.PHP_EOL.'#'.substr($result[$i]['tags'], 0, strlen($result[$i]['tags']) - 1);
          $this->response['data'][$i]['edit_text'] = $result[$i]['text'].$tags;
          $i++;
        }
      }
      return $this->response();
    }

    public function retrieve(Request $request){
      $data = $request->all();
      $sort = array(
        'condition' => $data['condition'],
        'sort' => array('created_at' => 'DESC')
      );
      $this->retrieveDB($sort);
      if(sizeof($this->response['data']) > 0){
        $i = 0;
        $result = $this->response['data'];
        foreach ($result as $key) {
          $this->response['data'][$i]['account'] = $this->getAccountDetails($result[$i]['account_id']);
          $this->response['data'][$i]['tags_array'] = $this->tags($result[$i]['tags']);
          $this->response['data'][$i]['answer'] = $this->getAnswers($result[$i]['id']);
          $this->response['data'][$i]['created_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $result[$i]['created_at'])->copy()->tz('Asia/Manila')->format('D F j, Y @ g:i A');
          $this->response['data'][$i]['reactions'] = $this->getTotalReactions($result[$i]['id']);
          $this->response['data'][$i]['account_reaction_flag'] = $this->getAccountReactionFlag($data['account_id'], $result[$i]['id']);
          $this->response['data'][$i]['new_answer'] = null;
          $this->response['data'][$i]['editable'] = (intval($data['account_id']) == intval($result[$i]['account_id'])) ? true : false; 
          $this->response['data'][$i]['edit_flag'] = false;
          $tags = PHP_EOL.PHP_EOL.'#'.substr($result[$i]['tags'], 0, strlen($result[$i]['tags']) - 1);
          $this->response['data'][$i]['edit_text'] = $result[$i]['text'].$tags;
          $i++;
        }
      }
      return $this->response();
    }

    public function getAnswers($discussionPostId){
      $result = DiscussionAnswer::where('discussion_post_id', '=', $discussionPostId)->get();
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $result[$i]['account'] = $this->getAccountDetails($result[$i]['account_id']);
          $result[$i]['edit_flag'] = false;
          // $result[$i]['created_at'] = 
          $i++;
        }
        return $result;
      }
      return null;
    }

    public function getAccountDetails($accountId){
      $result = Account::where('id', '=', $accountId)->get();
      if(sizeof($result) > 0){
        $profile = AccountProfile::where('account_id', '=', $accountId)->orderBy('created_at', 'DESC')->get();
        $result[0]['profile'] = (sizeof($profile) > 0) ? $profile[0] : null;
        return $result[0];
      }else{
        return null;
      }
    }

    public function tags($tags){
      if(strpos($tags, '#')){
        $result = explode('#', $tags);
        $response = array();
        $i = 0;
        foreach ($result as $key) {
          $response[$i]['tag'] = $key;
          $i++;
        }
        return $response;
      }
      return null;
    }

    public function getTotalReactions($discussionPostId){
      return DiscussionReaction::where('discussion_post_id', '=', $discussionPostId)->count();
    }

    public function getAccountReactionFlag($accountId, $discussionPostId){
      $result = DiscussionReaction::where('account_id', '=', $accountId)->where('discussion_post_id', '=', $discussionPostId)->get();
      return (sizeof($result) > 0) ? true : false;
    }
}
