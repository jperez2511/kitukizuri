<?php

namespace Icebearsoft\Kitukizuri\Resources;

use Illuminate\Database\Eloquent\Model;

/** Shared persistence boundary. Eloquent events, casts and observers still run. */
class ModelPersistence
{
    public function save(Model $model)
    {
        return $model->save();
    }

    public function delete(Model $model)
    {
        return $model->delete();
    }
}
