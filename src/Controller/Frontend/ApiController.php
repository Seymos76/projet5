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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends Controller
{
    /**
     * @Route("/api/publishedcaptures", name="app_publishedcaptures_list", methods={"GET"})
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOShowMap $naoShowMap
     * @return JsonResponse
     */
    public function getPublishedCapturesData(NAOCaptureManager $naoCaptureManager, NAOShowMap $naoShowMap)
    {
        $captures = $naoCaptureManager->getPublishedCaptures();
        return $publishedCaptures = $naoShowMap->formatPublishedCaptures($captures);
    }

    /**
     * @Route(path="/api/birdpublishedcaptures/{id}", name="app_birdpublishedcaptures_list", requirements={"id" = "\d+"}, methods={"GET"})
     * @param $id
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOShowMap $naoShowMap
     * @return JsonResponse
     */
    public function getBirdPublishedCapturesData($id, NAOCaptureManager $naoCaptureManager, NAOShowMap $naoShowMap)
    {
        $captures = $naoCaptureManager->getBirdPublishedCaptures($id);
        return $publishedCaptures = $naoShowMap->formatPublishedCaptures($captures);
    }

    /**
     * @Route(path="/api/latloncapture/{id}", name="app_publishedcapture", requirements={"id" = "\d+"}, methods={"GET"})
     * @param $id
     * @param NAOShowMap $naoShowMap
     * @return JsonResponse
     */
    public function getLatitudeLongitudeCapture($id, NAOShowMap $naoShowMap)
    {
        return $capture = $naoShowMap->formatCapture($id);
    }

    /**
     * @Route(path="/api/lastcaptures", name="app_lastcaptures_list", methods={"GET"})
     * @param NAOShowMap $naoShowMap
     * @param NAOPagination $naoPagination
     * @return JsonResponse
     */
    public function showLastCapturesAction(NAOShowMap $naoShowMap, NAOPagination $naoPagination)
    {
        $numberCaptures = $naoPagination->getNbHomeCapturesPerPage();
        $lastCaptures = $this->getDoctrine()->getRepository(Capture::class)->getLastPublishedCaptures($numberCaptures);
        return $naoShowMap->formatPublishedCaptures($lastCaptures);
    }
}
