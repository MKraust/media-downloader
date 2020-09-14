<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Services;

class WebhookController extends Controller
{
    /** @var Services\Media\PostDownloadProcessor */
    private $_postDownloadProcessor;

    public function __construct(Services\Media\PostDownloadProcessor $postDownloadProcessor) {
        $this->_postDownloadProcessor = $postDownloadProcessor;
    }

    public function finishDownload(Requests\Webhook\FinishDownload $request) {
        $this->_postDownloadProcessor->processFinishedDownload($request->hash, $request->path);
    }
}
