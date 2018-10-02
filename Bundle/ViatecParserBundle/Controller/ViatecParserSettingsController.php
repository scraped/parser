<?php

namespace Onixcat\Bundle\ViatecParserBundle\Controller;

use Onixcat\Bundle\ViatecParserBundle\Entity\ParserViatecSettings;
use Onixcat\Bundle\ViatecParserBundle\Form\ParserViatecSettingsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ViatecParserSettingsController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editSettingsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $settings = $em->getRepository(ParserViatecSettings::class)->find(1);
        if (!$settings) {
            $this->addFlash('error', 'No settings found for viatec parser');
        }
        $editForm = $this->createForm(ParserViatecSettingsType::class, $settings);
        $editForm->handleRequest($request);
        if ($request->isMethod('POST')) {
            $em->flush();
            $this->addFlash('success', 'onixcat.ui.viatec_auth_info_success_update');
            return $this->redirectToRoute('ult_admin_viatec_parser_edit_settings');
        }
        return $this->render('OnixcatViatecParserBundle:Settings:settings.html.twig',
            [
                'form' => $editForm->createView(),
            ]);
    }
}
