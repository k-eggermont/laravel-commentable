<?php

namespace Keggermont\Commentable\Models;

use Illuminate\Database\Eloquent\Model;
use Keggermont\Commentable\Traits\Commentable;

class Comment extends Model {

    use Commentable;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $with = ["childrens","author"];

    public function commentableModel() {
        return config('laravel-commentable.model');
    }

    public function childrens() {
        return $this->morphMany($this->commentableModel(), 'commentable');
    }


    /**
     * @return mixed
     */
    public function commentable() {
        return $this->morphTo();
    }

    /**
     * @return mixed
     */
    public function author() {
        return $this->morphTo('author');
    }

    /**
     * @param Model $commentable
     * @param $data
     * @param Model $author
     *
     * @return static
     */
    public function createComment(Model $commentable, $data, Model $author) {
        return $commentable->comments()->create(array_merge($data, ['author_id' => $author->getAuthIdentifier(), 'author_type' => get_class($author),]));
    }

    /**
     * @param $id
     * @param $data
     *
     * @return mixed
     */
    public function updateComment($id, $data) {
        return (bool)static::find($id)->update($data);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteComment($id) {
        return (bool)static::find($id)->delete();
    }
}