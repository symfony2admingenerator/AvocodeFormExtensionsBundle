<?php
namespace Avocode\FormExtensionsBundle\Storage;

use Avocode\FormExtensionsBundle\Storage\FileStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LocalFileStorage implements FileStorageInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var string
     */
    private $temporaryDirectory;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session, $tempDir = null)
    {
        $this->session = $session;
        $this->temporaryDirectory = $tempDir ?: sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'ave' . DIRECTORY_SEPARATOR . 'collectionupload';
    }

    /**
     * (non-PHPdoc)
     * @see \Avocode\FormExtensionsBundle\Storage\FileStorageInterface::storeFiles()
     */
    public function storeFiles(array $files)
    {
        $handledFiles = array();
        $sessionFiles = $this->session->get('ave_collectionUpload_files', array());

        foreach ($files as $file) {
            $handledFile = new \stdClass();
            $uid = uniqid();
            $sessionFiles[$uid] = sha1(uniqid(mt_rand(), true)).'.'.$file->guessExtension(); // filename
            $file->move($this->temporaryDirectory . DIRECTORY_SEPARATOR . $uid, $sessionFiles[$uid]);

            $handledFile->name = $file->getClientOriginalName();
            $handledFile->size = $file->getSize();
            $handledFile->type = $file->getClientMimeType();
            $handledFile->uid  = $uid;
            $handledFiles[] = $handledFile;
        }
        $this->session->set('ave_collectionUpload_files', $sessionFiles);

        return $handledFiles;
    }

}
