<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product", name="product_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        /**
         * @var $repository ProductRepository
         */
        $repository = $entityManager->getRepository(Product::class);
        return $this->render('product/index.html.twig', [
            'products' => $repository->findByFilter($request->request->all()),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id, Request $request, EntityManagerInterface $entityManager)
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        try {
            $entityManager->remove($product);
            $entityManager->flush();
            $this->addFlash('success', 'product.remove_success');
        } catch (\Exception $exception) {
            $this->addFlash('danger', 'product.remove_error');
        }

        return $this->redirect($this->generateUrl('product_list'));
    }

    /**
     * @Route("/new/", name="new")
     */
    public function new(Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $brochureFileName = $fileUploader->upload($image);
                $product->setFilename($brochureFileName);
            }

            try {
                $entityManager->persist($product);
                $entityManager->flush();

                $this->addFlash('success', 'create.success');
            } catch (\Exception $exception) {
                $this->addFlash('error', 'create.error');
            }

            return $this->redirect($this->generateUrl('product_list'));
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit($id, Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager)
    {
        $product = $entityManager->getRepository(Product::class)->find($id);
        if ($product === null) {
            $this->addFlash('danger', 'product.not_found');
            return $this->redirect($this->generateUrl('product_list'));
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $brochureFileName = $fileUploader->upload($image);
                $product->setFilename($brochureFileName);
            }

            try {
                $entityManager->persist($product);
                $entityManager->flush();

                $this->addFlash('success', 'create.success');
            } catch (\Exception $exception) {
                $this->addFlash('error', 'create.error');
            }

            return $this->redirect($this->generateUrl('product_list'));
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
