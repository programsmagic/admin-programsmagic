<?php

namespace App\Manager;

use App\Models\Post;

class CommonMgr
{
//For Generating Unique Slug Our Custom function
public function createSlug($title, $id = 0)
{
    // Normalize the title
    $slug = \Str::slug($title, '-');
    // Get any that could possibly be related.
    // This cuts the queries down by doing it once.
    $allSlugs = $this->getRelatedSlugs($slug, $id);
    // If we haven't used it before then we are all good.
    if (! $allSlugs->contains('slug', $slug)){
        return $slug;
    }
    // Just append numbers like a savage until we find not used.
    for ($i = 1; $i <= 10; $i++) {
        $newSlug = $slug.'-'.$i;
        if (! $allSlugs->contains('slug', $newSlug)) {
            return $newSlug;
        }
    }
    throw new \Exception('Can not create a unique slug');
}

protected function getRelatedSlugs($slug, $id = 0)
{
    return Post::select('slug')->where('slug', 'like', $slug.'%')
    ->where('id', '<>', $id)
    ->get();
}
}
