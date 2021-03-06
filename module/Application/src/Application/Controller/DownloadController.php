<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Filter\Compress;

class DownloadController extends AbstractActionController
{
    /**
     * @var array
     */
    private $applicationConfig;

    /**
     * Construct applicationConfig
     *
     * @param array $applicationConfig
     */
    public function __construct(array $applicationConfig)
    {
        $this->applicationConfig = $applicationConfig;
    }

    /**
     * Download module
     *
     * /download/:module
     */
    public function learnmoduleAction()
    {
        $module = $this->params()->fromRoute('module', '');
        $compress = $this->params()->fromRoute('compress', 'zip');
        $modules           = $this->applicationConfig['modules'];

        $response = $this->getResponse();
        if (in_array($module, $modules)) {
            $currDateTime = date('Y-m-dHis');

            $fileToArchive  = $module.'.'.(($compress == 'zip') ? 'zip' : 'bz2');
            $archive        = $fileToArchive.'-'.$currDateTime;
            $filter     = new Compress([
                'adapter' => ($compress == 'zip') ? 'Zip' : 'Bz2',
                'options' => [
                    'archive' =>  './data/'.$archive,
                ],
            ]);
            $compressed = $filter->filter('./module/'.$module);

            //setting response header....
            $response->getHeaders()->addHeaderLine('Content-Disposition', 'attachment; filename="'.$fileToArchive.'"');
            $response->getHeaders()->addHeaderLine('Content-Length', filesize($compressed));
            // set response with get content of file
            $response->setContent(file_get_contents($compressed));

            //remove file after no need
            @unlink('./data/'.$archive);

            return $response;
        }

        return new ViewModel();
    }
}
