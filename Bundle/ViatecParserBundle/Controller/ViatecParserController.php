<?php

namespace Onixcat\Bundle\ViatecParserBundle\Controller;

use Onixcat\Bundle\ViatecParserBundle\Helpers\FilesystemHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ViatecParserController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viatecParserScriptAction()
    {
        $shell = $this->get('onixcat_shell');

        return $this->render('OnixcatViatecParserBundle:Command:viatecParserScript.html.twig', [
            'commands' => $shell->getCommandsByMask('~viatec_parser~'),
        ]);

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function filesListAction()
    {
        try{
            $files = $this->get(FilesystemHelper::class)->getFilesList();
            return $this->render('OnixcatViatecParserBundle:Files:filesList.html.twig', ['files' => $files]);
        }catch(\Exception $e){
            $this->addFlash('info', $e->getMessage());
            return $this->render('OnixcatViatecParserBundle:Files:filesList.html.twig');
        }
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadFileAction(Request $request)
    {
        $fileName = $request->get('name');

        $file = $this->get(FilesystemHelper::class)->downloadFile($fileName);

        return $this->file($file);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteFileAction(Request $request)
    {
        $fileName = $request->get('name');

        $this->get(FilesystemHelper::class)->deleteFile($fileName);

        $this->addFlash('success', 'onixcat.ui.viatec_success_delete_file');

        return $this->redirectToRoute('ult_admin_viatec_parser_files_list');
    }
}
