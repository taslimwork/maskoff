<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Storage;

trait HasLogo{
        /**
     * Get the URL to the Group Logo photo.
     *
     * @return string
     */
    public function getGroupLogoUrlAttribute()
    {
        return $this->group_logo
                    ? Storage::disk($this->logoDisk())->url($this->group_logo)
                    : $this->defaultLogoUrl();
    }

    /**
     * Get the URL to the Event Logo photo.
     *
     * @return string
     */
    public function getEventLogoUrlAttribute()
    {
        return $this->image
                    ? Storage::disk($this->logoDisk())->url($this->image)
                    : $this->defaultLogoUrl();
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultLogoUrl()
    {
        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF&size=228';
    }

    /**
     * Get the disk that profile photos should be stored on.
     *
     * @return string
     */
    protected function logoDisk()
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : 'public';
    }
}