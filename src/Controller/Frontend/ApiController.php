<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 17/08/18
 * Time: 23:21
 */

namespace App\Controller\Frontend;


use App\Entity\Capture;
use App\Services\Capture\NAOCaptureManager;
use App\Services\Capture\NAOShowMap;
use App\Services\NAOPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends Controller
{
    /**
     * @Route("/api/publishedcaptures", name="app_publishedcaptures_list")
     * @Method("GET")
     */
    public function getPublishedCapturesData(NAOCaptureManager $naoCaptureManager, NAOShowMap $naoShowMap)
    {
        $captures = $naoCaptureManager->getPublishedCaptures();

        return $publishedCaptures = $naoShowMap->formatPublishedCaptures($captures);
    }

    /**
     * @Route(path="/api/birdpublishedcaptures/{id}", name="app_birdpublishedcaptures_list", requirements={"id" = "\d+"})
     * @Method("GET")
     */
    public function getBirdPublishedCapturesData($id, NAOCaptureManager $naoCaptureManager, NAOShowMap $naoShowMap)
    {
        $captures = $naoCaptureManager->getBirdPublishedCaptures($id);

        return $publishedCaptures = $naoShowMap->formatPublishedCaptures($captures);
    }

    /**
     * @Route(path="/api/latloncapture/{id}", name="app_publishedcapture", requirements={"id" = "\d+"})
     * @Method("GET")
     */
    public function getLatitudeLongitudeCapture($id, NAOShowMap $naoShowMap)
    {
        return $capture = $naoShowMap->formatCapture($id);
    }

    /**
     * @Route(path="/api/lastcaptures", name="app_lastcaptures_list")
     * @Method("GET")
     */
    public function showLastCpaturesAction(NAOShowMap $naoShowMap, NAOPagination $naoPagination)
    {
        $numberCaptures = $naoPagination->getNbHomeCapturesPerPage();
        $em = $this->getDoctrine()->getManager();
        $lastCaptures = $em->getRepository(Capture::class)->getLastPublishedCaptures($numberCaptures);

        return $naoShowMap->formatPublishedCaptures($lastCaptures);
    }
}
