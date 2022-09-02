<?php

namespace App\Controller;

use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AjaxController extends AbstractController
{
    #[Route('/ajax/getLieux', name: 'app_ajax_ville')]
    public function index(Request $request, LieuRepository $lieuRepository): Response
    {
        $params = json_decode($request->getContent(), true);
        if ($params == null) {
            $params['id'] = 1;
            // TODO : Gérer les erreurs et les exceptions
        }
        $lieux = $lieuRepository->findBy(['ville' => $params['id']]);

        return $this->renderForm('sortie/_lieu.html.twig',[
            'lieux' => $lieux,
            'villes' =>0,
            'lieu'=>0,
            'idVille'=>0,
            'idLieu'=>0
        ]);
    }

    #[Route('/ajax/getInfosLieu', name: 'app_ajax_lieu')]
    public function lieu(Request $request, LieuRepository $lieuRepository, SerializerInterface $serializer): JsonResponse
    {
        $params = json_decode($request->getContent(), true);
        if ($params == null) {
            $params['id'] = 1;
            // TODO : Gérer un message d'erreur
        }
        $lieu = $lieuRepository->find($params['id']);

        $data= [];
        $data['nomDuLieu'] = $lieu->getNom()==null?"non précisé":$lieu->getNom();
        $data['rue'] = $lieu->getRue()==null?"non précisé":$lieu->getRue();
        $data['codePostal'] = $lieu->getVille()->getCodePostal()==null?"non précisé":$lieu->getVille()->getCodePostal();
        $data['latitude']=$lieu->getLatitude()==null?"non précisé":$lieu->getLatitude();
        $data['longitude']=$lieu->getLongitude()==null?"non précisé":$lieu->getLongitude();

        return new JsonResponse($serializer->serialize($data,'json'));
        /*return $this->renderForm('sortie/_lieu.html.twig',[
            'lieux' => $lieux,
        ]);*/
    }



    /*$encoders = array(new XmlEncoder(), new JsonEncoder());
      $normalizers = array(new ObjectNormalizer());
      $serializer = new Serializer($normalizers, $encoders);

      $jsonContent = $serializer->serialize($lieux, 'json', [
          'circular_reference_handler' => function ($object) {
              return $object->getId();
          }
      ]);
      return new JsonResponse($jsonContent);*/
}
