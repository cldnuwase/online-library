<?php

namespace App\Session;

use Illuminate\Session\DatabaseSessionHandler as BaseDatabaseSessionHandler;

class DatabaseSessionHandler extends BaseDatabaseSessionHandler
{
    /**
     * Get the default payload for the session.
     *
     * @param  string  $data
     * @return array
     */
    protected function getDefaultPayload($data)
    {
        return [
            'payload' => base64_encode($data),
            'last_activity' => $this->currentTime(),
            'user_id' => null,
            'ip_address' => request()->ip(),
        ];
    }
}
